<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar"
    style="position: sticky; top: 0; z-index: 1030;">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Hello message -->
            <li class="nav-item d-flex align-items-center me-3">
                <span class="navbar-text">
                    Hello, {{ auth()->user()->employee ? auth()->user()->employee->full_name : auth()->user()->name }}
                </span>
            </li>

            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ti ti-md"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- /Style Switcher-->

            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" aria-expanded="false">
                    <i class="ti ti-bell ti-md"></i>
                    <span class="badge bg-danger rounded-pill badge-notifications">5</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">Notification</h5>
                            <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Mark all as read"><i class="ti ti-mail-opened fs-4"></i></a>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container" style="max-height: 300px; overflow-y:auto;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <!-- notifications go here -->
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top">
                        <a href="javascript:void(0)"
                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online" style="width: 32px; height: 32px;">
                        <img 
                            src="{{ auth()->user()->employee && auth()->user()->employee->employee_image ? asset('storage/' . auth()->user()->employee->employee_image) : asset('assets/default-avatar.png') }}" 
                            alt="User Avatar" class="h-auto rounded-circle" style="width: 32px; height: 32px; object-fit: cover;" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online" style="width: 32px; height: 32px;">
                                        <img 
                                            src="{{ auth()->user()->employee && auth()->user()->employee->employee_image ? asset('storage/' . auth()->user()->employee->employee_image) : asset('assets/default-avatar.png') }}" 
                                            alt="User Avatar" class="h-auto rounded-circle" style="width: 32px; height: 32px; object-fit: cover;" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">{{ auth()->user()->employee ? auth()->user()->employee->full_name : auth()->user()->name }}</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <!-- Redirects to Profile page -->
                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">My Account</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-faq.html">
                            <i class="ti ti-help me-2 ti-sm"></i>
                            <span class="align-middle">FAQ</span>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item"
                                style="background: none; border: none; padding: 0; width: 100%; text-align: left;">
                                <i class="ti ti-logout me-2 ti-sm"></i>
                                <span class="align-middle">Log Out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

</nav>

<!-- Optional spacer so sticky navbar doesn't overlap content -->
<div style="height: 10px;"></div>
