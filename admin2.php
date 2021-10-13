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
        <div class="link">
            <span id='bus'><a href=admin.php>车次信息</a><br></span>
        </div>
        <div id='choose'>
            <span id='chosen'><a href=admin2.php>乘客信息</a><br></span>
        </div>
        <div class='link'>
            <span id='about'><a href=login.html>退出</a></span>
        </div>
        <!--<div class='link'>
            <span id='about'>关于</span>
        </div>-->
    </div>
    <div id='salepage'>
        <h1 style="color: rgb(29, 29, 29); margin-bottom: 50px;">乘客信息</h1>
        <form name='search' id='search' action="admin2.php" method="get">
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
            <button id="busnum" class="filterbutton" onclick="openfilt(event, 'busnumfilter')">车次</button>
            <button id="delete" class="filterbutton" onclick="openfilt(event, 'passnamefilter')" style="margin: 0 70px;">姓名</button>
            <button id="idnum" class="filterbutton" onclick="openfilt(event, 'idnumfilter')">身份证</button>
        </div>
        <div class="filtsearch" id="busnumfilter">
            <form action="busnumfilter.php" name="busnumfilter" class="filt" method="post">
                <input type='date' name='filtdate' class="filtdate" id='filt1' style="font-family: Microsoft YaHei;">
                <div><input type="text" name="busnum" placeholder="车次"></div>
                <input type="submit" name="done" class="done" value="查询">
            </form>
        </div>
        <script>
            document.getElementById('filt1').valueAsDate = new Date();
        </script>
        <div class="filtsearch" id="passnamefilter">
            <form action="passnamefilter.php" name="passnamefilter" class="filt" method="post">
                <input type='date' name='filtdate' class="filtdate" id='filt2' style="font-family: Microsoft YaHei;">
                <div><input type="text" name="passname" placeholder="姓名"></div>
                <input type="submit" name="done" class="done" value="查询">
            </form>
        </div>
        <script>
            document.getElementById('filt2').valueAsDate = new Date();
        </script>
        <div class="filtsearch" id="idnumfilter">
            <form action="idnumfilter.php" name="idnumfilter" class="filt" method="post">
                <input type='date' name='filtdate' class="filtdate" id='filt3' style="font-family: Microsoft YaHei;">
                <div><input type="text" name="idnum" placeholder="身份证号"></div>
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

            document.getElementById("busnumfilter").style.display = "none";
            document.getElementById("passnamefilter").style.display = "none";
            document.getElementById("idnumfilter").style.display = "none";

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

            $sql = "SELECT * FROM passengers WHERE Idnum in (
                SELECT Idnum FROM busandpass WHERE Date='$today')";
            $d = $_GET['date'];
            if ($d != '') {
                $sql = "SELECT * FROM passengers WHERE Idnum in (
                    SELECT Idnum FROM busandpass WHERE Date='$d')";
            } else {
                $sql = "SELECT * FROM passengers WHERE Idnum in (
                    SELECT Idnum FROM busandpass WHERE Date='$today')";
            }


            mysqli_select_db($conn, 'ticket');
            $retval = mysqli_query($conn, $sql);
            if (!$retval) {
                die('error: ' . mysqli_error($conn));
            }
            echo '<table class="info">
                <tr>
                    <th style="border-radius: 19px 0 0 0; ">姓名</th>
                    <th style="width: 215px;">身份证号</th>
                    <th style="width: 160px; border-radius: 0 19px 0 0;">手机号</th>
                </tr>';

            while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['Name'] . "</td>";
                echo "<td>" . $row['Idnum'] . "</td>";
                echo "<td>" . $row['Phone'] . "</td>";
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
                <form action='addpass.php' name='addpass' class='setinfo' method='post'>
                    <input type='date' name='adddate' class='setdate' min='$today' value='$today' style='font-family: Microsoft YaHei;'>
                    <div><input type='text' name='add1' placeholder='姓名' required='required'></div>
                    <div><input type='text' name='add2' placeholder='身份证号' required='required 'minlength='8' maxlength='18'></div>
                    <div><input type='text' name='add3' placeholder='手机号' required='required' onkeyup=\"this.value=this.value.replace(/\D/g,'')\" onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" minlength='11' maxlength='11'></div>
                    <div><input type='text' name='add4' placeholder='车次' required='required'></div>
                    <input type='submit' name='done' class='done' value='完成'>
                </form>
            </div>
            <div class='set' id='editinfo'>
                <form action='editpass.php' name='editpass' class='setinfo' method='post'>
                    
                    <div><input type='text' name='edit1' placeholder='姓名' required='required'></div>
                    <div><input type='text' name='edit2' placeholder='身份证号' required='required' minlength='8' maxlength='18'></div>
                    <div><input type='text' name='edit3' placeholder='手机号' required='required' onkeyup=\"this.value=this.value.replace(/\D/g,'')\" onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" minlength='11' maxlength='11'></div>
                    <input type='submit' name='done' class='done' value='完成'>
                </form>
            </div>";?>
        <div class="set" id="delinfo">
            <p>单独删除</p>
            <form action="delpass1.php" name="delpass1" class="setinfo" method="post">
                <input type='date' name='deldate' class='setdate' id='deldate1' style="font-family: Microsoft YaHei;">
                <div><input type="text" name="del1" placeholder="姓名" required='required'></div>
                <div><input type="text" name="del2" placeholder="身份证号" required='required' minlength='8' maxlength='18'></div>
                <div><input type="text" name="del3" placeholder="车次" required='required'></div>
                <input type="submit" name="done" class="done" value="完成">
            </form>
            <br><br>
            <p>批量删除</P>
            <form action="delpass2.php" name="delpass2" class="setinfo" method="post">
                
                <div><input type="text" name="del1" placeholder="姓名" required='required'></div>
                <div><input type="text" name="del2" placeholder="身份证号" required='required' minlength='8' maxlength='18'></div>
                <input type="submit" name="done" class="done" value="完成">
            </form>
            <br><br>
            <p>全部删除</p>
            <form action="delpass3.php" name="delpass3" class="setinfo" method="post">
                <input type='date' name='deldate' class='setdate' id='deldate2' style="font-family: Microsoft YaHei; margin-right:20px">
                <br>
                <input type="submit" name="done" class="done" value="完成">
            </form>
            <script>
                document.getElementById('deldate1').valueAsDate = new Date();
                document.getElementById('deldate2').valueAsDate = new Date();
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