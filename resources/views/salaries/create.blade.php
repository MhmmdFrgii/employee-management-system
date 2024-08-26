<x-app-layout>
    <form action="{{ route('salaries.store')}}" method="POST" class="mb-5">
        @csrf
        <div class="mb-3">
            <label for="user" class="form-label">Nama Karyawan</label>
            <input type="text" name="employee" class="form-control" id="employee" placeholder="Masukkan Nama Karyawan">
            {{-- <select name="user" id="user" class="form-control">
                <option value="">--Pilih Karyawan--</option>
                @foreach ($users as $item)
                    <option value="{{ $item->id }}"> {{ $item->name }} </option>
                @endforeach
            </select> --}}
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Gaji</label>
            <input type="text" name="amount" class="form-control" id="amount" placeholder="Masukkan Nilai Gaji">
        </div>
        <div class="mb-3">
            <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="payment_date" class="form-control" id="payment_date"
            placeholder="Masukkan Tanggal Pembayaran">
        </div>
        <button type="submit">Submit</button>
    </form>
</x-app-layout>

@extends('name')