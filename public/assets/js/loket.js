        $(document).ready(function(){
            $(document).on('click', '#panggil', function(){
                var id_user = $(this).data('users');

                $.ajax({
                    url: '{{ url("json-antrian") }}',
                    method: 'GET',
                    data: {
                        user_id: id_user
                    },
                    dataType: 'json',
                    success: function(data){
                        var nomor_loket = data['nomor_loket'];
                        var nomor_antrian = data['nomor_antrian'];

                        var tipe = nomor_antrian.substring(0, 1);
                        var nomor = nomor_antrian.substring(1, 4);

                        responsiveVoice.speak(
                            "Antrian "+tipe+", "+nomor+", Menuju, Loket, "+nomor_loket,
                            "Indonesian Female",
                            {
                                pitch: 1, 
                                rate: 0.85, 
                                volume: 1
                            }
                        );
                    }
                });
            });
        });