@extends('global.layout.waiting')

@section('content')
<div class="container">
<!-- Small Box (Stat card) -->
<div class="row mt-2">
  <div class="col-lg-12 mb-4">
  <h3 class="mt-4 text-center">Ambil Antrian Loket</h3>
  <hr>
  </div>
  @foreach($purpose as $use)
    <div class="col-lg-4 col-12">
      <!-- small card -->
      <div class="small-box bg-success" id="{{ $use->jenis }}">
          {{-- <div class="overlay">
            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Loading...</div>
          </div> --}}
            <div class="inner">
                <h3> {{ $use->keterangan }}</h3>

                <p> Antrian untuk ke loket {{ $use->keterangan }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-bookmark"></i>
            </div>
            <a class="small-box-footer"id="antrian-{{ $use->jenis }}" data-id-antrian="{{ $use->id }}">
                Print <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
  @endforeach
  </div>
</div>
<!-- /.container -->
@endsection
@section('javascript')
<script type="text/javascript">
  $(document).ready(function(){
    
    @foreach($purpose as $use)
      $('{{ "#".$use->jenis }}').click(function(){
          var id_antrian = $('#antrian-{{ $use->jenis }}').data('id-antrian');
          
          $('#{{ $use->jenis }}').append('<div class="overlay" id="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i></div>');
          ajax(id_antrian);
      });
    @endforeach
      
      // $('#antrian-service').on('click', function(){
      //     var jenis_antrian = $('#antrian-service').data('jenis-antrian');

      //     ajax(jenis_antrian);
      // });

      function ajax(id_antrian){
          var id_antrian = id_antrian;

          $.ajax({
              url: "{{ route('create-antrian') }}",
              method: 'post',
            //   method: 'get',
              data: {
                  _token: '{{ csrf_token() }}',
                  id_antrian: id_antrian
              },
              dataType: 'json',
              success: function(data){
                  var nama_outlet = data['nama_outlet'];
                  var alamat_outlet = data['alamat_outlet'];
                  var no_telp = data['no_telp'];
                  var nomor_antrian = data['nomor_antrian'];
                  var jenis_antrian = data['jenis_antrian'];
                  var keterangan = data['keterangan'];
                  var count = data['count'];
                  var hari = data['hari'];
                  var tanggal = data['tanggal'];

                  printAntrian(data);
                  $("#overlay").remove();

                  // alert('Berhasil! Nomor antrian '+nomor_antrian);
              }
          });
      }

      var printer = new Recta('{{ $appKey }}', '1811')

      function printAntrian(data){
        printer.open().then(function () {
          printer
            .align('center')
            .mode('A', true, false, false, false)
            .text(data['nama_outlet'])
            .text(data['alamat_outlet'])
            .text('No.Telp : '+data['no_telp'])
            .text('')
            .text('--------------------------------')
            .text('')
            .text(data['hari']+', '+data['tanggal'])
            .text('')
            .text('Nomor Antrian:')
            .text(data['keterangan'])
            .mode('A', true, true, true, false)
            .text(data['nomor_antrian'])
            .mode('A', true, false, false, false)
            .text('')
            .text('--------------------------------')
            .text('')
            .text('Antrian belum dipanggil : '+data['count'])
            .text('')
            .text('Terimakasih atas kunjungannya')
            .text('Semoga lekas sembuh')
            .cut()
            .print()
        })
      }
  });
</script>
@endsection