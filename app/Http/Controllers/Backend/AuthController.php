<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository=$userRepository;
    }

    public function index(){
        return view('Backend.auth.login');
    }

    public function login(AuthRequest $request){
        $credentials=['email'=>$request->input('email'), 'password'=>$request->input('password')];
        $condition=[
            ['email','=', $credentials['email']]
        ];
        $user=$this->userRepository->findByCondition($condition);
        if(Auth::attempt($credentials)){
            return redirect()->route('dashboard.index')->with('success','Đăng nhập thành công');
        }
        else{
            return redirect()->route('auth.admin')->with('error','Email hoặc mật khẩu không chính xác');
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin');
    }
}
