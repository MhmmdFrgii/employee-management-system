<!-- Modal Tambah Data -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Ubah ke employee_detail -->
                <!-- Ubah ke employee_detail -->
                <input type="hidden" name="employee_id" value="{{ Auth::user()->employee_detail->id }}">
                <!-- Tambahkan input hidden untuk company_id -->
                <input type="hidden" name="company_id" value="{{ Auth::user()->company->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Permintaan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Ubah ke employee_detail -->

                    <input type="hidden" value="{{ Auth::user()->company->id }}">
                    <input type="hidden" value="{{ Auth::user()->employee_detail->id }}">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Mulai Ijin</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                            id="start_date" name="start_date" value="{{ old('start_date') }}">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                            id="end_date" name="end_date" value="{{ old('end_date') }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Bukti</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo"
                            name="photo" value="{{ old('photo') }}">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="2">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Izin</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('type') is-invalid @enderror" type="radio"
                                    name="type" id="izin" value="izin"
                                    {{ old('type') == 'izin' ? 'checked' : '' }}>
                                <label class="form-check-label" for="izin">Izin</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('type') is-invalid @enderror" type="radio"
                                    name="type" id="sakit" value="sakit"
                                    {{ old('type') == 'sakit' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sakit">Sakit</label>
                            </div>
                        </div>
                        @error('type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
