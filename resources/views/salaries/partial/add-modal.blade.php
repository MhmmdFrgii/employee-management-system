 <!-- Modal Create -->
 <div class="modal fade" id="createSalariesModal" tabindex="-1" aria-labelledby="createSalariesModalLabel"
     aria-hidden="true" data-bs-backdrop="static">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="createSalariesModalLabel">Buat Gaji</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form action="{{ route('salaries.store') }}" method="POST">
                     @csrf
                     <input type="hidden" name="company_id" value="{{ Auth::user()->company->id }}">
                     <div class="mb-3">
                         <label for="create_employee_id" class="form-label">Nama Karyawan</label>
                         <select name="employee_id" id="create_employee_id"
                             class="form-control @error('employee_id') is-invalid @enderror">
                             <option value="">Pilih Karyawan</option>
                             @foreach ($employees as $employee)
                                 <option value="{{ $employee->id }}"
                                     {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                     {{ $employee->name }}
                                 </option>
                             @endforeach
                         </select>
                         @error('employee_id')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="mb-3">
                         <label for="create_amount" class="form-label">Gaji</label>
                         <input type="text" class="form-control" id="create_amount" name="amount" readonly>
                     </div>
                     <div class="mb-3">
                         <label for="create_extra" class="form-label">Bonus Gaji</label>
                         <input type="text" name="extra" id="create_extra"
                             class="form-control @error('extra') is-invalid @enderror" value="{{ old('extra') }}">
                         @error('extra')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="mb-3" style="display: none;">
                         <label for="create_type" class="form-label">Jenis Transaksi</label>
                         <select name="type" id="create_type"
                             class="form-control @error('type') is-invalid @enderror">
                             <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Pengeluaran
                             </option>
                             <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                         </select>
                         @error('type')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="mb-3">
                         <label for="create_description" class="form-label">Deskripsi</label>
                         <input type="text" name="description" id="create_description"
                             class="form-control @error('description') is-invalid @enderror"
                             value="{{ old('description') }}">
                         @error('description')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="mb-3">
                         <label for="create_transaction_date" class="form-label">Tanggal Transaksi</label>
                         <input type="date" name="transaction_date" id="create_transaction_date"
                             class="form-control @error('transaction_date') is-invalid @enderror"
                             value="{{ old('transaction_date') }}">
                         @error('transaction_date')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <button type="submit" class="btn btn-primary">Buat</button>
                 </form>
             </div>
         </div>
     </div>
 </div>
