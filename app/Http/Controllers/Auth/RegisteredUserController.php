<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departments = Department::all();

        return view('auth.register', compact('departments'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['required', 'exists:departments,id'],
            'fullname' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:20', 'unique:employee_details,nik'],
            'photo' => ['required', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'cv' => ['required', 'mimes:jpeg,jpg,png', 'max:2048'],
            'phone' => ['required', 'string', 'max:15', 'unique:employee_details,phone'],
            'gender' => ['required', 'in:male,female'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $cv = $request->file('cv')->store('cv');
        $photo = $request->file('photo')->store('photo');

        $employee = EmployeeDetail::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'fullname' => $request->fullname,
            'nik' => $request->nik,
            'photo' => $photo,
            'cv' => $cv,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
