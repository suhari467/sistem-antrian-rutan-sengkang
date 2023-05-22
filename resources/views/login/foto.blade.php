@extends('global.layout.waiting')

@section('content')
<div class="container">
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center mt-5">
          @if (session()->has('success'))
          <div class="col-md-12">
              <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
              </div>
          </div>
          @endif
          
          @if (session()->has('error'))
            <div class="col-md-12">
              <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
              </div>
            </div>
          @endif
        <div class="col-md-10">
          <!-- Line chart -->
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="far fa-picture"></i>
                Foto Profile
              </h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row justify-content-center">
                      @if ($user->foto)  
                      <img src="{{ url('storage/foto') }}/{{ $user->foto }}" id="foto-profile" alt="{{ $user->foto }}" width="300">
                      @else
                      <img src="{{ url('assets/img/blank-profile.jpg') }}" id="foto-profile" alt="blank-profile" width="300">
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <form action="{{ url('foto-profile') }}" method="post" enctype="multipart/form-data">
                      @csrf
                      <p>
                        Upload Foto Profile <br>
                        Masukkan foto profile agar bisa dilihat pada layar tampilan antarmuka
                      </p>
                      <input type="file" name="foto" id="foto" class="form-control" required onchange="previewImage()">
                      <button type="submit" class="btn btn-primary mt-3">Upload</button>
                    </form>
                  </div>
                </div>
            </div>
            <!-- /.card-body-->
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
<!-- /.container -->
@endsection
@section('javascript')
<script type="text/javascript">
    function previewImage() {
        const foto = document.querySelector('#foto');
        const fotoProfile = document.querySelector('#foto-profile');

        const oFReader =  new FileReader();
        oFReader.readAsDataURL(foto.files[0]);

        oFReader.onload = function(oFREvent) {
            fotoProfile.src = oFREvent.target.result;
        }
    }
</script>    
@endsection