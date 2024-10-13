<aside class="main-sidebar elevation-4 sidebar-light-warning">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
      <img src="{{asset("dist/img/logo.png")}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Packaging Films</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset("dist/img/user2-160x160.jpg")}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name}}</a>
        </div>
        {{-- <div class="">
          <a href="#" class="d-block">{{ Auth::user()->role->name}}</a>
        </div> --}}
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          {{-- <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Starter Pages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inactive Page</p>
                </a>
              </li>
            </ul>
          </li> --}}
          <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link active">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashbaord
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>
          @if(session('role_id') == 1)
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                  User Management
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('user.index')}}" class="nav-link">
                    <i class="fas fa-list nav-icon"></i>
                    <p>User List</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('user.add')}}" class="nav-link">
                    <i class="fas fa-plus nav-icon"></i>
                    <p>Add User</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-truck nav-icon"></i>
                <p>
                  Supplier  Management
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('supplier.index')}}" class="nav-link">
                    <i class="fas fa-list nav-icon"></i>
                    <p>Supplier List</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('supplier.add')}}" class="nav-link">
                    <i class="fas fa-plus nav-icon"></i>
                    <p>Add Supplier</p>
                  </a>
                </li>
              </ul>
            </li>
          @endif  
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-book-open"></i>
              <p>
                LC Opening Requests 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('lc_request.pending.index')}}" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Pending LC Requests</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('lc_request.transmitted.index')}}" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Tranmitted LC Requests</p>
                </a>
              </li>
              @if(in_array(session('role_id'),[1,5]))
                <li class="nav-item">
                  <a href="{{route('lc_request.add')}}" class="nav-link">
                    <i class="fas fa-plus nav-icon"></i>
                    <p>Add Request</p>
                  </a>
                </li>
              @endif  
            </ul>
          </li>

          {{-- @if(in_array(session('role_id'),[1,4,5])) --}}
          <li class="nav-item">
            <a href="#" class="nav-link">
             
              <i class="fas fa-book-reader"></i>
          
              <p>
                LC Amendment Requests
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('amendment_request.index')}}" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          {{-- @endif   --}}
          <li class="nav-item">
            <a href="#" class="nav-link">
             
              <i class="fas fa-tablet"></i>
          
              <p>
                Clearance Requests
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('clearance_request.index')}}" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>