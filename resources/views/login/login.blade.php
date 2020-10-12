<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        a{
            font-size:14px;
        }
    </style>
</head>
<body>
<table>
    @if(!empty(session('msg')))
        <div class="alert alert-msg" role="alert">
            {{session('msg')}}
        </div>
    @endif
    <form action="{{url('/login/logindo')}}">
        <tr>
            <td>账号</td>
            <td>
                <input type="text" name="user_name" id="" placeholder="账号/邮箱/手机号">
            </td>
        </tr>
        <tr>
            <td>密码</td>
            <td>
                <input type="password" name="user_pwd" id="" placeholder="密码">
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="登陆"></td>
            <td><a href="{{'/user/register'}}">还没有？立即注册</a></td>
        </tr>
    </form>
</table>
</body>
</html>
