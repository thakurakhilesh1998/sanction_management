<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: black">
              Gram Panchayat: {{Auth::user()->gp_name}}<img class="img-profile rounded-circle"
              src="{{url(asset('assets/img/undraw_profile.svg'))}}">
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" style="color: black">Block name: <b>{{Auth::user()->block_name}}</b></a>
            </li>
            <li>
              <a class="dropdown-item" style="color: black">GP name: <b>{{Auth::user()->gp_name}}</b></a>
            </li>
              {{-- <li>
                <a class="dropdown-item" href="{{url('dir/change-password')}}" style="color: black">Change Password</a>
              </li> --}}
              <li>
                <a class="dropdown-item" style="color:black" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form action="{{route('logout')}}" method="POST" id="logout-form" class="d-none">
                @csrf
                </form>
                
                {{-- <a class="dropdown-item"  href="#">Logout</a></li> --}}
            </ul>
          </li>
    </ul>

</nav>
