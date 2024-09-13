<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class CompanyController extends Controller
{
    public function reset_code(Company $company): mixed
    {
        $company->update([
            'company_code' => Company::company_generate()
        ]);

        return redirect()->route('candidates.index')->with('success', 'berhasil mereset kode rekrut!');
    }

    public function reset_invite(Company $company): mixed
    {
        $company->update([
            'company_invite' => Company::company_generate()
        ]);

        return redirect()->route('candidates.index')->with('success', 'berhasil mereset kode undangan!');
    }

    public function updateOfficeHour(Request $request)
    {
        $request->validate([
            'checkin_start' => 'required',
            'checkin_end' => 'required',
        ]);

        $company = Auth::user()->company;

        $company->update([
            'checkin_start' => $request->checkin_start,
            'checkin_end' => $request->checkin_end,
        ]);

        return redirect()->back()->with('success', 'Jam masuk kantor berhasil diperbarui!');
    }
}
