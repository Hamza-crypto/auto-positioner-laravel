<div class="menu">
    <div class="menu-header">
        <a href="index.html" class="menu-header-logo">
            <img src="{{ asset('assets/img/logo.svg') }}" alt="logo">
        </a>
        <a href="index.html" class="btn btn-sm menu-close-btn">
            <i class="bi bi-x"></i>
        </a>
    </div>
    <div class="menu-body" tabindex="2" style="overflow: hidden; outline: none;">
        <ul>
            <li class="menu-divider">Manage</li>
            <li class="">
                <a class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}"
                    href="{{ route('dashboard.index') }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-card-checklist"></i>
                    </span>
                    <span>Dashboard</span>
                </a>

            </li>
            <li class="">
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-person-circle"></i>
                    </span>
                    <span>Employees</span>
                </a>
                <ul style="display: none;">
                    <li><a class="{{ request()->is('users') ? 'active' : '' }}" href="{{ route('users.index') }}">All
                            Employees</a></li>
                    <li><a class="{{ request()->is('users/create') ? 'active' : '' }}"
                            href="{{ route('users.create') }}">Add New Employee</a></li>
                </ul>
            </li>

            <li class="">
                <a href="#">
                    <span class="nav-link-icon">

                        <i class="bi bi-command"></i>
                    </span>
                    <span>Positions</span>
                </a>
                <ul style="display: none;">
                    <li><a class="{{ request()->is('positions') ? 'active' : '' }}"
                            href="{{ route('positions.index') }}">All
                            Positions</a></li>
                </ul>
            </li>

        </ul>
    </div>

</div>
