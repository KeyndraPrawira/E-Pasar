<header class="topbar">
  <div class="with-vertical">
    <!-- ---------------------------------- -->
    <!-- Start Vertical Layout Header -->
    <!-- ---------------------------------- -->
    <nav class="navbar navbar-expand-lg p-0">
      <ul class="navbar-nav">
        <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
          <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
      </ul>

      <div class="d-block d-lg-none py-4">
        <a href="../main/index.html" class="text-nowrap logo-img">
          <img src="../assets/template/images/logos/dark-logo.svg" class="dark-logo" alt="Logo-Dark" />
          <img src="../assets/template/images/logos/light-logo.svg" class="light-logo" alt="Logo-light" />
        </a>
      </div>

       

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <div class="d-flex align-items-center justify-content-between">
          <a href="javascript:void(0)" class="nav-link nav-icon-hover-bg rounded-circle mx-0 ms-n1 d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
            <i class="ti ti-align-justified fs-7"></i>
          </a>

          <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
            <!-- ------------------------------- -->
            <!-- Dark Mode Toggle -->
            <!-- ------------------------------- -->
            <li class="nav-item nav-icon-hover-bg rounded-circle">
              <a class="nav-link moon dark-layout" href="javascript:void(0)">
                <i class="ti ti-moon moon"></i>
              </a>
              <a class="nav-link sun light-layout" href="javascript:void(0)">
                <i class="ti ti-sun sun"></i>
              </a>
            </li>

            <!-- ------------------------------- -->
            <!-- start profile Dropdown -->
            <!-- ------------------------------- -->
            <li class="nav-item dropdown">
              <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                <div class="d-flex align-items-center">
                  <div class="user-profile-img">
                    <img src="../assets/template/images/profile/user-1.jpg" class="rounded-circle" width="35" height="35" alt="modernize-img" />
                  </div>
                </div>
              </a>
              <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                <div class="profile-dropdown position-relative" data-simplebar>
                  <div class="py-3 px-7 pb-0">
                    <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                  </div>
                  <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                    <img src="../assets/template/images/profile/user-1.jpg" class="rounded-circle" width="80" height="80" alt="modernize-img" />
                    <div class="ms-3">
                      <h5 class="mb-1 fs-3">{{ Auth::user()->name}}</h5>
                      <span class="mb-1 d-block">{{ Auth::user()->role}}</span>
                      <p class="mb-0 d-flex align-items-center gap-2">
                        <i class="ti ti-mail fs-4"></i> {{ Auth::user()->email}}
                      </p>
                    </div>
                  </div>
                  <div class="message-body">
                    <a href="" class="py-8 px-7 mt-8 d-flex align-items-center">
                      <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                        <img src="../assets/template/images/svgs/icon-account.svg" alt="modernize-img" width="24" height="24" />
                      </span>
                      <div class="w-100 ps-3">
                        <h6 class="mb-1 fs-3 fw-semibold lh-base">Profile Saya</h6>
                        <span class="fs-2 d-block text-body-secondary">Account Settings</span>
                      </div>
                    </a>
                  </div>
                  <div class="d-grid py-4 px-7 pt-8">
                    <div class="upgrade-plan bg-primary-subtle position-relative overflow-hidden rounded-4 p-4 mb-9">
                      <div class="row">
                        <div class="col-6">
                          <div class="m-n4 unlimited-img">
                            <img src="../assets/template/images/backgrounds/unlimited-bg.png" alt="modernize-img" class="w-100" />
                          </div>
                        </div>
                      </div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                    <a href="../main/authentication-login.html" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-primary">Log Out</a>
                  </div>

                  </form>
                  
                </div>
              </div>
            </li>
            <!-- ------------------------------- -->
            <!-- end profile Dropdown -->
            <!-- ------------------------------- -->
          </ul>
        </div>
      </div>
    </nav>
    <!-- ---------------------------------- -->
    <!-- End Vertical Layout Header -->
    <!-- ---------------------------------- -->

    <!-- ------------------------------- -->
    <!-- Mobile navbar -->
    <!-- ------------------------------- -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="mobilenavbar" aria-labelledby="offcanvasWithBothOptionsLabel">
      <nav class="sidebar-nav scroll-sidebar">
        <div class="offcanvas-header justify-content-between">
          <img src="../assets/template/images/logos/favicon.ico" alt="modernize-img" class="img-fluid" />
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body h-n80" data-simplebar="" data-simplebar>
          <ul id="sidebarnav">
            <!-- Mobile menu content here -->
            <li class="sidebar-item">
              <a class="sidebar-link" href="../main/index.html" aria-expanded="false">
                <span>
                  <i class="ti ti-home"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <!-- Add more mobile menu items as needed -->
          </ul>
        </div>
      </nav>
    </div>
  </div>
</header>
