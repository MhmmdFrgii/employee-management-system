<!-- Modal Edit -->
<div class="modal fade" id="editModal{{ $project->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $project->id }}"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
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
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                            id="price" value="{{ old('price', number_format($project->price, 0, '.', '')) }}">
                        @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Departemen</label>
                        <select name="department_id" id="edit-department-{{ $project->id }}" class="form-control">
                            <option value="">Pilih Departemen</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $project->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Ditugaskan kepada</label>
                        <select class="js-example-basic-multiple form-control w-100" name="employee_id[]"
                            id="edit-employee-{{ $project->id }}" multiple="multiple">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" @selected(in_array($employee->id, old('employee_id[]', $project->employee_details->pluck('id')->toArray())))>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id[]')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date"
                            class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                            value="{{ old('start_date', $project->start_date) }}">
                        @error('start_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date"
                            class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                            value="{{ old('end_date', $project->end_date) }}">
                        @error('end_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 pada modal edit ketika modal ditampilkan
        $('#editModal{{ $project->id }}').on('shown.bs.modal', function() {
            // Inisialisasi Select2 untuk employee_id
            $('#edit-employee-{{ $project->id }}').select2({
                placeholder: "Pilih Karyawan",
                allowClear: true,
                width: '100%'
            });

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
                            var options =
                                '<option></option>'; // Placeholder for Select2
                            $.each(data, function(key, value) {
                                options += '<option value="' + value.id +
                                    '">' + value.name + '</option>';
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
</script>
