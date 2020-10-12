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
        //进行非空验证
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
        //            $pwd= password_hash($data['user_pwd'], PASSWORD_DEFAULT);
        //手机号或者邮箱或者用户名登陆(一条中的所有数据)
        $res = UserModel::where(['user_name' => $data['user_name']])
            ->orwhere(['user_tel' => $data['user_name']])
            ->orwhere(['user_email' => $data['user_name']])
            ->first();
        if(empty($res)){
            return redirect('/login/login')->with('msg','账号不存在');
        }
        //检测用户是否已被锁定
        $key = 'login:count:' . $res['user_id'];
        //剩余时间
        $login_time = ceil(Redis::TTL('login_time:'.$key) / 60);
        if(!empty($login_time)){
            return redirect('login/login')->with(['msg' => '该账户密码输入错误次数过多,已锁定一小时,剩余时间' . $login_time . '分钟']);
        }
        $count = Redis::get($key);
        if ($count>=4) {
            //过期时间
            Redis::setex('login_time:'.$key,3600,Redis::get($key));
            return redirect('/login/login')->with('msg','错误次数过多，已被锁定一小时');
        }
            //判断如果有数据执行其他字段的修改
            if (password_verify($data['user_pwd'], $res['user_pwd'])) {
                // 如果用户登录成功 并且 账号的status(状态)不在锁定状态，也就是说用户的错误次数没有超过一定的限制
                // 下边这个操作是讲该用户的登录的错误次数设置为null(空)
                Redis::setex($key,1,Redis::get($key));
                $loginInfo = ['last_login' => time(), 'last_ip' => $login_ip, 'login_count' => $res['login_count'] + 1];
               session(['user_id'=>$res['user_id'],'user_name'=>$res['user_name'],'user_email'=>$res['user_email'],'user_tel'=>$res['user_tel']]);
                $login = UserModel::where('user_id', $res['user_id'])->update($loginInfo);
                return redirect('/index/index');
                //用户登录成功把用户的信息存入session
            } else {
                /**
                 *  10分钟内，用户连续输入密码错误超过5次，锁定用户 60分钟（禁止登录）。
                 * 提示：
                 * 使用Redis实现计数（incr）
                 * 使用expire实现时间控制
                 */
                //如果错误的次数为空设置十分钟时间内
                if(empty(Redis::get($key))){
                    Redis::setex($key,600,Redis::get($key));
                }
                //设置错误次数($key上面已经定义所以不用定义了)
                Redis::incr($key);
                return redirect('/login/login')->with('msg',"错误次数为：" .Redis::get($key));
            }
        }
    /** 首页 */
    public function index(){
        return view('/user/index');
    }
    //用户中心
    public function center(){
//        echo "hah";
        return view('user/center');
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
