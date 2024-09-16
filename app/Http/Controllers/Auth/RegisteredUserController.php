<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\InvitationCode;
use App\Models\Notification;
use App\Models\User;
use App\Rules\UniqueEmail;
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
    public function apply_or_invite(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $companyInviteExists = Company::where('company_invite', $value)->exists();
                    $companyApplicantExists = Company::where('company_code', $value)->exists();

                    if (!$companyInviteExists && !$companyApplicantExists) {
                        $fail('Kode tidak valid!');
                    }
                }
            ],
        ], [
            'code.required' => 'Kode tidak boleh kosong!',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $code = $request->code;

        // Check if the code is an invitation code or an applicant code
        $companyInvite = Company::where('company_invite', $code)->first();
        $companyApplicant = Company::where('company_code', $code)->first();

        // If it's an invitation code
        if ($companyInvite) {
            return view('auth.register-invite'); // Redirect to invite registration view
        }

        // If it's an applicant code
        if ($companyApplicant) {
            return view('auth.apply-applicant'); // Redirect to applicant registration view
        }


        // If the code is invalid
        return redirect()->back()->withErrors(['invitation_code' => 'Kode tidak valid!'])->withInput();
    }


    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    // Handling Create a new company Account
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'contact_email' => 'required|email|unique:companies,contact_email',
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email', new UniqueEmail],
            'password' => 'required|string|min:8|confirmed',
        ],  [
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'company_name.string' => 'Nama perusahaan harus berupa teks.',
            'company_name.max' => 'Nama perusahaan maksimal 255 karakter.',

            'company_address.required' => 'Alamat perusahaan wajib diisi.',
            'company_address.string' => 'Alamat perusahaan harus berupa teks.',
            'company_address.max' => 'Alamat perusahaan maksimal 500 karakter.',

            'contact_email.required' => 'Email kontak wajib diisi.',
            'contact_email.email' => 'Format email kontak tidak valid.',
            'contact_email.unique' => 'Email kontak sudah digunakan.',

            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email.unique_email' => 'Email sudah digunakan oleh pengguna lain.', // Jika Anda memiliki aturan kustom `UniqueEmail`

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi harus memiliki minimal 8 karakter.',
            'password.confirmed' => 'Kata sandi konfirmasi tidak cocok.',
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
                'company_invite' => Company::company_generate()
            ]);

            $user = User::create([
                'company_id' => $company->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ])->assignRole('manager');

            event(new Registered($user));

            DB::commit();
            return redirect(route('login'))->with('success', 'Berhasil Membuat akun!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat registrasi.')->withInput();
        }
    }

    // store the applicant data to company
    public function store_applicant(Request $request)
    {
        $company = Company::where('company_code', $request->code)->first();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email', new UniqueEmail],
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cv' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'required|string|max:15',
            'gender' => 'required|string|in:male,female',
            'address' => 'required|string|max:500',
            'g-recaptcha-response' => 'recaptcha',
            recaptchaFieldName() => recaptchaRuleName(),
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email.unique_email' => 'Email sudah digunakan oleh pengguna lain.', // Jika Anda memiliki aturan kustom `UniqueEmail`

            'photo.required' => 'Foto wajib diisi.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
            'photo.mimes' => 'Foto harus berupa file dengan ekstensi jpeg, png, atau jpg.',
            'photo.max' => 'Foto maksimal 2 MB.',

            'cv.required' => 'CV wajib diisi.',
            'cv.image' => 'File yang diunggah harus berupa gambar.',
            'cv.mimes' => 'CV harus berupa file dengan ekstensi jpeg, png, atau jpg.',
            'cv.max' => 'CV maksimal 2 MB.',

            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon maksimal 15 karakter.',

            'gender.required' => 'Jenis kelamin wajib diisi.',
            'gender.string' => 'Jenis kelamin harus berupa teks.',
            'gender.in' => 'Jenis kelamin harus berupa "male" atau "female".',

            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat harus berupa teks.',
            'address.max' => 'Alamat maksimal 500 karakter.',

            'g-recaptcha-response.recaptcha' => 'Tolong verifikasi bahwa Anda bukan robot.', // Pastikan aturan recaptcha diatur dengan benar
            recaptchaFieldName() . '.' . recaptchaRuleName() => 'Tolong verifikasi captcha Anda.', // Pastikan Anda menyesuaikan nama field dan aturan
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
                'source' => 'applicant'
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

    // cek the company single use code, return employee regist based on company code
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

    // store employee account to spesific company
    public function store_employee(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:employee_details,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan dalam data pegawai.',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi harus memiliki minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',

            'password_confirmation.required' => 'Konfirmasi kata sandi wajib diisi.',
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

    public function store_invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'photo' => 'required|mimes:png,jpg,jpeg|max:3024',
            'gender' => 'required|string|in:male,female',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',

            'photo.required' => 'Foto wajib diunggah.',
            'photo.mimes' => 'Foto harus berupa file dengan ekstensi png, jpg, atau jpeg.',
            'photo.max' => 'Foto maksimal 3 MB.',

            'gender.required' => 'Jenis kelamin wajib diisi.',
            'gender.string' => 'Jenis kelamin harus berupa teks.',
            'gender.in' => 'Jenis kelamin harus berupa "male" atau "female".',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi harus memiliki minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Jika validasi gagal, kembali dengan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $company = Company::where('company_invite', $request->code)->first();

        DB::beginTransaction(); // Memulai transaksi

        try {
            // Upload photo
            $photo = $request->file('photo')->store('employee_photos', 'public');

            // Buat user baru
            $user = User::create([
                'company_id' => $company->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Buat detail karyawan
            EmployeeDetail::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'photo' => $photo,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
                'company_id' => $company->id,
                'source' => 'invited'
            ]);

            DB::commit();
            return redirect()->route('confirmation')->with('success', 'Berhasil Daftar, Menunggu Konfirmasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($photo)) {
                Storage::disk('public')->delete($photo);
            }

            dd($e);
            // Kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    // return single use setup page for location
    public function setup_location()
    {
        return view('auth.location-setup');
    }

    // store the setted location to a spesific company
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

        return redirect()->route($request->route)->with('success', 'Berhasil Update Lokasi!');
    }
}
