<!-- Modal Edit -->
<div class="modal fade" id="editModal{{ $project->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $project->id }}"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $project->id }}">Ubah Proyek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama proyek</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="name" value="{{ old('name', $project->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                            id="price" value="{{ old('price', number_format($project->price, 0, '.', '')) }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                            rows="4">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                    value="{{ old('start_date', $project->start_date) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                    value="{{ old('end_date', $project->end_date) }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department_id" class="form-label">Departemen</label>
                                <select name="department_id" id="edit-department-{{ $project->id }}"
                                    class="form-control @error('department_id') is-invalid @enderror">
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id', $project->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Karyawan</label>
                                <select name="employee_id[]" id="edit-employee-{{ $project->id }}"
                                    class="form-control js-example-basic-multiple" multiple="multiple">
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
</div>

<style>
    /* Gaya Select2 untuk tampilan yang lebih baik */
    .select2-container--default .select2-selection--multiple {
        background-color: #fff !important;
        border: 1px solid #ccc !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #bcb9b9 !important;
    }

    .select2-container--default .select2-dropdown {
        z-index: 9999;
        /* Pastikan dropdown muncul di atas elemen lain */
    }
</style>

{{-- <script>
    $(document).ready(function() {
        $('#editModal{{ $project->id }}').on('shown.bs.modal', function() {
            // Inisialisasi Select2 untuk employee_id
            $('#edit-employee-{{ $project->id }}').select2({
                placeholder: "Pilih Karyawan",
                allowClear: true,
                width: '100%'
            });

            // Ambil nilai lama dari employee_id
            var selectedEmployees = {!! json_encode(old('employee_id', $project->employee_details->pluck('id')->toArray())) !!};

            // Ketika modal ditampilkan, otomatis ambil karyawan berdasarkan departemen yang sudah terpilih
            var departmentId = $('#edit-department-{{ $project->id }}').val();
            var employeeSelect = $('#edit-employee-{{ $project->id }}');

            if (departmentId) {
                $.ajax({
                    url: '/manager/get-employees/' + departmentId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option></option>'; // Placeholder for Select2
                        $.each(data, function(key, value) {
                            options += '<option value="' + value.id + '" ' + (selectedEmployees.includes(value.id) ? 'selected' : '') + '>' + value.name + '</option>';
                        });
                        employeeSelect.html(options).trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal memuat data karyawan:', error);
                    }
                });
            }

            // Event ketika departemen dipilih
            $('#edit-department-{{ $project->id }}').on('change', function() {
                var departmentId = $(this).val();
                var employeeSelect = $('#edit-employee-{{ $project->id }}');

                // Kosongkan dropdown employee saat departemen diubah
                employeeSelect.empty().trigger('change');

                if (departmentId) {
                    $.ajax({
                        url: '/manager/get-employees/' + departmentId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var options = '<option></option>'; // Placeholder for Select2
                            $.each(data, function(key, value) {
                                options += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                            employeeSelect.html(options).trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error('Gagal memuat data karyawan:', error);
                        }
                    });
                }
            });
        });
    });
</script> --}}

<script>
    $(document).ready(function() {
        $('#editModal{{ $project->id }}').on('shown.bs.modal', function() {
            // Inisialisasi Select2 untuk employee_id
            $('#edit-employee-{{ $project->id }}').select2({
                placeholder: "Pilih Karyawan",
                allowClear: true,
                width: '100%'
            });

            // Ambil nilai lama (old) atau nilai dari relasi karyawan terkait proyek
            var selectedEmployees = {!! json_encode(old('employee_id', $project->employee_details->pluck('id')->toArray())) !!};

            // Ambil department_id terpilih untuk memuat karyawan terkait
            var departmentId = $('#edit-department-{{ $project->id }}').val();
            var employeeSelect = $('#edit-employee-{{ $project->id }}');

            // Fungsi untuk memuat karyawan berdasarkan department_id
            function loadEmployees(departmentId, selectedEmployees) {
                if (departmentId) {
                    $.ajax({
                        url: '/manager/get-employees/' + departmentId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var options = '<option></option>'; // Placeholder
                            $.each(data, function(key, value) {
                                options += '<option value="' + value.id + '" ' +
                                    (selectedEmployees.includes(value.id) ?
                                        'selected' : '') + '>' +
                                    value.name + '</option>';
                            });
                            employeeSelect.html(options).trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error('Gagal memuat data karyawan:', error);
                        }
                    });
                }
            }

            // Muat karyawan saat modal ditampilkan
            if (departmentId) {
                loadEmployees(departmentId, selectedEmployees);
            }

            // Event ketika departemen diubah
            $('#edit-department-{{ $project->id }}').on('change', function() {
                var departmentId = $(this).val();

                // Kosongkan dropdown employee saat departemen diubah
                employeeSelect.empty().trigger('change');

                // Muat karyawan baru berdasarkan departemen yang baru dipilih
                if (departmentId) {
                    loadEmployees(departmentId, []);
                }
            });
        });
    });
</script>
