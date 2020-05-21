<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sistema Ventas Laravel Vue Js">
  <meta name="author" content="EdwinSys">
  <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">  
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ventas / Compras</title>
  <link href="img/logo.png" rel="icon">
  <link rel="stylesheet" href="css/plantilla.css">
  <link rel="stylesheet" href="css/app.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
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
                  <notification-component :notifications="notifications"></notification-component>
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

    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
</body>
</html>

