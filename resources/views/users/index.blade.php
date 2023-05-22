@extends('global.layout.main')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Pengguna</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengguna</li>
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
              <a href="{{ route('user.create') }}" class="btn btn-primary mt-2 mb-2">Tambah Data</a>            
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="table-user" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Lengkap</th>
                  <th>Nomer Loket</th>
                  <th>Kode Antrian</th>
                  <th>Jenis Loket</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $user->name }}</td>
                  <td>Loket {{ $user->loket->nomor }}</td>
                  <td>{{ $user->loket->purpose->kode }}</td>
                  <td>{{ $user->loket->purpose->keterangan }}</td>
                  <td>
                    <a href="/user/{{ $user->username }}/password" class="btn btn-sm btn-success"><span class="fas fa-unlock-alt"></span></a>
                    <a href="/user/{{ $user->username }}/edit" class="btn btn-sm btn-warning"><span class="fas fa-edit"></span></a>
                    <form action="/user/{{ $user->username }}" method="post" class="d-inline">
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
      $('#table-user').DataTable({
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