@extends('global.layout.main')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Antarmuka</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ url('setting/display') }}">Antarmuka</a></li>
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
                <h3 class="card-title">Edit Antarmuka</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" action="{{ route('display.store').'/'.$display->id }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Nama Video</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Video" value="{{ old('name', $display->name) }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <select name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror">
                        <option value="local" {{ old('keterangan', $display->keterangan)=='local' ? 'selected' : '' }} class="form-control">Local (Upload)</option>
                        <option value="youtube" {{ old('keterangan', $display->keterangan)=='youtube' ? 'selected' : '' }} class="form-control">Youtube</option>
                    </select>
                    @error('keterangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-group" id="form-youtube">
                    <label for="source_youtube">Alamat URL</label>
                    <input type="text" name="source_youtube" id="source_youtube" class="form-control @error('source_youtube') is-invalid @enderror" placeholder="Alamat URL" value="https://www.youtube.com/watch?v={{ old('source_youtube', $display->keterangan=='youtube'?$display->source:'') }}">
                    @error('source_youtube')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-group" id="form-local">
                    <label for="source_local">Upload Media</label>
                    <input type="file" name="source_local" id="source_local" class="form-control @error('source_local') is-invalid @enderror" placeholder="Sumber Local" value="{{ old('source_youtube', $display->keterangan=='local'?$display->source:'') }}">
                    @error('source_local')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-warning">Edit</button>
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

    $('#form-youtube').hide();
    $('#form-local').hide();
    formData();
    
    $('#keterangan').change(function(){
      formData();
    })

    function formData(){
      if($('#keterangan').val()=='youtube'){
        $('#form-youtube').show();
        $('#form-local').hide();
      }else{
        $('#form-local').show();
        $('#form-youtube').hide();
      }
    }
</script>
@endsection