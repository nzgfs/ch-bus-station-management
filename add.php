<?php
$servername = "localhost";
$username = "root";
$password = "mariadb";
$dbname = "ticket";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}
mysqli_query($conn, "set names utf8");
date_default_timezone_set('Asia/Shanghai');
$today = date('Y-m-d');
$now = date('H:i');
$d = $_POST['adddate'];
$addinfo1 = $_POST['add1'];
$addinfo2 = $_POST['add2'];
$addinfo3 = $_POST['add3'];
$addinfo4 = $_POST['add4'];
$addinfo5 = $_POST['add5'];
$addinfo6 = $_POST['add6'];

if($d == $today && $addinfo3 < $now)
{
    echo "<script>alert('添加失败');history.back();location.reload();</script>";
    mysqli_close($conn);
} else{
    $sql = "INSERT INTO businfo
        VALUES ('$addinfo1', '$addinfo2', '$addinfo3', '$addinfo4', $addinfo5, $addinfo6, '$d')";
}



if (mysqli_query($conn, $sql)) {
    echo "<script>alert('添加成功，请刷新查看');history.back();location.reload();</script>";
} else {
    echo "<script>alert('添加失败');history.back();location.reload();</script>";
}
mysqli_close($conn);
?>