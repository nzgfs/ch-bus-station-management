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
        <div id='choose'>
            <span id='chosen'><a href=sale.php>售票</a><br></span>
        </div>
        <div class='link'>
            <span id='refund'><a href=sale2.php>退票</a><br></span>
            <span id='about'><a href=login.html>退出</a></span>
            <!--<span id='about'>关于</span>-->
        </div>
    </div>
    <div id='salepage'>
        <h1 style="color: rgb(29, 29, 29); margin-bottom: 50px;" id="title">购票成功</h1>
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
        $hi = date('H:i');
        $today = date('Y-m-d');
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
            where Time > '$hi'
            group by busnum)c
            where Leftseat > 0 and Busnum = '$addinfo4'
            order by time";
            $result = mysqli_query($conn, $sql);
            $data = mysqli_num_rows($result);
            if ($data) {
                $sql = "SELECT * FROM passengers WHERE Idnum='$addinfo2'";
                $result = mysqli_query($conn, $sql);
                $data = mysqli_num_rows($result);
                if ($data) {
                    $sql = "INSERT INTO busandpass
                            VALUES ('$addinfo4', '$addinfo2', '$today')";
                } else {
                    $sql = "INSERT INTO passengers
                            VALUES ('$addinfo2', '$addinfo1', '$addinfo3');";
                    $sql .= "INSERT INTO busandpass
                            VALUES ('$addinfo4', '$addinfo2', '$today')";
                }
            }
            
            else echo "<script>alert('查无车次');history.back();</script>";
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
            if ($data) {
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
            else echo "<script>alert('查无车次');history.back();</script>";
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
            echo "<script>document.getElementById('title').innerHTML='购票失败';</script>";
        }
        mysqli_close($conn);
        ?>
        <br><br><br>
        <button id='back' onclick="javascrtpt:window.location.href='sale.php'">返回</button>
    </div>
</body>

</html>