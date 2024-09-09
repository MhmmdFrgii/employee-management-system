<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Buat Proyek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Proyek</label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" name="price" class="form-control" id="price"
                            value="{{ old('price') }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <input type="text" name="description" class="form-control" id="description"
                            value="{{ old('description') }}">
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" id="start_date"
                            value="{{ old('start_date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" id="end_date"
                            value="{{ old('end_date') }}">
                    </div>

                    <div class="mb-3">
                        <label for="department_id" class="form-label">Departemen</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="">Pilih Departemen</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"  {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Karyawan</label>
                        <select name="employee_id[]" id="employee_id" class="form-control js-example-basic-multiple" multiple="multiple">
                        </select>
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
    z-index: 9999; /* Pastikan dropdown muncul di atas elemen lain */
}
</style>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk employee_id
        $('#employee_id').select2({
            placeholder: "Pilih Karyawan",
            allowClear: true,
            width: '100%'
        });

        // Jika ada old values dari karyawan sebelumnya, maka load data ini
        var oldEmployeeIds = {!! json_encode(old('employee_id', [])) !!}; // Array of employee ids from old()
        var departmentId = $('#department_id').val(); // Get selected department

        if (departmentId) {
            loadEmployees(departmentId, oldEmployeeIds);
        }

        // Ketika departemen dipilih, muat karyawan terkait
        $('#department_id').on('change', function() {
            var departmentId = $(this).val();
            var employeeSelect = $('#employee_id');

            // Kosongkan pilihan karyawan jika departemen diubah
            employeeSelect.empty().trigger('change');

            if (departmentId) {
                loadEmployees(departmentId, []); // Load employees based on selected department
            }
        });

        // Fungsi untuk memuat data karyawan berdasarkan departemen yang dipilih
        function loadEmployees(departmentId, selectedEmployees) {
            var employeeSelect = $('#employee_id');
            $.ajax({
                url: '/manager/get-employees/' + departmentId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var options = '<option></option>'; // Placeholder untuk Select2
                    $.each(data, function(key, value) {
                        options += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                    employeeSelect.html(options);

                    // Jika ada karyawan yang sebelumnya dipilih (old value), set kembali
                    if (selectedEmployees.length > 0) {
                        employeeSelect.val(selectedEmployees).trigger('change');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Gagal memuat data karyawan:', error);
                }
            });
        }
    });
</script>
