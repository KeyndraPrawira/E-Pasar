

<!-- Sidebar Start -->
    <aside class="left-sidebar with-vertical">
      <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="{{ route('dashboard') }}" class="text-nowrap logo-img mt-1">
            <img src="{{ asset('template/images/logos/Pajajap logo.png')}}" width="150px" class="dark-logo" alt="Logo-Dark" />
            <img src="{{ asset('template/images/logos/Pajajap logo.png')}}" width="150px" class="light-logo" alt="Logo-light" />
          </a>
          <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x"></i>
          </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
          <ul id="sidebarnav">
             @if(Auth::check())
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Driver</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('driver.application.status') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-user-check"></i>
                </span>
                <span class="hide-menu">Status Driver</span>
              </a>
            </li>
            @if(Auth::user()->role === 'user')
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('driver.application.create') }}" aria-expanded="false">
                  <span>
                    <i class="ti ti-license"></i>
                  </span>
                  <span class="hide-menu">Daftar Driver</span>
                </a>
              </li>
            @endif
          @endif
          </ul>
        </nav>

       

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
      </div>
    </aside>
    <!--  Sidebar End -->
     
