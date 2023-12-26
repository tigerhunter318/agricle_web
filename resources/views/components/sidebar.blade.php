<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{route('view_user_list')}}" class="brand-link">
        <img src="{{ asset('admin_assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->avatar === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.Auth::user()->avatar) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->email }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
{{--                <li class="nav-item">--}}
{{--                    <a href="{{ route('admin_dashboard') }}" class="nav-link {{ (request()->is('admin/dashboard*')) ? 'active' : '' }}">--}}
{{--                        <i class="nav-icon fas fa-search"></i>--}}
{{--                        <p>--}}
{{--                            {{__('messages.sidebar.dashboard')}}--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a href="{{ route('view_user_list') }}" class="nav-link {{ (request()->is('admin/users*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{__('messages.sidebar.user_management')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('view_matter_list') }}" class="nav-link  {{ (request()->is('admin/matters*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            {{__('messages.sidebar.matter_management')}}
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
