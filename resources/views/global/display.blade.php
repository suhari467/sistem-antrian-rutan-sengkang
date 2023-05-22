@extends('global.layout.waiting')
@push('style')
<style>
    .alert-warning {
        color: #fff;
        background-color: #232359;
        border-color: #232359;
    }
</style>
@endpush
@section('content')
<section class="content">
    <div class="add-audio" style="display: none">
        <audio id="bell-announcement">
            <source src="{{ url("assets/sound/bell-announcement.mp3") }}" type="audio/ogg">
        </audio>
        <audio id="bell-clossing">
            <source src="{{ url("assets/sound/bell-clossing.mp3") }}" type="audio/ogg">
        </audio>
    </div>
    <div class="row mt-3 mr-1 ml-1">
        <div class="col-md-6 text-center">
            <div class="row">
                <div class="col-md-7">
                    <div class="alert alert-block alert-warning" style="height:100%;">
                        <br>
                        <br>
                        <h2> Nomor Antrian</h2>
                        <hr>
                        <h1 class="display-1 font-weight-bold" id="nomor_antrian">- </h1>
                        <hr>
                            <h3 id="keterangan" style="display:inline;">- </h3>
                            <h3 style="display:inline;" class="font-weight-bold"><i class="icon fas fa-arrow-circle-right"> </i> Loket </h3>
                            <h3 id="nomor_loket" style="display:inline;" class="font-weight-bold">-</h3>
                    </div>
                </div>
                <div class="col-md-5">
                    <img src="{{ asset('assets/img/blank-profile.jpg') }}" id="foto_user" alt="foto_user" width="100%" height="375">
                </div>
            </div>
        </div>
        <!-- /.col -->

        <div class="col-md-6">
        <div class="card card-default">
            <div class="card-body">
                @if($display->keterangan=='youtube')
                <iframe width="100%" height="340" src="https://www.youtube.com/embed/{{ $display->source }}?playlist={{ $display->source }}&autoplay=1&loop=1&showinfo=0&mute=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @else
                <video width="100%" height="340" controls autoplay loop muted >
                    <source src="{{ asset('storage/display/'.$display->source) }}" type="video/mp4">
                    Browser tidak mendukung
                </video>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row mt-1 mr-1 ml-1">
        <div class="col-md-3 text-center">
            <div class="alert alert-warning">
                <h3> Nomor Antrian</h3>
                <hr>
                <h1 class="display-4 font-weight-bold" id="nomor_antrian_0">- </h1>
                <hr>
                <h5 id="keterangan_0" style="display:inline;">- </h5>
                <h5 style="display:inline;" class="font-weight-bold"><i class="icon fas fa-arrow-circle-right"> </i> Loket </h5>
                <h5 id="nomor_loket_0" style="display:inline;" class="font-weight-bold">-</h5>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-3 text-center">
            <div class="alert alert-warning">
                <h3> Nomor Antrian</h3>
                <hr>
                <h1 class="display-4 font-weight-bold" id="nomor_antrian_1">- </h1>
                <hr>
                <h5 id="keterangan_1" style="display:inline;">- </h5>
                <h5 style="display:inline;" class="font-weight-bold"><i class="icon fas fa-arrow-circle-right"> </i> Loket </h5>
                <h5 id="nomor_loket_1" style="display:inline;" class="font-weight-bold">-</h5>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-3 text-center">
            <div class="alert alert-warning">
                <h3> Nomor Antrian</h3>
                <hr>
                <h1 class="display-4 font-weight-bold" id="nomor_antrian_2">- </h1>
                <hr>
                <h5 id="keterangan_2" style="display:inline;">- </h5>
                <h5 style="display:inline;" class="font-weight-bold"><i class="icon fas fa-arrow-circle-right"> </i> Loket </h5>
                <h5 id="nomor_loket_2" style="display:inline;" class="font-weight-bold">-</h5>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-3 text-center">
            <div class="alert alert-warning">
                <h3> Nomor Antrian</h3>
                <hr>
                <h1 class="display-4 font-weight-bold" id="nomor_antrian_3">- </h1>
                <hr>
                <h5 id="keterangan_3" style="display:inline;">- </h5>
                <h5 style="display:inline;" class="font-weight-bold"><i class="icon fas fa-arrow-circle-right"> </i> Loket </h5>
                <h5 id="nomor_loket_3" style="display:inline;" class="font-weight-bold">-</h5>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@endsection
@section('javascript')
<script src="https://code.responsivevoice.org/responsivevoice.js?key=6zFzZrfQ"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var awal_bell = document.getElementById("bell-announcement");
        var akhir_bell = document.getElementById("bell-clossing");

        const timer = ms => new Promise(res => setTimeout(res, ms))

        fetchData();
        panggilAntrian();
        var refreshId = setInterval(panggilAntrian, 15000);

        function fetchData(){
          $.ajax({
              url: "{{ url('tabel-antrian-aktif') }}",
              method: 'get',
              dataType: 'json',
              success: function(data){
                var antrian = data.data;

                setData(antrian);
                setDataWait(antrian);
              }
          });
        }

        function fetchFoto(data){
          $.ajax({
              url: "{{ url('foto-user-aktif') }}/?loket="+data.nomor_loket,
              method: 'get',
              dataType: 'json',
              success: function(data){
                var loket = data.data;

                loket.users.forEach(user => {
                    if(user.foto == null){
                        $('#foto_user').attr('src','{{ url("assets/img/blank-profile.jpg") }}');
                    }else{
                        $('#foto_user').attr('src','{{ url("storage/foto") }}/'+user.foto);
                    }
                });
              }
          });
        }

        async function setData(antrian){
            var i=0;
            while (i < antrian.length) {
                var data = antrian[i];

                $('#nomor_antrian').text(data.nomor_antrian);
                $('#keterangan').text(data.keterangan);
                $('#nomor_loket').text(data.nomor_loket);

                fetchFoto(data);

                await timer(3500);
                i++;
            }

            fetchData();
        }

        var a=0
        async function setDataWait(antrian){
            var i=0;
            while (i < antrian.length) {
                var data = antrian[i];

                $('#nomor_antrian_'+a).text(data.nomor_antrian);
                $('#keterangan_'+a).text(data.keterangan);
                $('#nomor_loket_'+a).text(data.nomor_loket);

                await timer(2500);
                i++;
                if(a<3){
                    a++;
                }else{
                    a=0;
                }
            }
        }

        function panggilAntrian(){
            $.ajax({
                url: "{{ route('action.get') }}",
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    if(data['status_code']!=200){
                        var nomor_loket = data['nomor_loket'];
                        var nomor_antrian = data['nomor_antrian'];
    
                        if (nomor_antrian){
                            var tipe = nomor_antrian.substring(0, 1);
                            var nomor = nomor_antrian.substring(1, 4);
                        }
    
                        if (tipe && nomor && nomor_loket){
							awal_bell.play();

                            window.setTimeout(function() {	
                                responsiveVoice.speak(
                                    "Antrian "+tipe+", "+nomor+", Menuju, Loket, "+nomor_loket,
                                    "Indonesian Female",
                                    {
                                        pitch: 1, 
                                        rate: 0.85, 
                                        volume: 1
                                    }
                                );
                            }, 3000);

                            window.setTimeout(function() {	
                                akhir_bell.play();
                            }, 11500);
                            
                            
                        }
                        deleteHistoryPanggil(nomor_antrian);
                    }
                }
            });
        }

        function deleteHistoryPanggil(nomor_antrian){
            $.ajax({
                url: "{{ route('action.destroy') }}",
                method: 'GET',
                data: {
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