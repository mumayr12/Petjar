<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="background-color: rgba(255,231,193,255);">

    <button id="sidebarToggleTop" class="btn btn-link  rounded-circle mr-3">
      <i class="fa fa-bars"></i>
    </button>


    <ul class="navbar-nav ml-auto">

      <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-search fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
          <form class="form-inline mr-auto w-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      {{-- Home page --}}
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" target="_blank" data-toggle="tooltip" data-placement="bottom" title="home"  role="button">
          <i class="fas fa-home fa-fw"></i>
        </a>
      </li>

      <li class="nav-item dropdown no-arrow mx-1">

      </li>

      <li class="nav-item dropdown no-arrow mx-1" id="messageT" data-url="#">

      </li>

      <div class="topbar-divider d-none d-sm-block"></div>

      {{-- Wrap the user info section with @auth to check if a user is logged in --}}
      @auth
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{Auth()->user()->name}}</span>
          @if(Auth()->user()->photo)
            <img class="img-profile rounded-circle" src="{{Auth()->user()->photo}}">
          @else
            <img class="img-profile rounded-circle" src="{{asset('backend/img/avatar.png')}}">
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="{{ route('admin-profile') }}">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            Profile
          </a>
          <a class="dropdown-item" href="#">
            <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
            Change Password
          </a>
          <a class="dropdown-item" href="#">
            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
            Settings
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#"
                 onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                 <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> {{ __('Logout') }}
            </a>

            {{-- Corrected the form action to a valid logout route --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
      </li>
      @else
          {{-- Optional: Display a login link or alternative if not authenticated --}}
          <li class="nav-item dropdown no-arrow">
              <a class="nav-link" href="{{ route('login') }}">
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small">Login</span>
                  <i class="fas fa-sign-in-alt"></i>
              </a>
          </li>
      @endauth

    </ul>

  </nav>