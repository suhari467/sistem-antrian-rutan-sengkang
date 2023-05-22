@extends('global.layout.main')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Home</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <h5 class="mb-2">Info Sistem Antrian</h5>
      @if (session()->has('success'))
      <div class="row">
        <div class="col-lg-12">
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            </div>
        </div>
      </div>
        @endif

        @if (session()->has('error'))
        <div class="row">
          <div class="col-lg-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              </div>
          </div>
        </div>
      @endif
        <div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="far fa-bookmark"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Antrian</span>
                <span class="info-box-number">
                  <h4>
                    <b>{{ $queue_count }}</b>
                  </h4>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-danger"><i class="fa fa-th"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Loket</span>
                <span class="info-box-number">
                  <h4>
                    <b>{{ $loket_count }}</b>
                  </h4>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="far fa-flag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Tujuan Antrian</span>
                <span class="info-box-number">
                  <h4>
                    <b>{{ $purpose_count }}</b>
                  </h4>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="far fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Pengguna</span>
                <span class="info-box-number">
                  <h4>
                    <b>{{ $user_count }}</b>
                  </h4>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
      <!-- /.row -->
      
      <!-- =================================================================================== -->

      <h5 class="mt-4 mb-2">Info Antrian</h5>
        <div class="row">
          @foreach($queue_first as $info_first)
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-gradient-info">
              <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{ $info_first ? $info_first->keterangan : '-' }}</span>
                <span class="info-box-number">
                  <h4>
                    <b>{{ $info_first ? $info_first->nomor_antrian : '0' }}</b>
                  </h4>
                </span>

                <div class="progress">
                  <div class="progress-bar" style="width: 10%"></div>
                </div>
                <span class="progress-description">
                  Awal dari {{ $info_first ? $info_first->keterangan : '-' }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          @endforeach

          @foreach($queue_last as $info_last)
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-gradient-success">
              <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{ $info_last ? $info_last->keterangan : '-' }}</span>
                <span class="info-box-number">
                  <h4>
                    <b>{{ $info_last ? $info_last->nomor_antrian : '0'}}</b>
                  </h4>
                </span>

                <div class="progress">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                  Terakhir dari {{ $info_last ? $info_last->keterangan : '-'}}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          @endforeach
        </div>
        <!-- /.row -->
        
        <!-- =============================================================================== -->

        <!-- Small Box (Stat card) -->
        <h5 class="mb-2 mt-4">Tools Antrian</h5>
        <div class="row">
          <div class="col-lg-3">
            <!-- small card -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ $queue_count }}</h3>

                <p>Reset Antrian</p>
              </div>
              <div class="icon">
                <i class="fas fa-bookmark"></i>
              </div>
              <form action="{{ route('dashboard.reset') }}" method="post">
                @csrf
                <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
                <button type="submit" class="btn btn-block btn-danger btn-sm" onclick="return confirm('Are you sure?')">Reset <i class="fas fa-arrow-circle-right"></i></button>
              </form>
            </div>
          </div>
          <div class="col-lg-3">
            <!-- small card -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $queue_count }}</h3>
                <p>Antarmuka Antrian</p>
              </div>
              <div class="icon">
                <i class="fas fa-bookmark"></i>
              </div>
              <a href="{{ url('/antrian') }}" target="_blank" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <div class="col-lg-3">
            <!-- small card -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $queue_count }}</h3>
                <p>Antarmuka Display</p>
              </div>
              <div class="icon">
                <i class="fas fa-bookmark"></i>
              </div>
              <a href="{{ url('/display-antrian') }}" target="_blank" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('javascript')
<script>
    $(function () {
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
</script>
@endsection