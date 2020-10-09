<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table>
    @if(!empty(session('msg')))
        <div class="alert alert-msg" role="alert">
            {{session('msg')}}
        </div>
    @endif
    <form action="{{url('user/store')}}">
        <tr>
            <td>用户名</td>
            <td>
                <input type="text" name="user_name" id="">
            </td>
        </tr>
        <tr>
            <td>邮箱</td>
            <td>
                <input type="text" name="user_email" id="">
            </td>
        </tr>
        <tr>
            <td>手机号</td>
            <td>
                <input type="text" name="user_tel" id="">
            </td>
        </tr>
        <tr>
            <td>密码</td>
            <td>
                <input type="password" name="user_pwd" id="">
            </td>
        </tr>
        <tr>
            <td>重复密码</td>
            <td>
                <input type="password" name="user_pwd1" id="">
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="注册"></td>
            <td></td>
        </tr>
    </form>
</table>
</body>
</html>
