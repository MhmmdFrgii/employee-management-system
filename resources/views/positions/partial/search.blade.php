<div class="col-lg-8 col-md-6 col-sm-12">
    <div class="d-flex flex-column flex-lg-row justify-content-end gap-2">
        <div class="search-box col-lg-3 col-12">
            <form action="{{ route('positions.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                        id="searchMemberList" placeholder="Cari Jabatan">
                    <div class="input-group-append">
                        <button type="submit" class="input-group-text rounded-end border border-1"><i
                                class="ri-search-line"></i></button>
                    </div>
                    <button type="submit" class="btn btn-primary d-lg-none mt-2 w-100">Cari</button>
                </div>
        </div>
        {{-- <div class="search-box col-lg-3 col-12">
            <div class="input-group">
                <input type="text" class="form-control flatpickr-input" name="date" value="{{ request('date') }}"
                    data-provider="flatpickr" placeholder="Pilih tanggal">
                <div class="input-group-append">
                    <button type="submit" class="input-group-text rounded-end border border-1"><i
                            class="ri-calendar-line"></i></button>
                </div>
            </div>
        </div> --}}

        {{-- <!-- Dropdown Filter Status proyek -->
        <div class="dropdown col-lg-3 col-12">
            <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" id="filterStatusDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                Status Proyek
            </button>
            <ul class="dropdown-menu" aria-labelledby="filterStatusDropdown">
                <li>
                    <label class="dropdown-item">
                        <input type="checkbox" name="status[]" value="active"
                            {{ in_array('active', request('status', [])) ? 'checked' : '' }} onchange="this.form.submit()"> Aktif
                    </label>
                </li>
                <li>
                    <label class="dropdown-item">
                        <input type="checkbox" name="status[]" value="completed"
                            {{ in_array('completed', request('status', [])) ? 'checked' : '' }} onchange="this.form.submit()"> Selesai
                    </label>
                </li>
            </ul>
        </div> --}}

    </form>
    <div class="form-check form-switch gap-3 col-lg-3 col-12 d-flex justify-content-between align-items-center mt-2 mt-lg-0"
        style="width: auto;">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#createpositionsModal">
            Tambah Jabatan
        </button>
    </div>
    </div>
</div>
