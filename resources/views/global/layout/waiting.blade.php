<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }} | Sistem Antrian v1.0</title>
  <link rel="icon" href="{{ asset('assets/img/logo-pengayoman.png') }}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- jQuery -->
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

  <!-- Flex Align Center Horizontal & Custom Color for Navbar &-->
  <style>
    .flex-container {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .navbar-warning {
      background-color: #232359;
      color: #fff;
    }

    .footer {
      position:fixed;
      bottom:0px;
      right: 0px;
      width:100%;
      z-index:1000;
      padding:2px;
      margin:auto;
      text-align:center;
      float:none;
      box-shadow: 0px -2px 10px #c0c0c0;
      background-color: #232359;
      color: #fff;
    }

    .logo1 {
      background-color: #fff;
      border-style: solid;
      border-width: thin;
    }


  </style>

  @stack('style')
</head>
<body class="hold-transition sidebar-mini" onload="startTime()">
  <div class="wrapper hw-100">
  <!-- Navbar -->
  <div class="row">
    <div class="col-lg-12">
      <nav class="navbar navbar-expand navbar-warning">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item d-none d-sm-inline-block">
            <div class="flex-container">
              <img src="{{ url('assets/img/logo-pengayoman.png') }}" alt="Logo" class="p-2 ml-2 logo1" width="110">
              <img src="{{ url('assets/img/logo-pemasyarakatan.png') }}" alt="Logo" class="p-2" height="137">
              <div class="nav-link align-items-center"> 
                <h1 class="display-4">
                  <b>{{ $outlet->name }}</b>
                </h1>
                <h5>{{ $outlet->address }}</h5>
              </div>
            </div>
          </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item d-none d-sm-inline-block">
            <div class="nav-link">
              <h4>
                <b><div id="timer"></div></b>
              </h4>
            </div>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <h2><i class="fas fa-expand-arrows-alt"></i></h2>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>

      @yield('content')

  <div class="footer">
    <marquee>{{ $outlet->running_text }}</marquee>
  </div>
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('assets/js/demo.js') }}"></script> --}}
<!-- Recta JS for print antrian -->
<script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>

<script type="text/javascript">
  function startTime() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    
    if(dd<10){
      dd = '0'+dd;
    }
    document.getElementById('timer').innerHTML =
    "Tanggal: " + dd + "/" + mm + "/" + yyyy + " | " + h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
  }
  function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
  }
</script>

@yield('javascript')

</body>
</html>