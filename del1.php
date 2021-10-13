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
$d = $_POST['deldate'];
$delinfo = $_POST['delnum'];

$sql = "SELECT * FROM businfo WHERE Busnum='$delinfo' and Date='$d'";
$result = mysqli_query($conn, $sql);
$data = mysqli_num_rows($result);
if ($data){
    $sql = "DELETE FROM businfo
        WHERE Busnum='$delinfo' and Date='$d';";
    $sql .="DELETE FROM busandpass
        WHERE Busnum='$delinfo' and Date='$d';";
    $sql .="DELETE FROM passengers
        WHERE Idnum not in
        (SELECT Idnum from busandpass)";
}
else {
    echo "<script>alert('查无车次');history.back();location.reload();</script>";
    mysqli_close($conn);
}


if (mysqli_multi_query($conn, $sql)) {
    echo "<script>alert('删除成功，请刷新查看');history.back();location.reload();</script>";
} else {
    echo "<script>alert('删除失败');history.back();location.reload();</script>";
}
mysqli_close($conn);
?>