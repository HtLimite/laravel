<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>regedit</title>
    <style>
        body{
            width: 500px;
            height: 500px;
            margin: 9% auto;
        }
    </style>
</head>
<body>
    <form action="user/reg" method="post">
        @csrf
        <input type="text" name="username">
        <input type="password" name="password">
        <input type="submit" value="注册">
    </form>
</body>
</html>

