<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Action;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $lisensi = Action::lisensi();
        $fetch = json_decode(Storage::disk('public')->get('aktivation.json'));

        eval(gzinflate(base64_decode("DZRHrqRYAATv0qvfYgE8vEazgMIX3sNmBIUtvDenn3+CTIUiszjS7qd6mqHs0q34ydK1IPH/8uIz5sXPHz63UWG+Urbi7aN8RrgY2VS7e3XkJcwFydkhOz/v5Ybr3lCqev3AKECooDWpbrif3K00KkLl/oPKEPQpVAtU1leta/NVqaxXEO9Nt4m8GPc0qWlqqQEhNvthbt6uUUVEFa/vMjI8Otq6fquo4FcRd2m2rbRnLLznY6w9ym5AcDKEepsBt8Utl7JRhIQJ7l2iZ9sJSiZHPU01FmTYpDP8S7execpczSjlPcLQDsGX01eVM2gYmLIsXnbgdntAPYaMYZkGD/IaY0xDpflrIA6rSRFUiIVLD+Pi1RVoGXweyMUQ+0kDCCWrzCh7F5voh+Q3yk6qd9BSRY4FjTL47UNMbgGpBkbK7Y1AJk9oiY8Qbuc5AjCthy/XSyyUjud6XFolMqq9mYKet961HBcWZRBtPHwPlmzvaIHzhhJ2MNjyGMeiTQN0vgXjy6Ajn+i++23weBExQ4tMlGodO5A0kgE+XNUZGwO6Vo1rKVQf5TkvySR34mqudTTnaQos/LZl7Vri/pSDPdvVWsNPfdbT44MPkS6eHYIp6NBiSMPv+ikhGUifShSKG3d6z5d65FVWwf7wvn7QtRYGnLXfclHNH4g9J+GV+6bOFLyjgJ3VlNFmhm4anrT0R3wSrhVavuj01jpL3pdJK9OMGM8IG7ih7CEZtUm9OxPJRkNLtEQSG05jJhKlZI94pYPBmk6rBgmhNl1ODOyH+W5WNrjQ9YXsPDry+DRS4Rd6PDnaMSxGtMHr2cU0dx0zcyjma63TIH5EIIVWMrJODHC7M2UhgSpLwMaPuY0PtWBA4VoOjiLaociEjbQSadFbWgAywa2Wu5/NwDJcZUOebdUHAQdnqaVTPdFdpquiLvpKnMzKKb8J6UlSC6CWPKxtKUSzCE+qkb+aq/RSSfFlBiBhX9640OpDbT7Gt588K7fq99RNlRG8qT0vx0AA7/A9jYksLwUO49nV3sDVtEdCjymrDCh90gxxH5qT0LZsvpNoAA/EQbdfG4+5CYMlA73VXnp6zv3BfEQcyNm7vWjfKDa+nDl/RPn7EUveqSgcCYg64Pk3zPjYN7/cPI1YZLqwbQ0WYNYHzyU2p2krQ7S71fgTlG7O4hI57RvhoyF2VmT70msQ86BgFpXe0rjf3qM+E6GG74slhuirLc6J4qGmg597pYTmkPZKFvUg13y0nnRWSBsy92f/GqV3Es7ThMf1pQwH2IvgG2t12Bt4rVG8p1GLs4S6myyPtK9lSsn7EJd6z08kzWcr5laEM+/FKVoQwq+fpJiu5UKSxP3ev+KWG3MTWfg7m8KNGDlLgXnERZtw3J4QMmMpkcirnwFak440F7G2K9LODwu92t3hRq0ikK1ZsgwSylN1IBVd15q1799rkoER65TifBxCo5uXlI77dlbQleps1GZSdcvp9+zjjiz53ra+Lzra4urxVjybT5hX3isYodcqLXQncZP0Yl2DSTBKqZ5wvNGG1b0S6ZuxKU9abae5rtoYw/vNrxpixAgEIZ/qRW6OiCGFkLO5nqi/WLb2uS3rXQHVlEby9gSfCFDbXLKcLemahIU3hJ+zqyn5uulVAJX9R2UdzrOosoQphmFgGObof//8/fv3n/8B")));
        eval(gzinflate(base64_decode("DdNHDqtYAkDRvdTolxjAIz1QqwfkDDYZJi3A5GiSgdX338HVkW55ZsOf+mmnasj28k+ebSVN/u9TFvOn/POPmDqE+L0SrvfRNDMJV+PXSUp826bgRN/1EXdx+epomN9CsAcUaXX0i439TomqvORf0xfnqhd+cjqycCqKFKHlQEiN7ATVgl9Y5CP7qqLHJzssWKmneFy6LQVV7G1a8zMOlxZtvZ3x5iMzpCtxvEA+hcPuNFW0c66o92VMCv9VbiSJujpTiWG2pa494bUK3dUqyjUgd9P50VvCTPoDS19B3NKyQ1fmnrikeavIQ6bslT7sMzzRduIGweaZarh5+9fIRE85foW/75G5jr1w4RZsta/hBDFSi2uX9jJpcr2r4Vnd9MNRu3ljYF4KMzdVJ2cNsjV2pYX3OtdYq85Mtph4Sk6dMqxesAHwG0L1KrNauQCopgmY9WfrEWR0O9tJHW8QpADYxb79azlAwzdpdRgaCczvEEjMipoyMD/SLp3DcAes4B5DLPg3Sezih1NBG2wmOS2KEDoxh9xQCp3ut6yFGdhy0nsaKwUvXfhw692uOg5qXdlQ+xmIs1Uq9gtjI/YJdMw4BHpQtSjjVJs8JnpRfIsCBEEXtnPUs79MP96N1MwT/w2zrTvf0s6cSPYSwW/wc87JjW6pPGTuqWNysBcHDcosEVVJvmsuqvoIzl2c3EFvETbAnPfPiFqUnbKPsFERI8id1wTpQ1O0MqJuilgWxDXEnL1yJj71Bq98TuNsOfMB8hnFfA6o8ah1DJ2zff/KmIUubZZYjeYru2sUUpzpxc+rp221FD+VgNyZDJwI6yyWlDiKZzMvN23kB4CsgyRRt3JArMwhgyigbCw7vKBIzySULRWu9EyYJ/iKmwciBfmmx096bQ+osy9BM/zuK9qyuR7hjq/z+yrHMGaoEipliETnWeTHuYghxQ2gco99b2U/SdP5BmjE+MyWT+2ZR+yEIDAQPda9Cevt3PVmblc8s6eCiFyWnLmdXDAl8bbK5BYTOiCYNIPRuzbRC6XunV3sAGtlKLv1nuodLmkp6u2w5U9hYw2s78jrSIYH6A3HJ0q5s7F5hWSb6HTgfNrocYqtxuniCG+7qGQJHy+2d0tTdENwiAgjsAAN/HPhYzKVby8So+yqw0o6+1tLA4HewiTk2VRl5mvaSsC6lF80uPFxtQQWQpr4xIlSD9KIVlO/J/Vv2rYO35hvkfLRH9ZNVGVtfALRSkj7OkUm4IaVIo1wLDVXpNN6CTpq//BR4Ky1KEbRE3G/Ai29gKF/SD+LXeJk3ZhKb/GIzhLDv5whKVJA9o3wqr8yU4jcbNTbmF55v6yn2IEz/LtCvXHQ4imClUPP8dyJrR9AHsT4dkmNeCVJPWjyAIhIUZWxbPLXUV82TqJnN5pRQlsU5iO3u+FY01JXdDI/RB2YLpqanxfAc2Pa9nlXys1ktGzofdIKpfqqSnmOIj357pWNWB+1pnpYd6MYypcEiTFOgJj8AA6uqBrgtZV3VWSuS5idvew3vSu4L65bwdL+VAbGu0ZMmqYjlfHhNYBx0Z/m8elPEVOR00teaJDOQero/Gk/GzYYz3Opk53b44Wm7UFwv3h43jmkMWq1Re92XLehXwALPVbSgx74h/fOhRbdkvsXCyI982RePROKotWLgCyLoj/mv//8+++///k/")));
        
        if($hasil==null){
            return redirect('/aktivasi')->with('lisensi', $lisensi);
        }

        $cekkode = Action::generate($lisensi);
        $valid = $hasil===$cekkode ? true : false;
        if(!$valid){
            return redirect('aktivasi')->with('error', 'Aktivasi Key tidak valid');
        }

        if(!auth()->check()){
            return redirect('login')->with('error', 'Harap login terlebih dahulu. Terima kasih');
        }

        if(auth()->user()->role->name!='admin'){
            return redirect('rincian-loket')->with('error', 'Akses di tolak. Akun anda tidak diizinkan.');
        }

        return $next($request);
    }
}
