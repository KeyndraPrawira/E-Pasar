

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
            <!-- ---------------------------------- -->
            <!-- Home -->
            <!-- ---------------------------------- -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Master Data</span>
            </li>
            <!-- ---------------------------------- -->
            <!-- Dashboard -->
            <!-- ---------------------------------- -->
             <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('dashboard') }}"  aria-expanded="false">
                <span>
                  <i class="ti ti-chart-bar"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('pasar.index') }}"  aria-expanded="false">
                <span>
                  <i class="ti ti-building-store"></i>
                </span>
                <span class="hide-menu">Pasar</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('pengguna.index') }}"  aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Pengguna</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('kios.index') }}"  aria-expanded="false">
                <span>
                  <i class="ti ti-door"></i>
                </span>
                <span class="hide-menu">Kios</span>
              </a>
            </li>
           
           
            <!-- ---------------------------------- -->
            <!-- Frontend page -->
            <!-- ---------------------------------- -->
             <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-app-window"></i>
                  </span>
                  <span class="hide-menu">Produk</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../main/frontend-landingpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Homepage</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/frontend-aboutpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">About Us</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/frontend-contactpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Contact Us</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/frontend-blogpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Blog</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/frontend-blogdetailpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Blog Details</span>
                    </a>
                  </li>
                </ul>
              </li>

          
           
          
           
          </ul>
        </nav>

       

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
      </div>
    </aside>
    <!--  Sidebar End -->
     