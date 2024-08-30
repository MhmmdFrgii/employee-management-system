<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\EmployeeDetail;
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

        $company = Company::create([
            'name' => $request->company_name,
            'address' => $request->company_address,
            'contact_email' => $request->contact_email,
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

    public function create_employee()
    {
        return view('auth.register-employee');
    }

    public function store_employee(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'required|string|max:15',
            'gender' => 'required|string|in:male,female',
            'address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $photoPath = $request->file('photo')->store('employee_photos', 'public');

            EmployeeDetail::create([
                'name' => $request->name,
                'email' => $request->email,
                'photo' => $photoPath,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address
            ]);

            return redirect()->route('confirmation')->with('success', 'Berhasil Daftar Menunggu Konfirmasi!');
        } catch (\Throwable $e) {

            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            return redirect()->back()->with('error', 'Terjadi Kesalaah Pendaftaran.!')->withInput();
        }
    }
}
