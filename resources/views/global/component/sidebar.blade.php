<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Sistem Antrian v1.0</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets/img/blank-profile.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ url('/dashboard') }}" class="nav-link {{ $slug=="dashboard" ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/rincian-loket') }}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Rincian Loket
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/user') }}" class="nav-link {{ $slug=="user" ? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Data Pengguna
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/outlet') }}" class="nav-link {{ $slug=="outlet" ? 'active' : ''}}">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Data Outlet
              </p>
            </a>
          </li>
          <li class="nav-item {{ Request::is('setting/*') ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('setting/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Data Setting Antrian
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/setting/purpose') }}" class="nav-link {{ $slug=="purpose" ? 'active' : ''}}">
                  <i class="nav-icon far fa-flag"></i>
                  <p>Data Tujuan Loket</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/setting/loket') }}" class="nav-link {{ $slug=="loket" ? 'active' : ''}}">
                  <i class="nav-icon far fas fa-th"></i>
                  <p>Data Loket</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/setting/display') }}" class="nav-link {{ $slug=="display" ? 'active' : ''}}">
                  <i class="nav-icon fas fa-window-maximize"></i>
                  <p>
                    Data Antarmuka
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/setting/printer') }}" class="nav-link {{ $slug=="printer" ? 'active' : ''}}">
                  <i class="nav-icon fas fa-print"></i>
                  <p>
                    Data Printer Antrian
                  </p>
                </a>
              </li>
            </ul>
            <li class="nav-item">
              <form action="{{ route('logout') }}" method="get">
                @csrf
                  <button class="btn btn-danger btn-block text-left" type="submit" onclick="return confirm('Are you sure?')">&nbsp;<i class="nav-icon fas fa-power-off"></i>Logout</button>
              </form>
            </li>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>