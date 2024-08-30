<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereHas('employee_detail', function ($query) {
            $query->where('status', 'disapprove');
        })->with('employeeDetails')->paginate(6);
        return view('applicant.index', compact('users'));
    }

    public function detail($id)
    {
        $user = User::with('employee_detail.department', 'employee_detail.position')->findOrFail($id);
        $employeeDetails = $user->employee_detail;
        return view('applicant.detail', compact('user', 'employeeDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->update([
            'status' => $request->status,
        ]);

        if ($request->status == "approve") {
            $user->assignRole('karyawan');
        }

        return redirect()->route('applicant.index')->with('success', 'User Applicant Approved.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
