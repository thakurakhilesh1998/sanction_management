<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url('dir/dashboard')}}">
        <div class="sidebar-brand-text mx-3">District</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{url('district/dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    @if($errors->has('error'))
    <div class="alert alert-danger">
    {{ $errors->first('error') }}
    </div>
    @endif
    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
        Sanction 
    </div>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('district/add-sanction')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>Add Sanction</span>
        </a>
        <a class="nav-link collapsed" href="{{url('district/view-sanction')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>Manage Sanction</span>
        </a>
        {{-- <a class="nav-link collapsed" href="{{url('district/')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>View Sacntion<br>(which are not reported)</span>
        </a>
        <a class="nav-link collapsed" href="{{url('district/update')}}" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <span>Update Progress</span>
        </a> --}}
    </li>
    <hr class="sidebar-divider">
    <!-- Heading -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('district/view-sanction-dir')}}" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <span>View Sanction</span>
    </a>
    </li>
    
    
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('district/change-sanction-district')}}" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <span>Change Sanction</span>
    </a>
    </li> --}}

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Panchayat Ghar Details
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('district/view-block-status')}}" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <span>Panchayat Ghar Status</span>
    </a>
    </li>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>