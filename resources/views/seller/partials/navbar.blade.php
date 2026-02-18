<nav class="top-navbar">
    <div class="navbar-left">
        <button class="sidebar-toggle-btn d-lg-none"><i class="mdi mdi-menu"></i></button>
    </div>
    <div class="navbar-right">
        <a href="#" class="navbar-icon"><i class="mdi mdi-bell-outline"></i></a>
        <a href="#" class="navbar-icon"><i class="mdi mdi-help-circle-outline"></i></a>
        <div class="navbar-profile">
            <span class="profile-name">{{ Auth::user()->nama }}</span>
            <div class="profile-avatar" style="width: 32px; height: 32px; font-size: 0.8rem; margin-left: 10px;">
                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
            </div>
            <i class="mdi mdi-chevron-down profile-arrow"></i>
        </div>
    </div>
</nav>  