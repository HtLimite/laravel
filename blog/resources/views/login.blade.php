
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reiki-login</title>
    <link rel="stylesheet" href="{{ URL::asset('css/login.css') }}">
</head>
<body>
    <form action="checklog" method="post">
        @csrf
        <h3>登录账号</h3>
        <p>账号：<input type="text" name="user_num" placeholder="请输入账号" required="" lay-verify="required" ></p>
        <p>密码：<input type="password" name="password" placeholder="请输入密码" required="" lay-verify="required" ></p>
        <!-- <p>验证码：<input type="text" placeholder="请输入验证码" required="" lay-verify="required" ></p> -->
        <div class="buttom">
            <div><button class="but" type="submit">立即登录</button></div>
            <div class="reg"><a href="regdit">没有账号，立即注册</a></div>
        </div>
    </form>
</body>
</html>

