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
                <a href="{{ route('dashboard.index')}}">
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
                    <i class="sub-menu-arrow bi bi-arrow-right"></i> 
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
                    <i class="sub-menu-arrow bi bi-arrow-right"></i>
                </a>
                <ul style="display: none;">
                    <li><a class="{{ request()->is('positions') ? 'active' : '' }}"
                            href="{{ route('positions.index') }}">All
                            Positions</a></li>

                </ul>
            </li>

        </ul>
    </div>
    <div id="ascrail2001" class="nicescroll-rails nicescroll-rails-vr"
        style="width: 8px; z-index: 998; cursor: default; position: absolute; top: 98px; left: 342px; height: 229px; display: block; opacity: 0;">
        <div class="nicescroll-cursors"
            style="position: relative; top: 0px; float: right; width: 6px; height: 32px; background-color: rgb(66, 66, 66); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px;">
        </div>
    </div>
    <div id="ascrail2001-hr" class="nicescroll-rails nicescroll-rails-hr"
        style="height: 8px; z-index: 998; top: 319.111px; left: 0px; position: absolute; cursor: default; display: none; width: 342px; opacity: 0;">
        <div class="nicescroll-cursors"
            style="position: absolute; top: 0px; height: 6px; width: 350px; background-color: rgb(66, 66, 66); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px; left: 0px;">
        </div>
    </div>
</div>
