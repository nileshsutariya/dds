<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>

    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- FullCalendar CDN Links -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 20px">
                        @if (Auth::guard('admin')->check())
                            {{ Auth::guard('admin')->user()->name }}
                        @endif
                    </a>
                    <div class="dropdown-menu" aria-labelledby="profileDropdown">
                        <!-- Add profile links or other options here -->
                        <a class="dropdown-item" href="{{ route('profile.create') }}">My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </li>

                <!-- Logout Form (hidden) -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <h3 class="brand-text font-weight-bold text-center">DDS</h3>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="true">

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dash') }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p class="text-bold" style="font-size: 16px">
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <!-- Master Menu -->
                        <li class="nav-item menu">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p class="text-bold" style="font-size: 16px">
                                    Master
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="text-bold" style="font-size: 14px">User</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin_client.index') }}"
                                        class="nav-link {{ request()->routeIs('admin_client.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="text-bold" style="font-size: 14px">Client</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('area.index') }}"
                                        class="nav-link {{ request()->routeIs('area.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="text-bold" style="font-size: 14px">Area</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Daily Entry -->
                        <li class="nav-item">
                            <a href="{{ route('daily.clients') }}" class="nav-link">
                                <i class="fas fa-user-plus"></i>
                                <p class="text-bold" style="font-size: 15px">
                                    Daily Entry
                                </p>
                            </a>
                        </li>

                        <!-- Daily Report -->
                        <li class="nav-item">
                            <a href="{{ route('calendar') }}" class="nav-link">
                                <i class="far fa-calendar-alt"></i>
                                <p class="text-bold" style="font-size: 15px; padding: 4px">
                                    Daily Report
                                </p>
                            </a>
                        </li>

                        <!-- Monthly Report -->
                        <li class="nav-item">
                            <a href="{{ route('monthly.report') }}" class="nav-link">
                                <i class="far fa-clock" style="font-size:15px"></i>
                                <p class="text-bold" style="font-size: 15px; padding: 4px">
                                    Monthly Report
                                </p>
                            </a>
                        </li>

                        <!-- Payment Menu -->
                        <li class="nav-item menu">
                            <a href="#" class="nav-link">
                                <i class="fa fa-credit-card" style="font-size:15px"></i>
                                <p class="text-bold" style="font-size: 16px">
                                    Payment
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('receive.payment') }}"
                                        class="nav-link {{ request()->routeIs('receive.payment') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="text-bold" style="font-size: 14px">Receive Payment</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('expense') }}"
                                        class="nav-link {{ request()->routeIs('expense') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="text-bold" style="font-size: 14px">Expense</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Payment Report -->
                        <li class="nav-item">
                            <a href="{{ route('payment.report') }}" class="nav-link">
                                <i class="fa fa-file-text-o"></i>
                                <p class="text-bold" style="font-size: 15px; padding: 4px">
                                    Payment Report
                                </p>
                            </a>
                        </li>

                        <!-- Settings -->
                        <li class="nav-item">
                            <a href="{{ route('settings') }}" class="nav-link">
                                <i class="fa fa-cogs" style="font-size: 15px"></i>
                                <p class="text-bold" style="font-size: 15px; padding: 4px">
                                    Settings
                                </p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
