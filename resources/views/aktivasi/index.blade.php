@extends('global.layout.aktivasi')

@section('content')
<div class="card card-outline card-primary mt-5">
  <div class="card-body">
    <p class="login-box-msg">Lisensi :
      @if (session()->has('lisensi'))
        <b>{{ session('lisensi') }}</b>
      @else
        <b>{{ $lisensi }}</b>
      @endif 
    </p>
      @if (session()->has('lisensi'))
        <p>Silahkan untuk melakukan perpanjang lisensi, lalu masukan kode aktivasi anda dibawah.<br>Jika anda membeli software ini silahkan informasikan kode lisensi anda ke Admin, <a href="https://wa.me/6283824441192?text={{ session('lisensi') }}" target="_blank">KLIK DISINI</a>!<br><br></p>
      @else
        <p>Silahkan untuk melakukan perpanjang lisensi, lalu masukan kode aktivasi anda dibawah.<br>Jika anda membeli software ini silahkan informasikan kode lisensi anda ke Admin, <a href="https://wa.me/6283824441192?text={{ $lisensi }}" target="_blank">KLIK DISINI</a>!<br><br></p>
      @endif 

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

    <form action="{{ route('aktivasi') }}" method="post">
      @csrf
      <div class="input-group mb-3">
        <input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Kode Aktivasi" id="kode_aktivasi" name="kode_aktivasi" value="{{ old('kode_aktivasi') }}">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-key"></span>
          </div>
        </div>
        @error('username')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
        @enderror
      </div>
      <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Aktivasi</button>
        </div>
      </div>
    </form>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection