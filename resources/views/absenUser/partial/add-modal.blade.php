 <!-- Modal Tambah Data -->
 <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true"
     data-bs-backdrop="static">
     <div class="modal-dialog">
         <div class="modal-content">
             <form action="{{ route('leave.store') }}" method="POST">
                 @csrf
                 <input type="hidden" name="employee_id" value="{{ Auth::user()->employeeDetails->id }}">
                 <div class="modal-header">
                     <h5 class="modal-title" id="addModalLabel">Tambah Permintaan Cuti
                     </h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <input type="hidden" value="{{ Auth::user()->employeeDetails->id }}">
                     <div class="mb-3">
                         <label for="start_date" class="form-label">Mulai Ijin</label>
                         <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                             id="start_date" name="start_date" value="{{ old('start_date') }}">
                         @error('start_date')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="mb-3">
                         <label for="end_date" class="form-label">Sampai
                             Tanggal</label>
                         <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                             id="end_date" name="end_date" value="{{ old('end_date') }}">
                         @error('end_date')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="mb-3">
                         <label for="type" class="form-label">Type</label>
                         <input type="text" class="form-control @error('type') is-invalid @enderror" id="type"
                             name="type" value="{{ old('type') }}">
                         @error('type')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <input type="hidden" name="status" value="pending">
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                     <button type="submit" class="btn btn-primary">Simpan</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
