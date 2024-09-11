<div class="col-lg-8 col-md-6 col-sm-12">
    <div class="d-flex flex-column flex-lg-row justify-content-end gap-2">
        <div class="search-box col-lg-3 col-12">
            <form action="{{ route('finance.index') }}">
        </div>
        <div class="search-box col-lg-3 col-12">
            <div class="input-group">
                <input type="text" class="form-control flatpickr-input" name="date" value="{{ request('date') }}"
                    data-provider="flatpickr" placeholder="Pilih tanggal">
                <div class="input-group-append">
                    <button type="submit" class="input-group-text rounded-end border border-1"><i
                            class="ri-calendar-line"></i></button>
                </div>
            </div>
        </div>

        <!-- Dropdown Filter Status proyek -->
        <div class="dropdown col-lg-3 col-12">
            <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" id="filterStatusDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                Status Transaksi
            </button>
            <ul class="dropdown-menu" aria-labelledby="filterStatusDropdown">
                <li>
                    <label class="dropdown-item">
                        <input type="checkbox" name="status[]" value="income"
                            {{ in_array('income', request('status', [])) ? 'checked' : '' }} onchange="this.form.submit()"> Pemasukan
                    </label>
                </li>
                <li>
                    <label class="dropdown-item">
                        <input type="checkbox" name="status[]" value="expense"
                            {{ in_array('expense', request('status', [])) ? 'checked' : '' }} onchange="this.form.submit()"> Pengeluaran
                    </label>
                </li>
            </ul>
        </div>

    </form>
    </div>
</div>
