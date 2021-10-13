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
$d = $_POST['editdate'];
$editinfo1 = $_POST['edit1'];
$editinfo2 = $_POST['edit2'];
$editinfo3 = $_POST['edit3'];
$editinfo4 = $_POST['edit4'];
$editinfo5 = $_POST['edit5'];
$editinfo6 = $_POST['edit6'];

if($d == $today && $editinfo3 < $now)
{
    echo "<script>alert('编辑失败');history.back();location.reload();</script>";
    mysqli_close($conn);
} else{
    if($d == $today){
        $sql = "SELECT * FROM businfo WHERE Busnum='$editinfo1' and Date='$d' and Time>'$now'";
    }
    else{
        $sql = "SELECT * FROM businfo WHERE Busnum='$editinfo1' and Date='$d'";
    }
    
    $result = mysqli_query($conn, $sql);
    $data = mysqli_num_rows($result);
    if ($data){
        $sql = "UPDATE businfo
            SET Place='$editinfo2', Time='$editinfo3', License='$editinfo4', Price=$editinfo5, Seat=$editinfo6
            WHERE Busnum='$editinfo1' and Date='$d'";
    }
    else{
        echo "<script>alert('查无车次');history.back();location.reload();</script>";
        mysqli_close($conn);
    }
    
}
    
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('编辑成功，请刷新查看');history.back();location.reload();</script>";
} else {
    echo "<script>alert('编辑失败');history.back();location.reload();</script>";
}
mysqli_close($conn);
?>