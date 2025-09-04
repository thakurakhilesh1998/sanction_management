<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url('dir/dashboard')}}">
        <div class="sidebar-brand-text mx-3">Directorate</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{url('dir/dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
       PR Sanction 
    </div>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/dir/')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>Add Sanction</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/dir/view')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>View Sanction</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/dir/xenreport')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>View Ex. En. Wise Report</span>
        </a>
    </li>
    
    <hr class="sidebar-divider">

    {{-- Rural Development Department Sanction --}}
    <!-- Heading -->
    <div class="sidebar-heading">
        RD Sanction 
    </div>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/dir/rd-add')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>Add Sanction</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/dir/view-rd')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>View Sanction</span>
        </a>
    </li>

    
    <hr class="sidebar-divider">


    <div class="sidebar-heading">
        Progress
    </div>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/dir/view-progress')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>View Progress</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/dir/view-pimage')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>Panchayat Ghar Details</span>
        </a>
    </li>
    <!-- Heading -->
    
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>