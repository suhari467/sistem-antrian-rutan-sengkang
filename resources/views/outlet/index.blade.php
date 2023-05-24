@extends('global.layout.main')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Outlet</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Outlet</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              {{-- <h3 class="card-title">Data Loket untuk antrian</h3> --}}
              {{-- <br> --}}
              @if (session()->has('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  </div>
              @endif

              @if (session()->has('error'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
                  </div>
              @endif
              <a href="{{ route('outlet.create') }}" class="btn btn-primary mt-2 mb-2">Tambah Data</a>
            </div>
            
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Outlet</th>
                  <th>Alamat Outlet</th>
                  <th>No.Telepon</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data_outlet as $outlet)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    {{ $outlet->name }}
                    @if($outlet->status)
                    <span class="badge bg-success">{{ $outlet->status }}</span>
                    @endif
                  </td>
                  <td>{{ $outlet->address }}</td>
                  <td>{{ $outlet->no_telp }}</td>
                  <td>
                    <form action="{{ url('outlet') }}/{{ $outlet->id }}/status" method="post" class="d-inline">
                      @method('put')
                      @csrf
                      <button class="btn btn-success btn-sm border-0" onclick="return confirm('Are you sure?')"><span class="fas fa-check-square"></span></button>
                    </form>
                    <a href="{{ url('outlet') }}/{{ $outlet->id }}/edit" class="btn btn-sm btn-warning"><span class="fas fa-edit"></span></a>
                    <form action="{{ url('outlet') }}/{{ $outlet->id }}" method="post" class="d-inline">
                      @method('delete')
                      @csrf
                      <button class="btn btn-danger btn-sm border-0" onclick="return confirm('Are you sure?')"><span class="fas fa-trash"></span></button>
                    </form>
                </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
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