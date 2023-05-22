@extends('global.layout.simple')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="{{ url('/') }}" class="h3"><b>Sistem Antrian</b> v1.0 </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Login to start your session</p>

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

      <form action="{{ route('login_auth') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Username" id="username" name="username" value="{{ old('username') }}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('username')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" id="password">
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
        <div class="row">
          <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Login</button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-3">
        <a href="tel:{{ $outlet->no_telp }}" class="text-center">Register a new account ? Contact Us</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
@endsection