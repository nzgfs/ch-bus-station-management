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
$editinfo1 = $_POST['edit1'];
$editinfo2 = $_POST['edit2'];
$editinfo3 = $_POST['edit3'];


$sql = "SELECT * FROM passengers Idnum='$editinfo2'";
$result = mysqli_query($conn, $sql);
$data = mysqli_num_rows($result);
if ($data) {
    $sql = "UPDATE passengers
            SET Name='$editinfo1', Phone='$editinfo3'
            WHERE Idnum='$editinfo2'";
} else {
    echo "<script>alert('查无乘客');history.back();location.reload();</script>";
    mysqli_close($conn);
}



if (mysqli_query($conn, $sql)) {
    echo "<script>alert('编辑成功，请刷新查看');history.back();location.reload();</script>";
} else {
    echo "<script>alert('编辑失败');history.back();location.reload();</script>";
}
mysqli_close($conn);
?>