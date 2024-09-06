<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\InvitationCode;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'contact_email' => 'required|email|unique:companies,contact_email',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Jika validasi gagal, kembali dengan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $company = Company::create([
                'name' => $request->company_name,
                'address' => $request->company_address,
                'contact_email' => $request->contact_email,
                'company_code' => Company::company_generate(),
            ]);

            $user = User::create([
                'company_id' => $company->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'approved',
            ])->assignRole('manager');

            event(new Registered($user));

            DB::commit();
            return redirect(route('login'))->with('success', 'Berhasil Membuat akun!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat registrasi.')->withInput();
        }
    }

    public function apply_applicant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applicant' => 'required|string|exists:companies,company_code',
        ], [
            'applicant.exists' => 'Kode lamaran tidak valid!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return view('auth.apply-applicant');
    }

    public function store_applicant(Request $request)
    {
        $company = Company::where('company_code', $request->company_code)->first();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cv' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'required|string|max:15',
            'gender' => 'required|string|in:male,female',
            'address' => 'required|string|max:500',
            'g-recaptcha-response' => 'recaptcha',
            recaptchaFieldName() => recaptchaRuleName(),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $photoPath = $request->file('photo')->store('employee_photos', 'public');
            $cvPath = $request->file('cv')->store('employee-cv', 'public');

            EmployeeDetail::create([
                'name' => $request->name,
                'email' => $request->email,
                'photo' => $photoPath,
                'cv' => $cvPath,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
                'company_id' => $company->id,
            ]);

            $manager = User::where('company_id', $company->id)
                ->role('manager')
                ->first();

            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Pelamar baru telah bergabung',
                'message' => 'Seorang pelamar baru telah mendaftar. Silakan tinjau lamaran mereka di portal rekrutmen.',
                'type' => 'info',
                'url' => 'applicants.index'
            ]);

            DB::commit();
            return redirect()->route('confirmation')->with('success', 'Berhasil Daftar, Menunggu Konfirmasi!');
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            if (isset($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }
            return redirect()->back()->with('error', 'Terjadi Kesalahan Pendaftaran.')->withInput();
        }
    }

    public function create_employee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company' => 'required|string|exists:invitation_codes,code',
        ], [
            'company.exists' => 'Kode lamaran tidak valid!',
        ]);

        if ($validator->fails()) {
            return redirect()->route('landing-page')->withErrors($validator)->withInput();
        }

        $invitation = InvitationCode::where('code', $request->company)
            ->where('status', 'unused')
            ->first();

        if (!$invitation) {
            return redirect()->route('landing-page')->withErrors(['company' => 'Kode lamaran sudah digunakan atau tidak valid!'])->withInput();
        }

        return view('auth.register-employee');
    }

    public function store_employee(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:employee_details,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $applicant = EmployeeDetail::where('email', $request->email)
            ->where('status', 'approved')
            ->first();

        if (!$applicant) {
            return redirect()->back()->withErrors(['email' => 'Email tidak ditemukan dalam data kami.']);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'company_id' => $applicant->company->id,
                'name' => $applicant->name,
                'email' => $applicant->email,
                'password' => Hash::make($request->password),
                'status' => 'approved',
            ])->assignRole('employee');

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Selamat Datang!',
                'message' => 'Akun Anda telah berhasil dibuat. Selamat bergabung dan selamat bekerja!',
                'type' => 'success',
                'url' => ''
            ]);

            $applicant->update([
                'user_id' => $user->id,
                'hire_date' => now(),
            ]);

            $invitation_code = InvitationCode::where('code', $request->company)->first();
            $invitation_code->update([
                'status' => 'used',
                'used_by' => $applicant->id,
            ]);

            DB::commit();
            return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silakan login.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat pembuatan akun.')->withInput();
        }
    }

    public function setup_location()
    {
        return view('auth.location-setup');
    }

    public function store_location(Request $request)
    {

        $company_id = Auth::user()->company_id;

        $company = Company::where('id', $company_id)->first();

        $messages = [
            'required' => 'Lokasi harus diisi!'
        ];

        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $company->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return redirect()->route('manager.dashboard')->with('success', 'Berhasil Update Lokasi!');
    }
}
