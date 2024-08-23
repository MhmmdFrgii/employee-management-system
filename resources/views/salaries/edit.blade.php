<x-app-layout>
    <form action="{{ route('salaries.update', $salarie->id) }}" method="POST" class="mb-5">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="user" class="form-label">Nama Karyawan</label>
            <input type="text" name="employee" class="form-control" id="employee"
            value="{{ old('employee', $salarie->employee) }}">
            {{-- <select name="user_id" id="user" class="form-control">
                <option value="">--Pilih Karyawan--</option>
                @foreach ($users as $item)
                    <option value="{{ $item->id }}"
                        {{ old('user_id', $salarie->user_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select> --}}
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Gaji</label>
            <input type="text" name="amount" class="form-control" id="amount"
            value="{{ old('amount', $salarie->amount) }}">
        </div>
        <div class="mb-3">
            <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="payment_date" class="form-control" id="payment_date"
            value="{{ old('payment_date', $salarie->payment_date) }}">
        </div>
        <button type="submit" class="btn btn-primary">Edit</button>
    </form>
</x-app-layout>
