<div class="col-lg-8 col-md-6 col-sm-12">
    <div class="d-flex flex-column flex-lg-row justify-content-end gap-2">
        <div class="search-box col-lg-3 col-12">
            <form action="{{ route('departments.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                        id="searchMemberList" placeholder="Cari Departemen">
                    <div class="input-group-append">
                        <button type="submit" class="input-group-text rounded-end border border-1"><i
                                class="ri-search-line"></i></button>
                    </div>
                    <button type="submit" class="btn btn-primary d-lg-none mt-2 w-100">Cari</button>
                </div>
        </div>
    </form>
    <div class="form-check form-switch gap-3 col-lg-3 col-12 d-flex justify-content-between align-items-center mt-2 mt-lg-0"
        style="width: auto;">
        <button data-bs-target="#createModal" data-bs-toggle="modal" class="btn btn-primary">
            Tambah Departemen
        </button>
    </div>
    </div>
</div>
