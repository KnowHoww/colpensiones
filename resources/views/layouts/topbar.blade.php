<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown no-arrow mx-1">
            
             <a class="nav-link" href="/notificaciones" id="alertsDropdown" 
                >
                <i class="fas fa-bell fa-fw"></i>
                @if (Auth::user()->notificacionQ() >0)
                <div style = "border-radius: 50%; width: 36px; height: 36px; padding: 8px; background: #fff; border: 2px solid #BA0021; color: #BA0021; text-align: center;font: 15px Arial, sans-serif;" >
                
                
                {{ Auth::user()->notificacionQ() }} </div>
                @endif
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter"></span>
            </a>
        </li> 

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
                @if (auth()->check())
                    <span
                        class="mr-2 d-none d-lg-inline text-gray-600 small">{{ optional(Auth::user()->centroCostos)->nombre }} - {{ Auth::user()->roles->pluck('name')->implode(', ') }}
                        - {{ Auth::user()->email }}</span>
                @endif
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <!-- Add a link for user profile modification -->
                <a class="dropdown-item" href="{{ route('edituser') }}">
                    <i class="mr-2 text-gray-400"></i>
                    <span>Modificar Usuario</span>
                </a>
                <!-- End of user profile modification link -->
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="mr-2 text-gray-400"></i>
                    <span>Cerrar Sesión</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>


    </ul>

</nav>
<!-- End of Topbar -->
