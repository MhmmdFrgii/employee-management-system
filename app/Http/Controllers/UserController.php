<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\ApprovedMail;
use App\Mail\RejectedMail;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use App\Models\InvitationCode;
use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $company = Company::where('id', Auth::user()->company_id)->first();

        $invitedApplicants = EmployeeDetail::where('status', 'pending')
            ->where('source', 'invited')
            ->where('company_id', $company->id)
            ->paginate(6);

        $applicants = EmployeeDetail::where('status', 'pending')
            ->where('source', 'applicant')
            ->where('company_id', $company->id)
            ->paginate(6);

        return view('candidates.index', compact('applicants', 'invitedApplicants', 'company'));
    }

    public function detail(EmployeeDetail $applicant)
    {
        $department = Department::where('company_id', Auth::user()->company->id)->get();
        $positions = Position::where('company_id', Auth::user()->company->id)->get();
        return view('candidates.detail', compact('applicant', 'department', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, EmployeeDetail $applicant)
    {
        // Ambil data yang sudah divalidasi dari UserRequest
        $validatedData = $request->validated();

        // Update departemen, posisi, dan set status menjadi 'approved'
        $applicant->update([
            'department_id' => $validatedData['department_id'],
            'position_id' => $validatedData['position_id'],
            'status' => 'approved',
            'salary' => $validatedData['salary']
        ]);

        // Jika statusnya 'approved', buat kode undangan dan kirim email
        $invitation_code = InvitationCode::create([
            'code' => InvitationCode::invitation_generate(),
            'company_id' => $applicant->company->id,
        ]);

        try {
            Mail::to($applicant->email)->send(new ApprovedMail(
                $applicant->name,
                $applicant->company->name,
                $applicant->company->email,
                $invitation_code->code
            ));
        } catch (\Exception $e) {
            return redirect()->route('candidates.index')->with('error', 'User approved but failed to send email.');
        }

        // Redirect ke index dengan pesan sukses
        return redirect()->route('candidates.index')->with('success', 'User Applicant approved.');
    }

    public function reject(EmployeeDetail $applicant)
    {
        // Update status menjadi 'rejected'
        $applicant->update([
            'status' => 'rejected',
        ]);

        try {

            Mail::to($applicant->email)->send(new RejectedMail(
                $applicant->name,
                $applicant->company->name
            ));
        } catch (\Exception $e) {
            return redirect()->route('candidates.index')->with('error', 'User rejected but failed to send email.');
        }

        // Redirect ke index dengan pesan sukses
        return redirect()->route('candidates.index')->with('success', 'User Applicant rejected.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Temukan data applicant berdasarkan ID
            $applicant = EmployeeDetail::findOrFail($id);

            // Update status applicant menjadi 'rejected'
            $applicant->update(['status' => 'rejected']);

            // Redirect ke index dengan pesan sukses
            return redirect()->route('candidates.index')->with('success', 'Applicant rejected successfully.');
        } catch (\Exception $e) {
            // Jika ada kesalahan, redirect ke index dengan pesan error
            return redirect()->route('candidates.index')->with('error', 'Failed to reject applicant.');
        }
    }
}
