<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Attendance System</title>

  <!-- Font Awesome Icons -->
  <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
    <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/b-print-1.6.2/r-2.2.5/datatables.min.css"/>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <router-link to="/app" class="nav-link">Home</router-link>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" role="button">{{auth()->user()->name}}
            <i class="ml-1 fas fa-angle-down"></i>
        </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
              <span class="dropdown-item dropdown-header">{{auth()->user()->role_label()->implode('/')}} Account</span>
              <div class="dropdown-divider"></div>
              <router-link v-if="auth>=2" :to="{name: 'edit_teacher_info'}" class="dropdown-item">
                <i class="fas fa-user-cog"></i> Edit Personal Details
              </router-link>
              <router-link v-if="auth==1" :to="{name: 'edit_student_info'}" class="dropdown-item">
                <i class="fas fa-user-cog"></i> Edit Personal Details
              </router-link>
              <div class="dropdown-divider"></div>
              <router-link :to="{name: 'change_password'}" class="dropdown-item">
                <i class="fas fa-key"></i> Change Password

              </router-link>
              <div class="dropdown-divider"></div>
              <a href="#" @click.prevent="logout()" class="dropdown-item">
                <i class="fas fa-sign-out-alt"></i> Log out
              </a>
            </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="app" class="brand-link navbar-primary">
      <img src="{{asset('img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text text-white font-weight-light">Attendance Sys</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
            <a href="#" class="d-block">
                {{auth()->user()->role_label()->implode('/')}}
            </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <sidebar-component :auth="auth"></sidebar-component>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <router-view :auth="auth"></router-view>
        <vue-progress-bar></vue-progress-bar>
    </div>

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <strong>Copyright &copy; 2020 <a href="https://attendance.io">Attendance System</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<script src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/b-print-1.6.2/r-2.2.5/datatables.min.js"></script>
<script src="{{asset('js/html5-qrcode.min.js')}}"></script>
<script src="{{asset('js/adminlte.min.js')}}"></script>
<script src="{{asset('js/external.js')}}"></script>
<!-- AdminLTE App -->

<script>

</script>
</body>
</html>


