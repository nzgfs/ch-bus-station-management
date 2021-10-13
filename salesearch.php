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
        <h1 style="color: rgb(29, 29, 29); margin-bottom: 50px;">售票</h1>
        <?php
        date_default_timezone_set('Asia/Shanghai');
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime("+1 day"));
        $aftertomorrow = date('Y-m-d', strtotime("+2 days"));
        echo "<div class='filtsearch' id='mixfilter'>
            <form action='salesearch.php' name='salesearch' class='filt' method='post'>
                <input type='date' name='filtdate' class='filtdate' min='$today' value='$today' style='font-family: Microsoft YaHei;'>
                <div><input type='text' name='station' id='station' required='required' placeholder='到站'></div>
                <select name='timeselect3' id='timeselect3' class='timeselect'>
                    <option value='07:00' name='07:00' selected='selected'>7:00</option>
                    <option value='11:00' name='11:00'>11:00</option>
                    <option value='15:00' name='15:00'>15:00</option>
                    <option value='19:00' name='19:00'>19:00</option>
                </select>
                <div style='font-size: 25px;'>&nbsp;~&nbsp;</div>
                <select name='timeselect4' id='timeselect4' class='timeselect'>
                    <option value='11:00' name='11:00'>11:00</option>
                    <option value='15:00' name='15:00'>15:00</option>
                    <option value='19:00' name='19:00'>19:00</option>
                    <option value='23:00' name='23:00' selected='selected'>23:00</option>
                </select>
                <input type='submit' name='done' id='done' value='查询'>
            </form>
        </div>
        <div id='form'>";

        $servername = "localhost";
        $username = "root";
        $password = "mariadb";
        $dbname = "ticket";
        $conn = mysqli_connect($servername, $username, $password);
        if (!$conn) {
            die('Could not connect: ' . mysqli_connect_error());
        }
        mysqli_query($conn, "set names utf8");

        date_default_timezone_set('Asia/Shanghai');
        $hi = date('H:i');
        $d = $_POST['filtdate'];
        $s = $_POST['station'];
        $t1 = $_POST['timeselect3'];
        $t2 = $_POST['timeselect4'];

        if ($d == $today) {
            $sql = "SELECT c.* from
                    (SELECT distinct a.Busnum, Place, Time, Price, (seat-count(idnum)) as Leftseat from
                    (select * from businfo where Date='$today')a
                    left join
                    (select * from busandpass where Date='$today')b
                    on a.busnum=b.busnum
                    where Time > '$t1' and Time < '$t2' and Place='$s'
                    group by busnum)c
                    where c.Leftseat>0 and Time > '$hi'
                    order by time";
        } else{
            $sql = "SELECT c.* from
                    (SELECT distinct a.Busnum, Place, Time, Price, (seat-count(idnum)) as Leftseat from
                    (select * from businfo where Date='$d')a
                    left join
                    (select * from busandpass where Date='$d')b
                    on a.busnum=b.busnum
                    where Time > '$t1' and Time < '$t2' and Place='$s'
                    group by busnum)c
                    where c.Leftseat>0
                    order by time";
        }


        mysqli_select_db($conn, 'ticket');
        $retval = mysqli_query($conn, $sql);
        if (!$retval) {
            die('error: ' . mysqli_error($conn));
        }
        echo '<table class="info">
                <tr>
                    <th style="border-radius: 19px 0 0 0; ">车次</th>
                    <th>到站</th>
                    <th>发车时间</th>
                    <th>票价</th>
                    <th style="border-radius: 0 19px 0 0;">剩余座位</th>
                </tr>';

        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['Busnum'] . "</td>";
            echo "<td>" . $row['Place'] . "</td>";
            echo "<td>" . $row['Time'] . "</td>";
            echo "<td>" . $row['Price'] . "</td>";
            echo "<td>" . $row['Leftseat'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_close($conn);


        echo "</div>
        <br><br><br>
        <button id='buybutton' class='setbutton' onclick='openbuy()'>购票</button>

        <div id='buy'>
            <form action='buyticket.php' name='buyticket' id='buyticket' method='post'>
            <input type='date' name='date' class='filtdate' min='$today' value='$today' style='font-family: Microsoft YaHei;'>";  ?>
        <div><input type='text' name='busnum' class='ticketinfo' placeholder='车次' required='required'></div>
        <div><input type='text' name='name' class='ticketinfo' placeholder='姓名' required='required'></div>
        <div><input type='text' name='idnum' class='passinfo' placeholder='身份证号' required='required' minlength='8' maxlength='18'></div>
        <div><input type='text' name='phone' class='passinfo' placeholder='手机号' required='required' onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" minlength='11' maxlength='11'></div>
        <input type='submit' name='done' id='confirm' value='确认'>
        </form>
    </div>
    <script>
        document.getElementById("buy").style.display = "none";

        function openbuy() {
            document.getElementById("buy").style.display = "block";
            document.getElementById("buybutton").style.display = "none";
        }
    </script>

    </div>



</body>

</html>