<html>

<head>
    <title>
        管理员界面
    </title>
    <link rel="stylesheet" type="text/css" href="admin.css">
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
            <span id='chosen'><a href=admin.php>车次信息</a><br></span>
        </div>
        <div class='link'>
            <span id='passenger'><a href=admin2.php>乘客信息</a><br></span>
            <span id='about'><a href=login.html>退出</a></span>
            <!--<span id='about'>关于</span>-->
        </div>
    </div>
    <div id='salepage'>
        <h1 style="color: rgb(29, 29, 29); margin-bottom: 50px;">车次信息</h1>
        <form name='search' id='search' action="admin.php" method="get">
            <div id="searchdate">
                <input type='date' name="date" id="date" style="font-family: Microsoft YaHei;">
            </div>
            <input type="submit" name="submit" value="查询" id='searchbutton'>
        </form>
        <script>
            document.getElementById('date').valueAsDate = new Date();
        </script>
        <button id="openfilterbutton" onclick="openfiltbutton()">筛选</button>
        <div id="filter">
            <button id="stastion" class="filterbutton" onclick="openfilt(event, 'stationfilter')">到站</button>
            <button id="delete" class="filterbutton" onclick="openfilt(event, 'timefilter')" style="margin: 0 70px;">时间</button>
            <button id="idnum" class="filterbutton" onclick="openfilt(event, 'mixfilter')">混合</button>
        </div>
        <div class="filtsearch" id="stationfilter">
            <form action="stationfilter.php" name="stationfilter" class="filt" method="post">
                <input type='date' name='filtdate' class="filtdate" id='filt1' style="font-family: Microsoft YaHei;">
                <div><input type="text" name="station" placeholder="到站" required='required'></div>
                <input type="submit" name="done" class="done" value="查询">
            </form>
        </div>
        <script>
            document.getElementById('filt1').valueAsDate = new Date();
        </script>
        <div class="filtsearch" id="timefilter">
            <form action="timefilter.php" name="timefilter" class="filt" method="post">
                <input type='date' name='filtdate' class="filtdate" id='filt2' style="font-family: Microsoft YaHei;">
                <br>
                <select name="timeselect1" id="timeselect1" class="timeselect">
                    <option value="07:00" name="07:00">7:00</option>
                    <option value="11:00" name="11:00">11:00</option>
                    <option value="15:00" name="15:00">15:00</option>
                    <option value="19:00" name="19:00">19:00</option>
                </select>
                ~
                <select name="timeselect2" id="timeselect2" class="timeselect">
                    <option value="11:00" name="11:00">11:00</option>
                    <option value="15:00" name="15:00">15:00</option>
                    <option value="19:00" name="19:00">19:00</option>
                    <option value="23:00" name="23:00">23:00</option>

                </select>
                <br>
                <input type="submit" name="done" class="done" value="查询">
            </form>
        </div>
        <script>
            document.getElementById('filt2').valueAsDate = new Date();
        </script>
        <div class="filtsearch" id="mixfilter">
            <form action="mixfilter.php" name="mixfilter" class="filt" method="post">
                <input type='date' name='filtdate' class="filtdate" id='filt3' style="font-family: Microsoft YaHei;">
                <div><input type="text" name="station" placeholder="到站" required='required'></div>
                <select name="timeselect3" id="timeselect3" class="timeselect">
                    <option value="07:00" name="07:00">7:00</option>
                    <option value="11:00" name="11:00">11:00</option>
                    <option value="15:00" name="15:00">15:00</option>
                    <option value="19:00" name="19:00">19:00</option>
                </select>
                ~
                <select name="timeselect4" id="timeselect4" class="timeselect">
                    <option value="11:00" name="11:00">11:00</option>
                    <option value="15:00" name="15:00">15:00</option>
                    <option value="19:00" name="19:00">19:00</option>
                    <option value="23:00" name="23:00">23:00</option>
                </select>
                <br>
                <input type="submit" name="done" class="done" value="查询">
            </form>
        </div>
        <script>
            document.getElementById('filt3').valueAsDate = new Date();
        </script>
        <script>
            document.getElementById("filter").style.display = "none";

            function openfiltbutton() {
                document.getElementById("search").style.display = "none";
                document.getElementById("openfilterbutton").style.display = "none";
                document.getElementById("filter").style.display = "block";
            }

            document.getElementById("stationfilter").style.display = "none";
            document.getElementById("timefilter").style.display = "none";
            document.getElementById("mixfilter").style.display = "none";

            function openfilt(evt, setName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("filtsearch");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("filterbutton");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(setName).style.display = "block";
                evt.currentTarget.className += " active";
            }
        </script>
        <div id='form'>
            <?php
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
            $today = date('Y-m-d');

            $d = $_POST['filtdate'];
            $s = $_POST['station'];

            $sql = "SELECT distinct a.*, (seat-count(idnum)) as Leftseat from
                (select * from businfo where Date='$d')a
                left join
                (select * from busandpass where Date='$d')b
                on a.busnum=b.busnum
                where Place='$s'
                group by busnum
                order by time";



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
                    <th>车牌号</th>
                    <th>票价</th>
                    <th>总座位数</th>
                    <th style="border-radius: 0 19px 0 0;">剩余座位</th>    
                </tr>';

            while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['Busnum'] . "</td>";
                echo "<td>" . $row['Place'] . "</td>";
                echo "<td>" . $row['Time'] . "</td>";
                echo "<td>" . $row['License'] . "</td>";
                echo "<td>" . $row['Price'] . "</td>";
                echo "<td>" . $row['Seat'] . "</td>";
                echo "<td>" . $row['Leftseat'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            mysqli_close($conn);
            

        echo"</div>
        <br><br><br>
        <button id='add' class='setbutton' onclick=\"openset(event, 'addinfo')\">添加</button>
        <button id='edit' class='setbutto' onclick=\"openset(event, 'editinfo')\">编辑</button>
        <button id='delete' class='setbutton' onclick=\"openset(event, 'delinfo')\">删除</button>
        <br><br><br>
        <div class='set' id='addinfo'>
            <form action='add.php' name='addinfo' class='setinfo' method='post'>
                <input type='date' name='adddate' class='setdate' min='$today' value='$today' style='font-family: Microsoft YaHei;'>
                <div><input type='time' name='add3' placeholder='发车时间' required='required'></div>
                <div><input type='text' name='add1' placeholder='车次' required='required'></div>
                <div><input type='text' name='add2' placeholder='到站' required='required'></div>
                <div><input type='text' name='add4' placeholder='车牌号' required='required'></div>
                <div><input type='text' name='add5' placeholder='票价' required='required'></div>
                <div><input type='text' name='add6' placeholder='总座位' required='required'></div>
                <input type='submit' name='done' class='done' value='完成'>
            </form>
        </div>
        <div class='set' id='editinfo'>
            <form action='edit.php' name='editinfo' class='setinfo' method='post'>
                <input type='date' name='editdate' class='setdate' min='$today' value='$today' style='font-family: Microsoft YaHei;'>
                <div><input type='text' name='edit1' placeholder='车次' required='required'></div>
                <div><input type='text' name='edit2' placeholder='到站' required='required'></div>
                <div><input type='time' name='edit3' placeholder='发车时间' required='required'></div>
                <div><input type='text' name='edit4' placeholder='车牌号' required='required'></div>
                <div><input type='text' name='edit5' placeholder='票价' required='required'></div>
                <div><input type='text' name='edit6' placeholder='总座位' required='required'></div>
                <input type='submit' name='done' class='done' value='完成'>
            </form>
        </div>";?>
        <div class="set" id="delinfo">
            <p>单独删除</p>
            <form action="del1.php" name="delinfo1" class="setinfo" method="post">
                <input type='date' name='deldate' class='setdate' id='deldate1' style="font-family: Microsoft YaHei;">
                <div><input type="text" name="delnum" placeholder="车次" required='required'></div>
                <input type="submit" name="done" class="done" value="完成">
            </form>
            <br><br>
            <p>批量删除</p>
            <form action="del2.php" name="delinfo2" class="setinfo" method="post">
                <input type='date' name='deldate' class='setdate' id='deldate2' style="font-family: Microsoft YaHei;">
                <div><input type="text" name="delname" placeholder="到站" required='required'></div>
                <input type="submit" name="done" class="done" value="完成">
            </form>
            <br><br>
            <p>全部删除</p>
            <form action="del3.php" name="delinfo3" class="delinfo" method="post">
                <input type='date' name='deldate' class='setdate' id='deldate3' style="font-family: Microsoft YaHei;">
                <br>
                <input type="submit" name="done" class="done" value="完成">
            </form>
        </div>

    </div>
    <script>
        document.getElementById('deldate1').valueAsDate = new Date();
        document.getElementById('deldate2').valueAsDate = new Date();
        document.getElementById('deldate3').valueAsDate = new Date();
        document.getElementById("addinfo").style.display = "none";
        document.getElementById("editinfo").style.display = "none";
        document.getElementById("delinfo").style.display = "none";

        function openset(evt, setName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("set");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("setbutton");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(setName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

    </div>

</body>

</html>