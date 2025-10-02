@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
        <a href="">STISLA</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
        <a href="">STISLA</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            @if (in_array(Auth::user()->role, ['admin', 'superadmin']))
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fire"></i><span>Dashboard Admin</span></a>
                </li>
            @endif
            @if (Auth::user()->role == 'superadmin')
            <li class="menu-header">Hak Akses</li>
            <li class="{{ Request::is('hakakses') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('hakakses') }}"><i class="fas fa-user-shield"></i> <span>Hak Akses</span></a>
            </li>
            @endif
            @if (in_array(Auth::user()->role, ['admin','superadmin']))
            <li class="menu-header">Administration</li>
            <li class="{{ Request::is('admin/objectifs*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.objectives.index') }}"><i class="fas fa-bullseye"></i> <span>Objectifs types</span></a>
            </li>
            <li class="{{ Request::is('admin/users/objectifs*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.objectives.assignments') }}"><i class="fas fa-user-plus"></i> <span>Attribuer objectifs</span></a>
            </li>
            <li class="{{ Request::is('admin/partenaires*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.partners.index') }}"><i class="fas fa-handshake"></i> <span>Partenaires</span></a>
            </li>
            @endif
            <!-- profile ganti password -->
            <li class="menu-header">Profile</li>
            <li class="{{ Request::is('admin/profile/edit') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/profile/edit') }}"><i class="far fa-user"></i> <span>Profile</span></a>
            </li>
            <li class="{{ Request::is('admin/profile/change-password') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/profile/change-password') }}"><i class="fas fa-key"></i> <span>Ganti Password</span></a>
            </li>
            <li class="menu-header">Health</li>
            @if (in_array(Auth::user()->role, ['admin', 'superadmin']))
            <li class="{{ Request::is('admin/goals*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.goals.index') }}"><i class="fas fa-bullseye"></i> <span>Gestion Goals</span></a>
            </li>
            @else
            <li class="{{ Request::is('objectifs*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('front.objectives.index') }}"><i class="fas fa-bullseye"></i> <span>Mes objectifs</span></a>
            </li>
            @endif
            @if (Auth::user()->role === 'user')
            <li class="{{ Request::is('partenaires*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('front.partners.index') }}"><i class="fas fa-hospital"></i> <span>Partenaires Santé</span></a>
            </li>
            <li class="{{ Request::is('mes-favoris*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('front.partners.favorites') }}"><i class="fas fa-heart"></i> <span>Mes Favoris</span></a>
            </li>
            @endif
            <li class="menu-header">Starter</li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('blank-page') }}"><i class="far fa-square"></i> <span>Blank Page</span></a>
            </li>
            <li class="menu-header">Examples</li>
            <li class="{{ Request::is('table-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('table-example') }}"><i class="fas fa-table"></i> <span>Table Example</span></a>
            </li>
            <li class="{{ Request::is('clock-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('clock-example') }}"><i class="fas fa-clock"></i> <span>Clock Example</span></a>
            </li>
            <li class="{{ Request::is('chart-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('chart-example') }}"><i class="fas fa-chart-bar"></i> <span>Chart Example</span></a>
            </li>
            <li class="{{ Request::is('form-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('form-example') }}"><i class="fas fa-file-alt"></i> <span>Form Example</span></a>
            </li>
            <li class="{{ Request::is('map-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('map-example') }}"><i class="fas fa-map"></i> <span>Map Example</span></a>
            </li>
            <li class="{{ Request::is('calendar-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('calendar-example') }}"><i class="fas fa-calendar"></i> <span>Calendar Example</span></a>
            </li>
            <li class="{{ Request::is('gallery-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('gallery-example') }}"><i class="fas fa-images"></i> <span>Gallery Example</span></a>
            </li>
            <li class="{{ Request::is('todo-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('todo-example') }}"><i class="fas fa-list"></i> <span>Todo Example</span></a>
            </li>
            <li class="{{ Request::is('contact-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('contact-example') }}"><i class="fas fa-envelope"></i> <span>Contact Example</span></a>
            </li>
            <li class="{{ Request::is('faq-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('faq-example') }}"><i class="fas fa-question-circle"></i> <span>FAQ Example</span></a>
            </li>
            <li class="{{ Request::is('news-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('news-example') }}"><i class="fas fa-newspaper"></i> <span>News Example</span></a>
            </li>
            <li class="{{ Request::is('about-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('about-example') }}"><i class="fas fa-info-circle"></i> <span>About Example</span></a>
            </li>
        </ul>
    </aside>
</div>
@endauth
