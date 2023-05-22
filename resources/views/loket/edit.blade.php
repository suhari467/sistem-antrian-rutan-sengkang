@extends('global.layout.main')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Loket</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ url('setting/loket') }}">Loket</a></li>
              <li class="breadcrumb-item active">Edit</li>
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
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Edit Loket</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" action="{{ route('loket.store').'/'.$loket->id }}" method="post">
                @method('put')
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Nama Loket</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Loket" value="{{ old('name', $loket->name) }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="nomor">Nomor Loket</label>
                    <input type="number" name="nomor" id="nomor" min="1" max="10" class="form-control @error('nomor') is-invalid @enderror" placeholder="Nomor Loket" value="{{ old('nomor', $loket->nomor) }}" readonly>
                    @error('nomor')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="purpose_id">Jenis Antrian</label>
                    <select name="purpose_id" id="purpose_id" class="form-control @error('purpose_id') is-invalid @enderror">
                      @foreach($purposes as $purpose)
                        <option value="{{ $purpose->id }}" {{ old('purpose_id', $loket->purpose_id) == $purpose->id ? 'selected' : '' }} class="form-control">{{ $purpose->keterangan }} (Kode: {{ $purpose->kode }})</option>
                      @endforeach
                    </select>
                    @error('purpose_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-warning">Edit Data</button>
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