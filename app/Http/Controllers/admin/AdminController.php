<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //后台首页
    public function index(){
//        echo "hah";
        return view('admin/index');
    }
    //退出
    public function exit(Request $request){
        session(['user_id'=>null,'user_name'=>null,'user_tel'=>null,'user_email'=>null]);
        $user_id = $request->session()->get('user_id');
        if(empty($user_id)){
            return redirect('/login/login')->with(['msg'=>'退出成功']);
        }
    }
}
