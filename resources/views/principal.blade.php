<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sistema Ventas Laravel Vue Js">
  <meta name="author" content="EdwinSys">
  <link href="img/logo.png" rel="icon">
  <title>Ventas / Compras</title>
  <link rel="stylesheet" href="css/plantilla.css">
  <link rel="stylesheet" href="css/app.css">
</head>

<body id="page-top">
    <div id="app">
      <div id="wrapper">
      <!-- Sidebar -->
        @if (Auth::check())
          @if (Auth::user()->role_id == 1)
              @include('plantilla.sidebarAdministrador')
            @elseif(Auth::user()->role_id == 2)
              @include('plantilla.sidebarVendedor')
            @elseif(Auth::user()->role_id == 3)
              @include('plantilla.sidebarAlmacenero')
            @else
          @endif
        @endif
      <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
              <!-- TopBar -->
              <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                  <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                  <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                      aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-bell fa-fw"></i>
                      <span class="badge badge-danger badge-counter">3+</span>
                    </a>
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                      aria-labelledby="alertsDropdown">
                      <h6 class="dropdown-header">
                        Alerts Center
                      </h6>
                      <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="mr-3">
                          <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                          </div>
                        </div>
                        <div>
                          <div class="small text-gray-500">December 12, 2019</div>
                          <span class="font-weight-bold">A new monthly report is ready to download!</span>
                        </div>
                      </a>
                      <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                    </div>
                  </li>
                  <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown"
                      aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-envelope fa-fw"></i>
                      <span class="badge badge-warning badge-counter">2</span>
                    </a>
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                      aria-labelledby="messagesDropdown">
                      <h6 class="dropdown-header">
                        Message Center
                      </h6>
                      <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="dropdown-list-image mr-3">
                          <img class="rounded-circle" src="img/man.png" style="max-width: 60px" alt="">
                          <div class="status-indicator bg-success"></div>
                        </div>
                        <div class="font-weight-bold">
                          <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been
                            having.</div>
                          <div class="small text-gray-500">Udin Cilok · 58m</div>
                        </div>
                      </a>
                    </div>
                  </li>
                  <div class="topbar-divider d-none d-sm-block"></div>
                  <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                      aria-haspopup="true" aria-expanded="false">
                      <img class="img-profile rounded-circle" src="img/boy.png" style="max-width: 60px">
                      <span class="ml-2 d-none d-lg-inline text-white small">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                      <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Cerrar sesion
                      </a>
                    </div>
                  </li>
                </ul>
              </nav>
              <!-- Topbar -->
      
              <!-- Container Fluid-->
              <div class="container-fluid" id="container-wrapper">
     
                <!-- content -->
                @yield('contenido')
                <!-- end content -->

              </div>
              <!---Container Fluid-->
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
              <div class="container my-auto">
                <div class="copyright text-center my-auto">
                  <span>copyright &copy; 2020 - developed by
                    <b><a href="#">EdwinSys</a></b>
                  </span>
                </div>
              </div>
            </footer>
            <!-- Footer -->
        </div>
      </div>
    </div>
  
    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <script src="js/plantilla.js"></script>
</body>
</html>

