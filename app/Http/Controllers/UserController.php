<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function updateStatus(Request $request, User $user)
    {
        // Validasi status yang diberikan oleh request
        $request->validate([
            'status' => 'required|in:approve,disapprove'
        ]);

        // Update status pengguna
        $user->status = $request->status;
        $user->save();

        if ($request->status == 'approve') {
            // Jika disetujui, pindahkan data ke EmployeeDetail
            $employeeDetail = EmployeeDetail::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id, // Pastikan ini berasal dari request atau set sesuai kebutuhan Anda
                'position_id' => $request->position_id,     // Pastikan ini berasal dari request atau set sesuai kebutuhan Anda
                'fullname' => $request->fullname ?? $user->name,
                'nik' => $request->nik,
                'photo' => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
                'cv' => $request->file('cv') ? $request->file('cv')->store('cvs', 'public') : null,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'hire_date' => $request->hire_date,
            ]);

            // Jika perlu, hapus atau setel ulang data pengguna lainnya
            // Contoh:
            // $user->name = null;
            // $user->department_id = null; 
            // $user->position_id = null;
            // $user->save();
            
        } elseif ($request->status == 'disapprove') {
            // Jika tidak disetujui, hapus data EmployeeDetail terkait
            $user->employeeDetail()->delete();
        }

        return redirect()->route('applicant.detail', ['id' => $user->id])->with('success', 'Status updated successfully.');
    }

    public function index(Request $request)
    {
        $users = User::with('employeeDetails.department', 'employeeDetails.position')->paginate(6);
        return view('applicant.index', compact('users')); 
    }

    public function detail($id)
    {
        $user = User::with('employeeDetails.department', 'employeeDetails.position')->findOrFail($id);
        $employeeDetails = $user->employeeDetails;
        return view('applicant.detail', compact('user', 'employeeDetails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status ?? 'disapprove', // Status default
        ]);

        // Pindahkan data ke EmployeeDetail jika disetujui
        if ($request->status === 'approve') {
            $this->moveToEmployeeDetail($user, $request);
        }

        return redirect()->route('applicant.index')->with('success', 'User created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update([
            'name' => $request->fullname,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'status' => $request->status,
        ]);

        // Cek jika pengguna disetujui dan belum ada di EmployeeDetail
        if ($request->status === 'approve' && !$user->employee_detail_id) {
            $this->moveToEmployeeDetail($user, $request);
        }

        return redirect()->route('applicant.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function moveToEmployeeDetail(User $user, Request $request)
    {
        DB::transaction(function () use ($user, $request) {
            // Buat entri EmployeeDetail
            $employeeDetail = EmployeeDetail::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'fullname' => $user->name,
                'nik' => $request->nik,
                'photo' => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
                'cv' => $request->file('cv') ? $request->file('cv')->store('cvs', 'public') : null,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'hire_date' => $request->hire_date,
            ]);

            // Update user untuk mereferensikan EmployeeDetail
            $user->update([
                'employee_detail_id' => $employeeDetail->id,
            ]);
        });
    }

}
