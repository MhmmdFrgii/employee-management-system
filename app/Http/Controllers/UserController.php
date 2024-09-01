<?php

namespace App\Http\Controllers;

use App\Mail\ApprovedMail;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use App\Models\InvitationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $company = Company::where('id', Auth::user()->company_id)->first();

        $applicants = EmployeeDetail::where('status', 'pending')
            ->where('company_id', $company->id)
            ->paginate(6);

        return view('applicant.index', compact('applicants', 'company'));
    }

    public function detail($id)
    {
        $applicant = EmployeeDetail::findOrFail($id);
        return view('applicant.detail', compact('applicant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeDetail $applicant)
    {
        $applicant_name = $applicant->name;
        $applicant_company = $applicant->company->name;
        $company_email = $applicant->company->email;
        $status = $request->status;

        if ($status == 'approved') {

            $invitation_code = InvitationCode::create([
                'code' => InvitationCode::invitation_generate(),
                'company_id' => $applicant->company->id
            ]);

            Mail::to($applicant->email)->send(new ApprovedMail($applicant_name, $applicant_company, $company_email, $invitation_code->code));
        }

        $applicant->update([
            'status' => $status
        ]);

        return redirect()->route('applicants.index')->with('success', 'User Applicant ' . $status . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
