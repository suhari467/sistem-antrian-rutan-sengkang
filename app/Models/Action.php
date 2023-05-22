<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Action extends Model
{
    use HasFactory;

    protected $guarded = [ 'id' ];

    public function purpose()
    {
        return $this->belongsTo(Purpose::class);
    }

    public static function lisensi()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            eval(gzinflate(base64_decode("DdQ3EqxWAkDRvSiSigDvSqUA7xpvm2QKDw/beFj9/CXcE9zqzIa/m7eb6iHbq7/zbKso4n9lVcxl9fdfYmri0s/6clxybrtsShUno1JK0C+uy9sn/9wixBon0mYQ/XvMGn49x2JLcA4RjJ8jXrUqPEQh/xyr46D0ODOGQilPcMk/ovoUWUpcMaOxT+xt90iwrPqC44wLcoJysSqpy8Issy1n47Vb22lbxMkQ3StM2rFNRJQL3k5mtM8aCXOuTJuntvJDR1Q3FUgy+hEvreqSyL+dFW4jTRKoq/eeNFsjh6QLwIka9H06IPES8YIl33jNv/Q6YtWF9Eng8IOzUuD0bAQt2QueHmVPogour1r1EYM49ubwJxNNvmATJgpbr8VmegPTReI2LXHDMhQ2ERq/9hfJ6xWEBmR7K1VoBlNHXvzbIAGuIcP/RIXeYrZFOl6V18X6W/LLR7Ets43KTdbDltD18g3fMYx8V6tSYZU1P4XInBI/xadi86DpG3/AZBWonwxW6FVsFvbHDfHYlOXmnj7bgeaEE2etWhxqnbRCnmAIOoSsep+VpeURbAJH0Nv5CIMvH7tuJ4pnFPAjq8lZWxM8sPXVrXmsfW3Bxn7Dd/vN3lrZhcuYyrwOqnuGGquYc9XJj4IiMK8YsukkC9gdRiqrF1a5ztkhHA64h0VB+ubtp/To+npOcwG/XpmsGgha7d7z8umP4mDvPe32NRiBpZrRV5oIb8uoo676ZH4Y1l06pICFoqFL8Qb2VYbjnaQFPhjzqbJMwQ57MHW3N5u0NZKfiCyNoAxNZo1avWw3wzP4jnbh6RLyVlbkZkTeYWrzy6jnJtbCnkREgEHuiO9v0veV+W7xtXErTRRgzMdo3p6Sq/4kcoLykvnPVNfp2ka8OTqK2cc3c7BeWIuUnL7Z0rntbyvcoOEpUqDV/WbPAYVG5uy/PYHd9SObBKrmGXvhMjWqMGIZopGiVrshuGGvPIv2N3IAHkvJzv5IkyJxfTG6rZNkTDf49O54uZtgQghKvdvmiK5XmdSwR2Wtm5DZeoIUaotUwCYKhC8HBPqUGqINGpIcsE9jq0Q5341A03R+b4yQPyPbechkytDz/LI4vc4kPm5CK23EWI5QTCpfZNQFDLV0TxRSKbLNwkxa3H4Q6ItKRpPd3F2cEnFA5Z7zUT0Mct5E23J7VqsLWBLfRIEhXHnKzJZ/T3MagP4t+KXMrTtOuKNWUk1+K2fZmcw0wlv3dj4Op5zD8+Dsl4j+BiOZlXZ7OC7D/RGl0BiP5B2L75BnVmt750U0MYZ6q4SDUMoLHqOG2mF1MFmrq9HR/UCkoT1wM8h75jEDtYA0bLgk1fJDXZCADDCSKSyy0Hfkcy9Lvhr8e6cKwBNdRzSHNKdyCm9Jrf0rpUakQ1Lrhj6Umjqh83FN35mFek2P1tOQfLZZgONvc2tuOCnWWK5rZwY8jopLwc3CydKvnsoQ2/1C9gJ8I0ONpnmEyFB2oibWV20C5ElDWVypD9CPc5najdQDLgy3JOPwrwrf2Q1dEZv4Uu2NPklykEzsp1VIkcLFp1nr0ITzqPE50SrIhBZtPueqJCl6BC8enmmcU+lQF/2MLtbTKH9ubpZMCHb6UeJfrJRells6/vZ095kKlZZrKQ9zP1MCG/0U76ZubQitjw3LREsbpAUWKN4z90EdP8yXL4Tn7U6UhP3LW72g4lFBFHuQhVTu5JLuQ3/TUp02i6dO18hEvd8meykC8SWnp2UW24V/P6amUY81s9ycyFCv7ZXdfxeWrpK0pXRX+9FFJXfcfS2mj9QFaSI/7hu+3GKR6qVeDQs+uEtOHmq5AVpTXBZWlnTBtpIzX6trPvw73Xyh4pxpqAwD1yeOwzB//fffX//888+//wc=")));
        } else {
            $lisensi = Uuid::uuid3(Uuid::NAMESPACE_URL, url('/'));
        }

        if($lisensi){
            return $lisensi;
        }else{
            return false;
        }
    }

    public static function generate($lisensi)
    {
        eval(gzinflate(base64_decode("DZRHEqPYAgTvMqueYAEIJ2JWeG+F3/wAvYc3wpvT/75CZWbBI+//VE8zln2+wT9FvkKa/B+A3wnAP//IwJel2c+5SuR29IoAI3JmnMvyTiND0pDOFcg0LF01MO59VckfMTC7z/BxSehnJPuXippz3xr0+kaToWjRNJ9GJ+BiSCzyHL6UKarbJChALb54g4X2XO+qPp/kYPUiNYorH3AIMbKZ6ewAOx9zOytV2PSXDmUafclk8TOytPlBWnFNCF/7SlJktg476UHSX463FWoJ2r2CkiHsocXRZPKILM+7HZOHxUKxm1cn6EMskyk0G87GyW6AJf24rJMfQwURqlnwWjYShOeOaqEIM1JR8wEIWujek3rXmaqpQ/uc7M5Q9G94qbjWDVJaLF4/dIPuqNVrR4xtJAmie1NHLYSUH4FqoNvhUVrZ0ihvZtyNcmLhsjU1358jp97fh63SPW7e3ubLr62zanYGo1T3gk64q79sC2s/347caZ7dx/2mXQXnsu9oSJ7wUa4gJmjIocYU+rN4xxGyfTn9YA1QSxaw364OrLzENh6/iUTmGVqZG8/72f6GWiBKO2PSxo7Ac/2csoG/dcMKfqP6XTZrny35+9wfgqEbllmspMuOInrlg/POuoN7dNxfk9qf0l7Uq2XOg6S1EDq95GoBMTIyeBZmh7YiBc2Odq2i6QJqrUIQTKPuQgs+ioduK/kRTQkB2bbl9qpTnJz+QM1iLTX0y3NVziWrV2Y+0ULaBLH6+HRxqPN13giGN0nzTJVLmAjd+W71lnfrxteZwiJ7wEg9bGuGDOnL3l58cMZC3US04+cF5SCzHvH5wlPkOPEdIc8rUfeha2QGT7p3CtO7jDJ7xZ58Y681OnMOFFdexY6geCrb4xMhcEclhZzRTst4t6EwHZcPe1cJoIMNx14ftyWN7BBdJp9vvBY2QWnpzFnKO3cqs6L9hD73cmGp+cE79lNy4ncH/6KCAR133A50gzqySb+R1wVGWhG/vrb+BhWPWikEcUkGxkM+mXUCbyDvTq85zt7xytGaFq4EAkV5YitFSyO0Msff+lERX5ECXyKLnn4MGfLhOnfY2mSY3j7+jL5bdGCzlFgcU59JA8RslDVAtJ6O72IMApSZDHuXj5ukTEpERJlrvpiwcbunW6pp6yKIskVVu6vr7vpEhW6xpFVnbXOm71IAjRWaVXpqc2LZiQzjqbWJkCHK3YheKhdA4v39O1YnJSvdU/l2RQZTPvDyQUJovfe31Kz9KLCsSS/KTLG+yQ3J00/ZmoZUPHkGsNgd0UHBKW4YL6LFU13zXf+L26WWVX8DEiU04xkVG0wNiQPX3ldPq/+6nsc+wifGzHj2rb/pRo10suzkLNQLPzm8z4nssfsJS/P8cKXIf8f1hRF4gkVoUB7a01Lip1jBqnXCoQ6u/VNsN4hmsSN+mt4Si8kdSqK0m+8nY3NMMT8Pxlh+fi2Wo02pzJkFlUY4YY6pUZ2n14nIj9Ed1xktDxUX5X0GYvzpHv05/Rhzz8D87BupJtntWH75+3s3UpGAdK4VJ7zJp+pdDIa3dqmPDYH+vRVL6sVq/tQ69obvPlJoONgR9woS6Bgx8zjBF42AgozCl/IDdlFl6/dDRSaQ+cBUPnP6ftouoCD2wg9XLACDLlTi3tcdXjaOA421D/qBuVW+DxK5fiLixNrP5sCyS++gmitIlsiTDIoC6fIgUBTdAfrPv//++9//AQ==")));

        if($cekkode){
            return $cekkode;
        }else{
            return false;
        }
    }
}
