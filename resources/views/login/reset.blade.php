@extends('global.layout.simple')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="{{ url('/') }}" class="h3"><b>Sistem Antrian</b> v1.0 </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Reset your password to login your account</p>

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

      <form action="{{ route('ubah_password') }}" method="post">
        @csrf
        <input type="hidden" id="user_id" name="user_id" value={{ auth()->user()->id }}>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Lama">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Password Baru">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          @error('new_password')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" name="repeat_password" id="repeat_password" class="form-control @error('repeat_password') is-invalid @enderror" placeholder="Ulangi Password Baru">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          @error('repeat_password')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="row">
          <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-3">
        <a href="{{ url('/rincian-loket') }}" class="text-center">Kembali ke menu sebelumnya</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
@endsection