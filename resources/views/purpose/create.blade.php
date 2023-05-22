@extends('global.layout.main')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Tujuan Loket</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ url('setting/purpose') }}">Tujuan</a></li>
              <li class="breadcrumb-item active">Tambah</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Tambah Tujuan Loket</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" action="{{ route('purpose.store') }}" method="post">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="kode">Kode Antrian</label>
                    <select name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror">
                    @foreach($kode_antrian as $kode)
                      <option value="{{ $kode }}" {{ old('kode') == $kode ? 'selected' : '' }} class="form-control">{{ $kode }}</option>
                    @endforeach
                    </select>
                    @error('kode')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="jenis">Jenis</label>
                    <input type="text" name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" placeholder="Jenis Antrian" value="{{ old('jenis') }}">
                    @error('jenis')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan Antrian" value="{{ old('keterangan') }}">
                    @error('keterangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection