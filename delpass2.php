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

$del1 = $_POST['del1'];
$del2 = $_POST['del2'];

$sql = "SELECT * FROM passengers WHERE Name='$del1' and Idnum='$del2'";
$result = mysqli_query($conn, $sql);
$data = mysqli_num_rows($result);
if ($data){
    $sql = "DELETE FROM passengers
            WHERE Name='$del1' and Idnum='$del2';";
    $sql .= "DELETE FROM busandpass
             WHERE Idnum='$del2'";
} else {
    echo "<script>alert('查无乘客');history.back();location.reload();</script>";
    mysqli_close($conn);
}

if (mysqli_multi_query($conn, $sql)) {
    echo "<script>alert('删除成功，请刷新查看');history.back();location.reload();</script>";
} else {
    echo "<script>alert('删除失败');history.back();location.reload();</script>";
}
mysqli_close($conn);
?>