<div class="">
  <nav class="navbar navbar-expand-lg" style="background-color: rgb(78,114,222)">
    <div class="container">
      {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button> --}}
      {{-- <div class="collapse navbar-collapse" id="navbarSupportedContent"> --}}
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <div class="d-flex align-items-center">
              <a href="{{url('/')}}"><img src="{{asset('assets/img/hp_logo.png')}}" alt="image_logo" style="height: auto; width: 100px;" class="m-2"></a>
              <div>
                <h3 style="font-family:sans-serif; color: #333; margin: 0;" class="text-white">Sanction Management</h3>
                <p style="font-size: 1.2rem; color: #555; margin: 0;" class="fw-bold text-white">Department of Panchayati Raj, Government of Himachal Pradesh.</p>
              </div>
            </div>
          </li>
        </ul>
        <ul class="navbar-nav">
          @guest
          <li class="nav-item">
            <a class="nav-link text-dark d-flex align-items-center fw-bold" href="{{Route('login')}}">
              <i class="fas fa-user text-white"></i>
              <span class="px-2 text-white">Login</span>
            </a>
          </li>
          @endguest
        </ul>
      {{-- </div> --}}
    </div>
  </nav>
  <hr style="height: 0.4px; color: white; background-color: white; border: none; margin: 0;">
</div>
