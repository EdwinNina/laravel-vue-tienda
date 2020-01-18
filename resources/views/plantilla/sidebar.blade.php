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
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
        aria-expanded="true" aria-controls="collapseBootstrap">
        <i class="icon-bag"></i>
        <span>Almacén</span>
        </a>
        <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="/categoria" @click.prevent="menu=1"><i class="icon-bag"></i> Categoria</a>
            <a class="collapse-item" href="/productos" @click.prevent="menu=2"><i class="icon-bag"></i> Productos</a>
        </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
        aria-controls="collapseTable">
        <i class="icon-wallet"></i>
        <span>Compras</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="/ingresos" @click.prevent="menu=3"><i class="icon-wallet"></i> Ingreso</a>
            <a class="collapse-item" href="/proveedores" @click.prevent="menu=4"><i class="icon-wallet"></i> Proveedores</a>
        </div>
        </div>
    </li>
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
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAcceso" aria-expanded="true"
        aria-controls="collapsePage">
        <i class="icon-people"></i>
        <span>Acceso</span>
        </a>
        <div id="collapseAcceso" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="#" @click.prevent="menu=7"><i class="icon-user"></i> Usuarios</a>
            <a class="collapse-item" href="#" @click.prevent="menu=8"><i class="icon-user-following"></i> Roles</a>
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
            <a class="collapse-item" href="#" @click.prevent="menu=9"><i class="icon-chart"></i> Reporte Ingresos</a>
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
  