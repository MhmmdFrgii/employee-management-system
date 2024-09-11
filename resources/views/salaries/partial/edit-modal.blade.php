  <!-- Modal Edit -->
  <div class="modal fade" id="editSalariesModal{{ $salary->id }}" tabindex="-1"
    aria-labelledby="editSalariesModalLabel{{ $salary->id }}" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSalariesModalLabel{{ $salary->id }}">Edit
                    Gaji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('salaries.update', $salary->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="company_id"
                        value="{{ Auth::user()->company->id }}">
                    <div class="mb-3">
                        <label for="edit_employee_id" class="form-label">Nama
                            Karyawan</label>
                        <select name="employee_id" id="edit_employee_id"
                            class="form-control @error('employee_id') is-invalid @enderror">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $employee->id == old('employee_id', $salary->employee_id) ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_amount" class="form-label">Gaji</label>
                        <input type="text" name="amount"
                            class="form-control @error('amount') is-invalid @enderror"
                            readonly id="edit_amount"
                            value="{{ old('amount', $salary->amount) }}">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_extra" class="form-label">Bonus Gaji</label>
                        <input type="text" name="extra"
                            class="form-control @error('extra') is-invalid @enderror"
                            id="edit_extra" value="{{ old('extra', $salary->extra) }}">
                        @error('extra')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3" style="display: none;">
                        <label for="edit_type" class="form-label">Jenis Transaksi</label>
                        <select name="type" id="edit_type"
                            class="form-control @error('type') is-invalid @enderror">
                            <option value="income"
                                {{ old('type', $salary->type) == 'income' ? 'selected' : '' }}>
                                Pemasukan</option>
                            <option value="expense"
                                {{ old('type', $salary->type) == 'expense' ? 'selected' : '' }}>
                                Pengeluaran</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <input type="text" name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            id="edit_description"
                            value="{{ old('description', $salary->description) }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_transaction_date" class="form-label">Tanggal
                            Transaksi</label>
                        <input type="date" name="transaction_date"
                            class="form-control @error('transaction_date') is-invalid @enderror"
                            id="edit_transaction_date"
                            value="{{ old('transaction_date', $salary->transaction_date) }}">
                        @error('transaction_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
