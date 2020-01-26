<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
        <img src="img/logo2.png">
        </div>
        <div class="sidebar-brand-text mx-3">SysVentas</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
        <a class="nav-link" href="#" @click="menu=0">
        <i class="icon-speedometer"></i>
        <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Mantenimiento
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVentas" aria-expanded="true" aria-controls="collapsePage">
            <i class="icon-basket"></i>
            <span>Ventas</span>
        </a>
        <div id="collapseVentas" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/ventas" @click.prevent="menu=5"><i class="icon-basket-loaded"></i> Salidas</a>
                <a class="collapse-item" href="/clientes" @click.prevent="menu=6"><i class="icon-notebook"></i> Clientes</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReportes" aria-expanded="true"
        aria-controls="collapsePage">
        <i class="icon-pie-chart"></i>
        <span>Reportes</span>
        </a>
        <div id="collapseReportes" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="#" @click.prevent="menu=10"><i class="icon-chart"></i> Reporte Egresos</a>
        </div>
        </div>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link" @click.prevent="menu=11">
            <i class="icon-book-open"></i>
            <span>Ayuda</span>
        </a>
    </li>
    </ul>
      <!-- Sidebar -->
  