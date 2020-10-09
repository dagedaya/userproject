<?php

namespace App\Http\Controllers\index;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;
class RegisterController extends Controller
{

//public function rediss(){
//      $v=Redis::incr('user_id');
//      $v=Redis::set('name','bbb');
//      $v=Redis::get('name','bbb');
//      echo $v;
//}




    /**注册视图页面 */
    public function register(){
        return view('user.register');
    }
    /** 注册添加 */
    public function store(Request $request){
        $data=$request->all();
         if(empty($data['user_name'])){
             return redirect('/user/register')->with('msg','用户名不可为空');
         }
         if(empty($data['user_email'])){
             return redirect('/uesr/register')->with('msg','邮箱不能为空');
         }
         if(empty($data['user_tel'])){
             return redirect('/user/register')->with('msg','手机号不能为空');
         }
         if(empty($data['user_pwd'])){
            return redirect('/user/register')->with('msg','密码不能为空');
         }
        //注册的时间
         $data['reg_time']=time();
         //密码一致
        if($data['user_pwd']!=$data['user_pwd1']){
            return redirect('/user/register')->with('msg','密码不一致');
        }
        unset($data['user_pwd1']);
        $data['user_pwd']=password_hash($data['user_pwd'],PASSWORD_DEFAULT);
        $userInfo=UserModel::insert($data);
        if($userInfo){
            return redirect('/login/login');
        }else{
            return redirect('/user/register')->with('msg','注册失败');
        }
    }
    /** 登陆的视图页面 */
    public function login(){
        return view('/login/login');
    }
    /** 登陆的验证 */
    public function logindo(Request $request)
    {
        //登陆的ip
        $login_ip = $_SERVER['REMOTE_ADDR'];
        $data = $request->all();
        $key = 'login:count:' . $data['user_name'];
//        dd($key);
        //检测用户是否已被锁定
        $count = Redis::get($key);
//        dd($count);
        if ($count >5) {
            echo "已被锁定";exit;
        }
        //            $pwd= password_hash($data['user_pwd'], PASSWORD_DEFAULT);
        //手机号或者邮箱或者用户名登陆(一条中的所有数据)
        $res = UserModel::where(['user_name' => $data['user_name']])
            ->orwhere(['user_tel' => $data['user_name']])
            ->orwhere(['user_email' => $data['user_name']])
            ->first();
        if(empty($res)){
            return redirect('/login/login')->with('msg','账号不存在');
        }
            //判断如果有数据执行其他字段的修改
            if (password_verify($data['user_pwd'], $res['user_pwd'])) {
                $loginInfo = ['last_login' => time(), 'last_ip' => $login_ip, 'login_count' => $res['login_count'] + 1];
                $login = UserModel::where('user_id', $res['user_id'])->update($loginInfo);
                return redirect('/index/index');
            } else {
                /**
                 *  10分钟内，用户连续输入密码错误超过5次，锁定用户 60分钟（禁止登录）。
                 * 提示：
                 * 使用Redis实现计数（incr）
                 * 使用expire实现时间控制
                 */
                //密码不正确 纪录错误错误
                $key = 'login:count:' . $data['user_name'];
                $count = Redis::incr($key);
                return redirect('/login/login')->with('msg',"错误次数为：" . "$count");
            }
        }
    /** 首页 */
    public function index(){
        echo "你好 laravel";
    }
}
