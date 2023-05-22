@extends('global.layout.main')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Loket pada Tujuan Loket</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('setting/purpose') }}">Tujuan</a></li>
                <li class="breadcrumb-item active">Loket</li>
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
            
              {{-- <h3 class="card-title">Data Loket untuk antrian</h3> --}}
              {{-- <br> --}}
              @if (session()->has('success'))
              <div class="card-header">
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  </div>
              </div>
              @endif

              @if (session()->has('error'))
              <div class="card-header">
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
                  </div>
              </div>
              @endif
            
            
            <!-- /.card-header -->
            <div class="card-body">
              <table id="table-loket" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Loket</th>
                  <th>Nomer Loket</th>
                  <th>Kode Antrian</th>
                  <th>Jenis Antrian</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data_loket as $loket)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $loket->name }}</td>
                  <td>{{ $loket->nomor }}</td>
                  <td>{{ $loket->purpose->kode }}</td>
                  <td>{{ $loket->purpose->keterangan }}</td>
                  <td>
                    <a href="/setting/loket/{{ $loket->id }}" class="btn btn-sm btn-success"><span class="fas fa-user"></span></a>
                    <a href="/setting/loket/{{ $loket->id }}/edit" class="btn btn-sm btn-warning"><span class="fas fa-edit"></span></a>
                    <form action="/setting/loket/{{ $loket->id }}" method="post" class="d-inline">
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
      $('#table-loket').DataTable({
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