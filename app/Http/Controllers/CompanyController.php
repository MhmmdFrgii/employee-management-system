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
            'checkin_start' => 'required|date_format:H:i|before:checkin_end',
            'checkin_end' => 'required|date_format:H:i|after:checkin_start',
        ], [
            'checkin_start.required' => 'Waktu check-in awal harus diisi.',
            'checkin_start.date_format' => 'Format waktu check-in awal harus dalam format jam:menit (HH:mm).',
            'checkin_start.before' => 'Waktu check-in awal harus sebelum waktu check-in akhir.',

            'checkin_end.required' => 'Waktu check-in akhir harus diisi.',
            'checkin_end.date_format' => 'Format waktu check-in akhir harus dalam format jam:menit (HH:mm).',
            'checkin_end.after' => 'Waktu check-in akhir harus setelah waktu check-in awal.',
        ]);

        $company = Auth::user()->company;

        $company->update([
            'checkin_start' => $request->checkin_start,
            'checkin_end' => $request->checkin_end,
        ]);

        return redirect()->back()->with('success', 'Jam masuk kantor berhasil diperbarui!');
    }
}
