@extends('global.layout.main')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Printer Antrian</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Printer</li>
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
              <a href="{{ route('printer.create') }}" class="btn btn-primary mt-2 mb-2">Tambah Data</a>
              <a href="https://drive.google.com/drive/folders/1CWuX_TyEsFc7Q9XFELzcM34YZUiibZ97?usp=share_link" target="_blank" class="btn btn-success mt-2 mb-2">Download Tools</a>
            </div>
            
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tabel-printer" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Printer</th>
                  <th>App Key</th>
                  <th>Keterangan</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($printer as $index => $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->printer }}</td>
                  <td>{{ $item->appKey }}</td>
                  <td>
                    @if ($item->status==1)
                      <span class="badge bg-success">Aktif</span>
                    @else
                      <span class="badge bg-danger">Tidak Aktif</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ url('setting/printer') }}/{{ $item->id }}/edit" class="btn btn-sm btn-warning"><span class="fas fa-edit"></span></a>
                    <form action="{{ url('setting/printer') }}/{{ $item->id }}/status" method="post" class="d-inline">
                      @csrf
                      <button class="btn btn-success btn-sm border-0" onclick="return confirm('Are you sure?')"><span class="fas fa-check"></span></button>
                    </form>
                    <form action="{{ url('setting/printer') }}/{{ $item->id }}" method="post" class="d-inline">
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
      $('#tabel-printer').DataTable({
        order: [[3, 'asc']],
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