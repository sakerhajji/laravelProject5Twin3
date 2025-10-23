@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
        <a href="">Health Tracker</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
        <a href="">Health Tracker</a>
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
            <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> <span>Gestion Utilisateurs</span></a>
            </li>
            <li class="{{ Request::is('admin/objectifs*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.objectives.index') }}"><i class="fas fa-bullseye"></i> <span>Objectifs types</span></a>
            </li>
            <li class="{{ Request::is('admin/users/objectifs*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.objectives.assignments') }}"><i class="fas fa-user-plus"></i> <span>Attribuer objectifs</span></a>
            </li>
            <li class="{{ Request::is('admin/partenaires*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.partners.index') }}"><i class="fas fa-handshake"></i> <span>Partenaires</span></a>
            <li class="{{ Request::is('admin/aliments*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.aliments.index') }}"><i class="fas fa-utensils"></i> <span>Gestion des Aliments</span></a>
            </li>
            <li class="{{ Request::is('admin/repas*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.repas.index') }}"><i class="fas fa-drumstick-bite"></i> <span>Attribuer un repas à un utilisateur</span></a>
            </li>
                  <!-- Categories -->
            <li class="{{ Request::is('admin/categories*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-tags"></i> <span>Categories</span>
                </a>
            </li>

            <!-- Activities -->
            <li class="{{ Request::is('admin/activities*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.activities.index') }}">
                    <i class="fas fa-running"></i> <span>Activities</span>
                </a>
            </li>
                        <!-- Meeet -->
<li class="{{ Request::is('admin/create-meet*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.create.meet') }}">
        <i class="fas fa-video"></i> <span>Meet</span>
    </a>
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

            @if (in_array(Auth::user()->role, ['admin', 'superadmin']))
            <!-- Gestion Maladies & Asymptomes Section - ADMIN SEULEMENT -->
            <li class="menu-header">Gestion Santé</li>
            <li class="{{ Request::is('maladies*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('maladies.index') }}"><i class="fas fa-virus"></i> <span>Maladies</span></a>
            </li>
            <li class="{{ Request::is('asymptomes*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('asymptomes.index') }}"><i class="fas fa-notes-medical"></i> <span>Asymptômes</span></a>
            </li>
            @endif


            </li>

           <!-- <li class="menu-header">Starter</li>
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
            </li>-->

        </ul>
    </aside>
</div>
@endauth
