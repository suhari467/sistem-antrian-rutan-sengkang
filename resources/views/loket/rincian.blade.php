@extends('global.layout.waiting')

@section('content')
<div class="container">
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row mt-5">
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
        <div class="col-md-7">
          <!-- Line chart -->
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="far fa-star"></i>
                Antrian Aktif
              </h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                <table id="table-antrian-aktif" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nomor Antrian</th>
                            <th>Nomer Loket</th>
                            <th>Jenis Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body-->
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col -->

        <div class="col-md-5">
          <!-- Bar chart -->
          <div class="card card-primary card-outline" id="setOverlay">
            <div class="card-header">
              <h3 class="card-title">
                <i class="far fa-check-square"></i>
                Action
              </h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                <button class="btn btn-primary " type="button" id="panggil" data-users="{{ auth()->user()->id }}">Panggil Loket {{ auth()->user()->loket->nomor }} (Enter)</button>
                <button class="btn btn-success "type="button" id="panggil-ulang" data-users="{{ auth()->user()->id }}">Ulangi (F9)</button>
            </div>
            <!-- /.card-body-->
          </div>
          <!-- /.card -->

          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="far fa-user"></i>
                Detail Loket
              </h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                  <div class="row justify-content-center">
                    @if ($user->foto)  
                      <img src="{{ url('storage/foto') }}/{{ $user->foto }}" alt="" class="img-thumbnail" width="100">
                    @else
                      <img src="{{ url('assets/img/blank-profile.jpg') }}" alt="" class="img-thumbnail" width="100">
                    @endif
                  </div>

                  <h3 class="profile-username text-center">{{ $user->name }}</h3>
  
                  <p class="text-muted text-center">{{ $user->username }}</p>
  
                  <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                      <b>Nomor Loket</b> <a class="float-right">{{ $user->loket->nomor }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Jenis Loket</b> <a class="float-right">{{ $user->loket->purpose->keterangan }}</a>
                    </li>
                  </ul>
  
                @if($user->role->name=='admin')
                <a href="{{ url('/dashboard') }}" class="btn btn-success btn-block mb-2">Kembali ke Menu</a>
                @endif
                <a href="{{ url('/foto-profile') }}" class="btn btn-secondary btn-block mb-2">Upload Foto Profil</a>
                <a href="{{ url('/ubah-password') }}" class="btn btn-warning btn-block mb-2">Ubah Password</a>
                <form action="{{ route('logout') }}" method="get">
                    @csrf
                    <button class="btn btn-danger btn-block" type="submit" onclick="return confirm('Are you sure?')">Logout</button>
                </form>
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
    $(document).ready(function(){

        tableRefresh();
        var refreshId = setInterval(tableRefresh, 5000);

        $(document).keyup(function(event) {
            if (event.keyCode === 13) {
                $("#panggil").click();
            }
        });

        $(document).keyup(function(event) {
            if (event.keyCode === 120) {
                panggilUlang();
            }
        });
                 
        $('#panggil').on('click', function(){
            var id_user = $('#panggil').data('users');

            $.ajax({
                url: "{{ route('panggil_antrian') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: id_user
                },
                dataType: 'json',
                success: function(data){
                    var nomor_loket = data['nomor_loket'];
                    var antrian_sebelumnya = data['antrian_sebelumnya'];
                    var antrian_panggil = data['antrian_panggil'];

                    panggilUlang();
                    tableRefresh();
                }
            });
        });

        $('#panggil-ulang').on('click', function(){
            panggilUlang();
        });

        function panggilUlang(){
            $('#setOverlay').append('<div class="overlay" id="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i></div>');
            var id_user = $('#panggil-ulang').data('users');

            $.ajax({
                url: "{{ url('ambil-detail-antrian') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: id_user
                },
                dataType: 'json',
                success: function(data){
                    var nomor_loket = data['nomor_loket'];
                    var nomor_antrian = data['nomor_antrian'];

                    actionPanggil(nomor_antrian);

                    $("#overlay").remove();
                }
            });
        }

        function tableRefresh(){
            $('#table-antrian-aktif').DataTable({
                serverSide: false,
                ajax: {
                    url: "{{ url('tabel-antrian-aktif') }}",
                    type: 'GET',
                    DataType: 'JSON'
                },
                order: [[1,'asc']],
                // scrollX: true,
                processing: true,
                columns: [
                    {data: 'nomor_antrian'},
                    {data: 'nomor_loket'},
                    {data: 'keterangan'}
                ],
                "responsive" : true,
                "bDestroy": true
            });
        }

        function actionPanggil(nomor_antrian){
          $.ajax({
                url: "{{ route('action.store') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    nomor_antrian: nomor_antrian
                },
                dataType: 'json',
                success: function(data){
                  var status_code = data['status_code'];
                }
            });
        }
    });
</script>
@endsection