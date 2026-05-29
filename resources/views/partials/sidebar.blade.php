<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo text-center py-3">
        <a href="{{ route('dashboard.index') }}" class="app-brand-link d-flex align-items-center justify-content-center">
            <img 
                src="{{ asset('favicon.ico') }}"
                alt="Logo" 
                class="app-brand-logo"
                style="width: 120px; height: 60px; object-fit: contain; border-radius: 8px;"
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

            {{-- User Management --}}
            <li class="menu-item {{ request()->routeIs('user-management.index') ? 'active' : '' }}">
                <a href="{{ route('user-management.index') }}" class="menu-link">
                    <i class="ti ti-users menu-icon"></i>
                    <div>User Management</div>
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

{{-- Security Guard Management --}}
            <li class="menu-item {{ request()->is('guard-scheduling*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-calendar-time menu-icon"></i>
                    <div>Security Guard Management</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('guard-scheduling.assign') ? 'active' : '' }}">
                        <a href="{{ route('guard-scheduling.assign') }}" class="menu-link"><div>Assign Schedule</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('guard-scheduling.deploy') ? 'active' : '' }}">
                        <a href="{{ route('guard-scheduling.deploy') }}" class="menu-link"><div>Deploy Guard</div></a>
                    </li>
                   
                   
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
                    @if(in_array($userRole, ['admin','hr-officer','head-guard','security-guard']))
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

        {{-- HR Officer & Admin Menu --}}
        @if($userRole === 'hr-officer' || $userRole === 'admin')
            {{-- Dashboard --}}
            <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <i class="ti ti-home menu-icon"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            {{-- User Management --}}
            <li class="menu-item {{ request()->routeIs('user-management.index') ? 'active' : '' }}">
                <a href="{{ route('user-management.index') }}" class="menu-link">
                    <i class="ti ti-users menu-icon"></i>
                    <div>User Management</div>
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

            {{-- Guard Scheduling --}}
            <li class="menu-item {{ request()->is('guard-scheduling*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-calendar-time menu-icon"></i>
                    <div>Security Guard Management</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('guard-scheduling.assign') ? 'active' : '' }}">
                        <a href="{{ route('guard-scheduling.assign') }}" class="menu-link"><div>Assign Schedule</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('guard-scheduling.deploy') ? 'active' : '' }}">
                        <a href="{{ route('guard-scheduling.deploy') }}" class="menu-link"><div>Deploy Guard</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('guard-scheduling.list') ? 'active' : '' }}">
                        <a href="{{ route('guard-scheduling.list') }}" class="menu-link"><div>Guard List</div></a>
                    </li>
                
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
                    @if(in_array($userRole, ['head-guard','security-guard']))
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

            {{-- My KPI --}}
            <li class="menu-item {{ request()->routeIs('my-kpi.index') ? 'active' : '' }}">
                <a href="{{ route('my-kpi.index') }}" class="menu-link">
                    <i class="ti ti-chart-bar menu-icon"></i>
                    <div>My KPI</div>
                </a>
            </li>

            {{-- Leave Requests --}}
            <li class="menu-item {{ request()->is('leaves*') || request()->is('attendance*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-calendar-event menu-icon"></i>
                    <div>Time Keeping</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('leaves.request') ? 'active' : '' }}">
                        <a href="{{ route('leaves.request') }}" class="menu-link"><div>File Leave</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                        <a href="{{ route('attendance.index') }}" class="menu-link"><div>My Shift</div></a>
                    </li>
                </ul>
            </li>

            {{-- View Security Guard Schedules --}}
            <li class="menu-item {{ request()->routeIs('guard-scheduling.view-all') ? 'active' : '' }}">
                <a href="{{ route('guard-scheduling.view-all') }}" class="menu-link">
                    <i class="ti ti-calendar-time menu-icon"></i>
                    <div>View Security Guard Schedules</div>
                </a>
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

            {{-- My KPI --}}
            <li class="menu-item {{ request()->routeIs('my-kpi.index') ? 'active' : '' }}">
                <a href="{{ route('my-kpi.index') }}" class="menu-link">
                    <i class="ti ti-chart-bar menu-icon"></i>
                    <div>My KPI</div>
                </a>
            </li>

            {{-- Leave Requests --}}
            <li class="menu-item {{ request()->is('leaves*') || request()->is('attendance*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="ti ti-calendar-event menu-icon"></i>
                    <div>Time Keeping</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('leaves.request') ? 'active' : '' }}">
                        <a href="{{ route('leaves.request') }}" class="menu-link"><div>File Leave</div></a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                        <a href="{{ route('attendance.index') }}" class="menu-link"><div>My Shift</div></a>
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

            {{-- My Applications --}}
            <li class="menu-item {{ request()->routeIs('applicant.applications') ? 'active' : '' }}">
                <a href="{{ route('applicant.applications') }}" class="menu-link">
                    <i class="ti ti-file-text menu-icon"></i>
                    <div>My Applications</div>
                </a>
            </li>

            {{-- My Credentials --}}
            <li class="menu-item {{ request()->routeIs('applicant.credentials') ? 'active' : '' }}">
                <a href="{{ route('applicant.credentials') }}" class="menu-link">
                    <i class="ti ti-id menu-icon"></i>
                    <div>My Credentials</div>
                </a>
            </li>

            
            {{-- Gamification --}}
            <li class="menu-item {{ request()->routeIs('applicant.gamification') ? 'active' : '' }}">
                <a href="{{ route('applicant.gamification') }}" class="menu-link">
                    <i class="ti ti-trophy menu-icon"></i>
                    <div>Gamification</div>
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

    {{-- Client Menu --}}
    @if($userRole === 'client')
        {{-- Dashboard --}}
        <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <a href="{{ route('dashboard.index') }}" class="menu-link">
                <i class="ti ti-home menu-icon"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- Notifications --}}
        <li class="menu-item {{ request()->is('notifications*') ? 'active open' : '' }}">
            <a href="{{ route('notifications.unread-count') }}" class="menu-link">
                <i class="ti ti-bell menu-icon"></i>
                <div>Notifications</div>
            </a>
        </li>

        {{-- Profile --}}
        <li class="menu-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
            <a href="{{ route('profile.show') }}" class="menu-link">
                <i class="ti ti-user menu-icon"></i>
                <div>Profile</div>
            </a>
        </li>

        {{-- Security Guard Tracking --}}
        <li class="menu-item {{ request()->is('guard-scheduling*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="ti ti-shield-check menu-icon"></i>
                <div>Security Guard Tracking</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('guard-scheduling.view-all') ? 'active' : '' }}">
                    <a href="{{ route('guard-scheduling.view-all') }}" class="menu-link">
                        <div>Shift Schedule View</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('guard-scheduling.list') ? 'active' : '' }}">
                    <a href="{{ route('guard-scheduling.list') }}" class="menu-link">
                        <div>Security Guard Management</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('my-kpi.index') ? 'active' : '' }}">
                    <a href="{{ route('my-kpi.index') }}" class="menu-link">
                        <div>Key Performance Indicator Evaluation</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                    <a href="{{ route('attendance.index') }}" class="menu-link">
                        <div>Attendance Record</div>
                    </a>
                </li>
                {{-- Removed Attendance Monitoring as route does not exist --}}
            </ul>
        </li>

        {{-- Incident Report (dropdown) --}}
        <li class="menu-item {{ request()->is('incident-reports*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="ti ti-alert-triangle menu-icon"></i>
                <div>Incident Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('incident-reports.logs') ? 'active' : '' }}">
                    <a href="{{ route('incident-reports.logs') }}" class="menu-link">
                        <div>IR Logs</div>
                    </a>
                </li>
            </ul>
        </li>


        {{-- Leave Request --}}
        <li class="menu-item {{ request()->routeIs('leaves.list') ? 'active' : '' }}">
            <a href="{{ route('leaves.list') }}" class="menu-link">
                <i class="ti ti-calendar-event menu-icon"></i>
                <div>Leave Request</div>
            </a>
        </li>
    @endif
</ul>
</aside>
