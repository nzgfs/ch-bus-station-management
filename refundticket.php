<html>

<head>
    <title>
        售票员界面
    </title>
    <link rel="stylesheet" type="text/css" href="sale.css">
</head>

<body>
    <div id='nav'>
        <div id='userinfo'>
            <div id='head'>
                <img src="pic/head.png" width="80px" />
            </div>
            <div id='info'>
                <div id='name'> </div>
                <div id='identity'></div>
            </div>
        </div>
        <div class='link'>
            <span id='sale'><a href=sale.php>售票</a><br></span>
        </div>
        <div id='choose'>
            <span id='chosen'><a href=sale2.php>退票</a><br></span>
        </div>
        <div class='link'>
            <span id='about'><a href=login.html>退出</a></span>
        </div>
        <!--<div class='link'>
            <span id='about'>关于</span>
        </div>-->
    </div>
    <div id='salepage'>
        <h1 style="color: rgb(29, 29, 29); margin-bottom: 50px;" id="title">退票成功</h1>
        <br>
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
        $d = $_POST['date'];
        $addinfo1 = $_POST['name'];
        $addinfo2 = $_POST['idnum'];
        $addinfo3 = $_POST['phone'];
        $addinfo4 = $_POST['busnum'];
        date_default_timezone_set('Asia/Shanghai');
        $date = date("Y-m-d");
        $hi = date('H:i');
        $today = date("Y-m-d");
        $tomorrow = date("Y-m-d", strtotime("+1 day"));
        $aftertomorrow = date("Y-m-d", strtotime("+2 days"));
        if (strlen($addinfo3) != 11 || strlen($addinfo2) != 18) {
            echo "<script>document.getElementById('title').style.display = 'none';</script>";
            mysqli_close($conn);
            echo "<script>alert('输入格式错误');history.back();</script>";
        }

        if ($d == $today) {
            $sql = "SELECT * FROM busandpass WHERE Idnum='$addinfo2' and Date='$today' and Busnum in(
                    SELECT Busnum FROM businfo WHERE Busnum='$addinfo4' and Time>'$hi' and Date='$today')";
            $result = mysqli_query($conn, $sql);
            $data = mysqli_num_rows($result);
            if ($data) {
                $sql = "SELECT * FROM passengers
                        WHERE Idnum='$addinfo2' and Name='$addinfo1' and Phone='$addinfo3'";
                $result = mysqli_query($conn, $sql);
                $data = mysqli_num_rows($result);
                if ($data) {
                    $sql = "DELETE FROM busandpass
                            WHERE Idnum='$addinfo2' and Busnum='$addinfo4' and Date='$today';";
                    $sql .= "DELETE FROM passengers
                            WHERE Idnum not in
                            (SELECT Idnum from busandpass)";
                } else {
                    echo "<script>alert('查无乘客');history.back();</script>";
                }
            } else echo "<script>alert('查无车次');history.back();</script>";
        } else {
            $sql = "SELECT * FROM busandpass WHERE Idnum='$addinfo2' and Date='$d' and Busnum in(
                SELECT Busnum FROM businfo WHERE Busnum='$addinfo4' and Date='$d')";
            $result = mysqli_query($conn, $sql);
            $data = mysqli_num_rows($result);
            if ($data) {
                $sql = "SELECT * FROM passengers
                    WHERE Idnum='$addinfo2' and Name='$addinfo1' and Phone='$addinfo3'";
                $result = mysqli_query($conn, $sql);
                $data = mysqli_num_rows($result);
                if ($data) {
                    $sql = "DELETE FROM busandpass
                        WHERE Idnum='$addinfo2' and Busnum='$addinfo4' and Date='$d';";
                    $sql .= "DELETE FROM passengers
                        WHERE Idnum not in
                        (SELECT Idnum from busandpass)";
                } else {
                    echo "<script>alert('查无乘客');history.back();</script>";
                }
            } else echo "<script>alert('查无车次');history.back();</script>";
        }

        if (mysqli_multi_query($conn, $sql)) {
            echo "<div id='ticket'>
            <div id='name'>
                姓名：$addinfo1
            </div>
            <div id='id'>
                身份证号：$addinfo2
            </div>
            <div>
                手机号：$addinfo3
            </div>
            <div id='bus'>
                车次：$addinfo4
            </div>
            <div id='da'>
                日期：$d
            </div>
        </div>";
        } else {
            echo "<script>document.getElementById('title').innerHTML='退票失败';</script>";
        }
        mysqli_close($conn);
        ?>
        <br><br><br>
        <button id='back' onclick="javascrtpt:window.location.href='sale2.php'">返回</button>
    </div>
</body>

</html>