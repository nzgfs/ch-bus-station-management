<?php

$username = trim($_POST['username']);
$password = trim($_POST['password']);
$identity = trim($_POST['identity']);
if ($username == 'user' && $password == 'password' && $identity == 'sale')
    header('location: sale.php');
if ($username == 'admin' && $password == 'password' && $identity == 'admin')
    header('location: admin.php');
else
    echo "<script>alert('登录信息错误');history.back();</script>";
?>