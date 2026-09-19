<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
    id="accordionSidebar">

    <!-- =========================================================
         SIDEBAR BRAND
         ========================================================= -->

    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       href="{{ url('gp/dashboard') }}">

        <div class="sidebar-brand-icon">
            <i class="fas fa-landmark"></i>
        </div>

        <div class="sidebar-brand-text mx-2">
            Gram Panchayat
        </div>

    </a>


    <!-- Divider -->
    <hr class="sidebar-divider my-0">


    <!-- =========================================================
         DASHBOARD
         ========================================================= -->

    <li class="nav-item active">

        <a class="nav-link"
           href="{{ url('gp/dashboard') }}">

            <i class="fas fa-fw fa-tachometer-alt"></i>

            <span>Dashboard</span>

        </a>

    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">


    <!-- =========================================================
         SANCTION
         ========================================================= -->

    <div class="sidebar-heading">
        Sanction
    </div>


    <!-- View Sanction -->

    <li class="nav-item">

        <a class="nav-link"
           href="{{ url('gp/view-sanction') }}">

            <i class="fas fa-fw fa-file-invoice-dollar"></i>

            <span>View Sanction</span>

        </a>

    </li>


    <!-- Status of Panchayat Ghar -->

    <li class="nav-item">

        <a class="nav-link"
           href="{{ url('gp/status') }}">

            <i class="fas fa-fw fa-home"></i>

            <span>Status of Panchayat Ghar</span>

        </a>

    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">


    <!-- =========================================================
         BUILDINGS OWNED BY PRI
         ========================================================= -->

    <div class="sidebar-heading">
        Building Owned by the PRI
    </div>


    <!-- Add Building -->

    <li class="nav-item">

        <a class="nav-link"
           href="{{ url('gp/add-assets') }}">

            <i class="fas fa-fw fa-plus-square"></i>

            <span>Add Building Owned by PRI</span>

        </a>

    </li>


    <!-- View Buildings -->

    <li class="nav-item">

        <a class="nav-link"
           href="{{ url('gp/view-assets') }}">

            <i class="fas fa-fw fa-building"></i>

            <span>View Building Owned by PRI</span>

        </a>

    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">


    <!-- =========================================================
         SIDEBAR TOGGLER
         ========================================================= -->

    <div class="text-center d-none d-md-inline">

        <button class="rounded-circle border-0"
                id="sidebarToggle">
        </button>

    </div>

</ul>