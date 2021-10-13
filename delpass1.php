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
$d = $_POST['deldate'];
$del1 = $_POST['del1'];
$del2 = $_POST['del2'];
$del3 = $_POST['del3'];
if(strlen($del2)!=18){
    echo "<script>document.getElementById('title').style.display = 'none';</script>";
    mysqli_close($conn);
    echo "<script>alert('输入格式错误');history.back();</script>";
}
$sql = "SELECT * FROM businfo WHERE Busnum='$del3' and Date='$d'";
$result = mysqli_query($conn, $sql);
$data = mysqli_num_rows($result);
if ($data){
    $sql = "DELETE FROM busandpass
        WHERE Idnum='$del2' and Busnum='$del3' and Date='$d';";
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