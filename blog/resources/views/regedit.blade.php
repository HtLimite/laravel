
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户注册</title>
    <style>
body{
    text-align: center;
            height: 100%;
            margin: 17% 0;
            background: url({{URL::asset('css/instagram/bag14.jpg')}});
            background-attachment: fixed;
            background-size: 100%;
        }
        h3{
    margin: 70px 0;
            margin-bottom: 33px;
            font-size: 23px;
            color: rgb(194 200 241);
        }
        input{
    width: 300px;
            height: 30px;
        }
        .but{
    height: 32.5px;
            width: 97px;
            background-color: #c1d3dbb0;
            border-style: none;
            color: #2a4368;
        }
        .but:hover{
    background-color: #7795d7ab;
        }
        .buttom{
    margin-top: 28px;
        }
        .inp{
    margin-left: 33px;
        }
        p{
    color: white;
}
    </style>
</head>
<body>
    <form action="{{url('checkreg')}}" method="post" name="regform" onsubmit="return check();" >
        @csrf
        <h3>注册账号</h3>
        <p>账号：<input class="inp" type="text" name="user_num" placeholder="请输入账号" required="" lay-verify="required" ></p>
        <p>密码：<input class="inp" type="password" name="password" placeholder="请输入密码" required="" lay-verify="required" ></p>
        <p>确认密码：<input type="password" name="password_two" placeholder="请确认密码" required="" lay-verify="required" ></p>
        <!-- <p>验证码：<input type="text" placeholder="请输入验证码" required="" lay-verify="required" ></p> -->
        <button class="but" type="submit">立即注册</button>
    </form>
    <script>
        function check(){
            let pwd = regform.password.value;
        // console.log(pwd);
        if(regform.password.value!=regform.password_two.value){
            alert("两次输密码不一致，请重新输入！");
            regform.password.focus();
            return false;
        }
        return true;
    }
    </script>
</body>
</html>
