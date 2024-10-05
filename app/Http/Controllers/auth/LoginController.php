<?php
namespace App\Http\Controllers\Auth;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    
    class LoginController extends Controller{

        public function showLogin(){
            return view("auth/login");
        }

        public function login(Request $request){
            echo $request->get("username");
            if (Auth::attempt(["username"=>$request->get("username"),'password'=>$request->get('password')])) {
                $user = Auth::user();
                return redirect('home/index');
            }else{
                return redirect('/login')->withErrors('auth.fail');
            }
        }

    }
?>