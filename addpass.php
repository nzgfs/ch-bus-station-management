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
if(strlen($addinfo3)!=11||strlen($addinfo2)!=18){
    echo "<script>document.getElementById('title').style.display = 'none';</script>";
    mysqli_close($conn);
    echo "<script>alert('输入格式错误');history.back();</script>";
}
if ($d == $today) {
    $sql = "SELECT c.* from
            (SELECT distinct a.Busnum, Place, Time, Price, (seat-count(idnum)) as Leftseat from
            (select * from businfo where Date='$today')a
            left join
            (select * from busandpass where Date='$today')b
            on a.busnum=b.busnum
            where Time > '$now'
            group by busnum)c
            where Leftseat > 0 and Busnum = '$addinfo4'
            order by time";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_num_rows($result);
    if($data){
        $sql = "SELECT * FROM passengers WHERE Idnum='$addinfo2' and Name='$addinfo1'";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_num_rows($result);
        if ($data) {
            $sql = "INSERT INTO busandpass
              VALUES ('$addinfo4', '$addinfo2', '$d')";
        } else {
            $sql = "INSERT INTO passengers
              VALUES ('$addinfo2', '$addinfo1', '$addinfo3');";
            $sql .= "INSERT INTO busandpass
                VALUES ('$addinfo4', '$addinfo2', '$d')";
        }
    }
    else{
        echo "<script>alert('查无车次');history.back();location.reload();</script>";
        mysqli_close($conn);
    }
    
} else {
    $sql = "SELECT c.* from
            (SELECT distinct a.Busnum, Place, Time, Price, (seat-count(idnum)) as Leftseat from
            (select * from businfo where Date='$d')a
            left join
            (select * from busandpass where Date='$d')b
            on a.busnum=b.busnum
            group by busnum)c
            where Leftseat > 0 and Busnum = '$addinfo4'
            order by time";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_num_rows($result);
    if($data){
        $sql = "SELECT * FROM passengers WHERE Idnum='$addinfo2' and Name='$addinfo1'";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_num_rows($result);
        if ($data) {
            $sql = "INSERT INTO busandpass
              VALUES ('$addinfo4', '$addinfo2', '$d')";
        } else {
            $sql = "INSERT INTO passengers
              VALUES ('$addinfo2', '$addinfo1', '$addinfo3');";
            $sql .= "INSERT INTO busandpass
                VALUES ('$addinfo4', '$addinfo2', '$d')";
        }
    }
    else{
        echo "<script>alert('查无车次');history.back();location.reload();</script>";
        mysqli_close($conn);
    }
}

if (mysqli_multi_query($conn, $sql)) {
    echo "<script>alert('添加成功，请刷新查看');history.back();location.reload();</script>";
} else {
    echo "<script>alert('添加失败');history.back();location.reload();</script>";
}
mysqli_close($conn);
