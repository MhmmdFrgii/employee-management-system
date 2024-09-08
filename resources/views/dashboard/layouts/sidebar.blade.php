<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="/manager/dashboard" class="text-nowrap logo-img">
                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg"
                    class="dark-logo" width="180" alt="" />
                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/light-logo.svg"
                    class="light-logo" width="180" alt="" />
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8 text-muted"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <!-- ============================= -->
                <!-- Home -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Menu</span>
                </li>
                <!-- =================== -->
                <!-- Dashboard -->
                <!-- =================== -->

                <li class="sidebar-item {{ Request::is('manager/dashboard') ? 'selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('manager.dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-aperture"></i>
                        </span>
                        <span class="hide-menu">Dasbor</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('manager/attendance*') ? 'selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('attendance.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-clipboard-data"></i>
                        </span>
                        <span class="hide-menu">Absensi</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('manager/projects*') ? 'selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('projects.index') }}" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-clipboard-list"></i>
                        </span>
                        <span class="hide-menu">Proyek</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('manager/positions*') ? 'selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('positions.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-list-details"></i>
                        </span>
                        <span class="hide-menu">Jabatan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('manager/departments*') ? 'selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('departments.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-category-2"></i>
                        </span>
                        <span class="hide-menu">Departemen</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('manager/employees*') ? 'selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('employees.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Karyawan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('manager/candidates*') ? 'selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('candidates.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-up"></i>
                        </span>
                        <span class="hide-menu">Pelamar</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('manager/salaries*') ? 'selected' : '' }}">
                    <a href="{{ route('salaries.index') }}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-coins"></i>
                        </div>
                        <span class="hide-menu">Gaji</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('manager/leave-requests*') ? 'selected' : '' }}">
                    <a href="{{ route('leave-requests.index') }}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-license"></i>
                        </div>
                        <span class="hide-menu">Permintaan Cuti</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('notifications*') ? 'selected' : '' }}">
                    <a href="{{ route('notifications.index') }}" class="sidebar-link d-flex align-items-center">
                        <span>
                            <i class="ti ti-inbox"></i>
                        </span>
                        <span class="hide-menu">Kotak Masuk</span>
                        @if (isset($newNotificationCount) && $newNotificationCount > 0)
                            <span class="badge bg-danger text-sm rounded-pill ms-auto p-1">
                                {{ $newNotificationCount }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
