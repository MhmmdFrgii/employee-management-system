<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
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

        $company = Company::create([
            'name' => $request->company_name,
            'address' => $request->company_address,
            'contact_email' => $request->contact_email,
            'company_code' => Company::company_generate()
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'approved',
        ])->assignRole('manager');

        event(new Registered($user));

        return redirect(route('login', absolute: false));
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
            recaptchaFieldName() => recaptchaRuleName()
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $photo = $request->file('photo')->store('employee_photos', 'public');

            $cv = $request->file('cv')->store('employee-cv', 'public');

            EmployeeDetail::create([
                'name' => $request->name,
                'email' => $request->email,
                'photo' => $photo,
                'cv' => $cv,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
                'company_id' => $company->id
            ]);

            return redirect()->route('confirmation')->with('success', 'Berhasil Daftar Menunggu Konfirmasi!');
        } catch (\Throwable $e) {

            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            return redirect()->back()->with('error', 'Terjadi Kesalaah Pendaftaran.!')->withInput();
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
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $applicant = EmployeeDetail::where('email', $request->email)
            ->where('status', 'approved')
            ->first();

        if (!$applicant) {
            return redirect()->back()->withErrors(['email' => 'Email tidak ditemukan dalam data kami.']);
        }

        $user = User::create([
            'company_id' => $applicant->company->id,
            'name' => $applicant->name,
            'email' => $applicant->email,
            'password' => $request->password,
            'status' => 'approved'
        ])->assignRole('employee');

        $applicant->update([
            'user_id' => $user->id,
            'hire_date' => date('Y-m-d')
        ]);

        $invitation_code = InvitationCode::where('code', $request->company)->first();
        $invitation_code->update([
            'status' => 'used',
            'used_by' => $applicant->id
        ]);

        return view('auth.login');
    }
}
