<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo text-center py-3">
        <a href="{{ route('dashboard.index') }}" class="app-brand-link d-flex align-items-center justify-content-center">
            <img 
                src="{{ asset('storage/logo.png') }}" 
                onerror="this.onerror=null; this.src='{{ asset('assets/bgpicture/default-logo.png') }}';"
                alt="Logo" 
                class="app-brand-logo"
                style="width: 180px; height: 180px; object-fit: contain; border-radius: 8px;"
            />
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="ti ti-x ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    @php
        $userRole = Auth::user()->getRoleNames()->first();
    @endphp

    <ul class="menu-inner py-1">

        {{-- Super Admin Menu --}}
        @if($userRole === 'super-admin')
            {{-- Dashboard --}}
            <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <i class="ti ti-home menu-icon"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            {{-- Employee Management --}}
            <li class="menu-item {{ request()->is('employee*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-briefcase menu-icon"></i>
                    <div>Employee Management</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('employee.list') ? 'active' : '' }}">
                        <a href="{{ route('employee.list') }}" class="menu-link"><div>Employee List</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('employee.create') ? 'active' : '' }}">
                        <a href="{{ route('employee.create') }}" class="menu-link"><div>Add Employee</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('employee.archived') ? 'active' : '' }}">
                        <a href="{{ route('employee.archived') }}" class="menu-link"><div>Terminated Employees</div></a>
                    </li>
                </ul>
            </li>

            {{-- Security Guard Tracking --}}
            <li class="menu-item {{ request()->is('security*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-shield-check menu-icon"></i>
                    <div>Security Guard Tracking</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('security.list') ? 'active' : '' }}">
                        <a href="{{ route('security.list') }}" class="menu-link"><div>List of Guards</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('security.deployments') ? 'active' : '' }}">
                        <a href="{{ route('security.deployments') }}" class="menu-link"><div>Deployments</div></a>
                    </li>
                </ul>
            </li>

            {{-- Applications --}}
            <li class="menu-item {{ request()->is('applications*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-file-text menu-icon"></i>
                    <div>Applications</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('job_postings.list') ? 'active' : '' }}">
                        <a href="{{ route('job_postings.list') }}" class="menu-link"><div>List of Job Postings</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('job_postings.create') ? 'active' : '' }}">
                        <a href="{{ route('job_postings.create') }}" class="menu-link"><div>Create Job Posting</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('applications.rejected') ? 'active' : '' }}">
                        <a href="{{ route('applications.rejected') }}" class="menu-link"><div>Rejected</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('applications.shortlist') ? 'active' : '' }}">
                        <a href="{{ route('applications.shortlist') }}" class="menu-link"><div>Shortlist</div></a>
                    </li>
                </ul>
            </li>

            {{-- Leave Requests --}}
            <li class="menu-item {{ request()->is('leaves*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-calendar-event menu-icon"></i>
                    <div>Time Keeping</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('leaves.pending') ? 'active' : '' }}">
                        <a href="{{ route('leaves.pending') }}" class="menu-link"><div>Pending Leaves</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('leaves.processed') ? 'active' : '' }}">
                        <a href="{{ route('leaves.processed') }}" class="menu-link"><div>Processed Leaves</div></a>
                    </li>
                </ul>
            </li>

            {{-- Incident Reports --}}
            <li class="menu-item {{ request()->is('incident-reports*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-alert-triangle menu-icon"></i>
                    <div>Incident Reports</div>
                </a>
                <ul class="menu-sub">
                    {{-- Submit IR visible to super-admin, admin, hr-officer, head-guard, security-guard --}}
                    @if(in_array($userRole, ['super-admin','admin','hr-officer','head-guard','security-guard']))
                        <li class="menu-item {{ request()->routeIs('incident-reports.index') ? 'active' : '' }}">
                            <a href="{{ route('incident-reports.index') }}" class="menu-link"><div>Submit IR</div></a>
                        </li>
                    @endif

                    {{-- IR Logs visible only to super-admin and admin --}}
                    @if(in_array($userRole, ['super-admin','admin']))
                        <li class="menu-item {{ request()->routeIs('incident-reports.logs') ? 'active' : '' }}">
                            <a href="{{ route('incident-reports.logs') }}" class="menu-link"><div>IR Logs</div></a>
                        </li>
                    @endif
                </ul>
            </li>

            {{-- Add Admin Account --}}
            <li class="menu-item {{ request()->routeIs('admin.add') ? 'active' : '' }}">
                <a href="{{ route('admin.add') }}" class="menu-link">
                    <i class="ti ti-user-plus menu-icon"></i>
                    <div>Add Admin Account</div>
                </a>
            </li>
        @endif

        {{-- HR Officer & Admin Menu --}}
        @if($userRole === 'hr-officer' || $userRole === 'admin')
            {{-- Dashboard --}}
            <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <i class="ti ti-home menu-icon"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            {{-- Employee Management --}}
            <li class="menu-item {{ request()->is('employee*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-briefcase menu-icon"></i>
                    <div>Employee Management</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('employee.list') ? 'active' : '' }}">
                        <a href="{{ route('employee.list') }}" class="menu-link"><div>Employee List</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('employee.create') ? 'active' : '' }}">
                        <a href="{{ route('employee.create') }}" class="menu-link"><div>Add Employee</div></a>
                    </li>
                </ul>
            </li>

            {{-- Security Guard Tracking --}}
            <li class="menu-item {{ request()->is('security*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-shield-check menu-icon"></i>
                    <div>Security Guard Tracking</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('security.list') ? 'active' : '' }}">
                        <a href="{{ route('security.list') }}" class="menu-link"><div>List of Guards</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('security.deployments') ? 'active' : '' }}">
                        <a href="{{ route('security.deployments') }}" class="menu-link"><div>Deployments</div></a>
                    </li>
                </ul>
            </li>

            {{-- Applications --}}
            <li class="menu-item {{ request()->is('applications*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-file-text menu-icon"></i>
                    <div>Job Applications</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('job_postings.list') ? 'active' : '' }}">
                        <a href="{{ route('job_postings.list') }}" class="menu-link"><div>Job Postings</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('applications.rejected') ? 'active' : '' }}">
                        <a href="{{ route('applications.rejected') }}" class="menu-link"><div>Rejected Applications</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('applications.shortlist') ? 'active' : '' }}">
                        <a href="{{ route('applications.shortlist') }}" class="menu-link"><div>Shortlisted Applicants</div></a>
                    </li>
                </ul>
            </li>

            {{-- Leave Requests --}}
            <li class="menu-item {{ request()->is('leaves*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-calendar-event menu-icon"></i>
                    <div>Time Keeping</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('leaves.request') ? 'active' : '' }}">
                        <a href="{{ route('leaves.request') }}" class="menu-link"><div>File Leave</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('leaves.pending') ? 'active' : '' }}">
                        <a href="{{ route('leaves.pending') }}" class="menu-link"><div>Pending</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('leaves.processed') ? 'active' : '' }}">
                        <a href="{{ route('leaves.processed') }}" class="menu-link"><div>Processed Leaves</div></a>
                    </li>
                </ul>
            </li>

            {{-- Incident Reports --}}
            <li class="menu-item {{ request()->is('incident-reports*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-alert-triangle menu-icon"></i>
                    <div>Incident Reports</div>
                </a>
                <ul class="menu-sub">
                    {{-- Submit IR visible to super-admin, admin, hr-officer, head-guard, security-guard --}}
                    @if(in_array($userRole, ['super-admin','admin','hr-officer','head-guard','security-guard']))
                        <li class="menu-item {{ request()->routeIs('incident-reports.index') ? 'active' : '' }}">
                            <a href="{{ route('incident-reports.index') }}" class="menu-link"><div>Submit IR</div></a>
                        </li>
                    @endif

                    {{-- IR Logs visible only to super-admin and admin --}}
                    @if(in_array($userRole, ['super-admin','admin']))
                        <li class="menu-item {{ request()->routeIs('incident-reports.logs') ? 'active' : '' }}">
                            <a href="{{ route('incident-reports.logs') }}" class="menu-link"><div>IR Logs</div></a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        {{-- Head Guard Menu --}}
        @if($userRole === 'head-guard')
            {{-- Security Guard Tracking --}}
            <li class="menu-item {{ request()->is('security*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-shield-check menu-icon"></i>
                    <div>Security Guard Tracking</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('security.list') ? 'active' : '' }}">
                        <a href="{{ route('security.list') }}" class="menu-link"><div>List of Guards</div></a>
                    </li>
                </ul>
            </li>

            {{-- Leave Requests --}}
            <li class="menu-item {{ request()->is('leaves*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-calendar-event menu-icon"></i>
                    <div>Time Keeping</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('leaves.request') ? 'active' : '' }}">
                        <a href="{{ route('leaves.request') }}" class="menu-link"><div>File Leave</div></a>
                    </li>
                </ul>
            </li>

            {{-- Incident Reports --}}
            <li class="menu-item {{ request()->is('incident-reports*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-alert-triangle menu-icon"></i>
                    <div>Incident Reports</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('incident-reports.index') ? 'active' : '' }}">
                        <a href="{{ route('incident-reports.index') }}" class="menu-link"><div>Submit IR</div></a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Security Guard Menu --}}
        @if($userRole === 'security-guard')
            {{-- Leave Requests --}}
            <li class="menu-item {{ request()->is('leaves*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-calendar-event menu-icon"></i>
                    <div>Time Keeping</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('leaves.request') ? 'active' : '' }}">
                        <a href="{{ route('leaves.request') }}" class="menu-link"><div>File Leave</div></a>
                    </li>
                </ul>
            </li>

            {{-- Incident Reports --}}
            <li class="menu-item {{ request()->is('incident-reports*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-alert-triangle menu-icon"></i>
                    <div>Incident Reports</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('incident-reports.index') ? 'active' : '' }}">
                        <a href="{{ route('incident-reports.index') }}" class="menu-link"><div>Submit IR</div></a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Applicant Menu --}}
        @if($userRole === 'applicant')
            {{-- Job Postings --}}
            <li class="menu-item {{ request()->routeIs('applicant.jobs') ? 'active' : '' }}">
                <a href="{{ route('applicant.jobs') }}" class="menu-link">
                    <i class="ti ti-briefcase menu-icon"></i>
                    <div>Job Postings</div>
                </a>
            </li>
        @endif

        {{-- Logout Button --}}
        <div class="menu-bottom-logout mt-auto text-center p-3" style="position: absolute; bottom: 10px; width: 100%;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px;">
                    <i class="ti ti-logout"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </ul>
</aside>
