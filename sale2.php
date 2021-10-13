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
        <h1 style="color: rgb(29, 29, 29); margin-bottom: 50px;">退票</h1>
        <br>
        <button id="search" class="setbutton" onclick="openfilt()"  style="margin-top: 10px; margin-bottom: 50px;">查询</button>
        <div class="filtsearch" id="mixfilter">
            <form action="refundsearch.php" name="salesearch" class="filt" method="post">
                <div><input type="text" name="name" id="station" placeholder="姓名" required="required" style="margin-left: 0;"></div>
                <div><input type="text" name="idnumber" id="idnumber" placeholder="身份证号" required="required" maxlength="18" minlength="18" style="width: 240px;"></div>
                <input type="submit" name="done" id="done" value="查询">
            </form>
        </div>
        <script>
             document.getElementById("mixfilter").style.display = "none";

            function openfilt() {
                document.getElementById("mixfilter").style.display = "block";
                document.getElementById("search").style.display = "none";
            }
        </script>
        <script>
            document.getElementById('form').style.display="none";
            function showinfo(){
                document.getElementById('form').style.display="block";
            }
        </script>
        
        <?php
        date_default_timezone_set('Asia/Shanghai');
        $hi = date('H:i');
        $date = date('Y-m-d');
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime("+1 day"));
        $aftertomorrow = date('Y-m-d', strtotime("+2 days"));
        echo "<br><br><br>
        <button id='buybutton' class='setbutton' onclick='openbuy()'>退票</button>

        <div id='buy'>
            <form action='refundticket.php' name='refundticket' id='buyticket' method='post'>
                <input type='date' name='date' class='filtdate' min='$today' value='$today' style='font-family: Microsoft YaHei;'>"; ?>
                <div><input type="text" name="busnum" class="ticketinfo" placeholder="车次" required="required"></div>
                <div><input type="text" name="name" class="ticketinfo" placeholder="姓名" required="required"></div>
                <div><input type="text" name="idnum" class="passinfo" placeholder="身份证号" required="required"
                maxlength="18" minlength="18" ></div>
                <div><input type="text" name="phone" class="passinfo" placeholder="手机号" required="required"
                onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"  maxlength="11" minlength="11" ></div>
                <input type="submit" name="done" id="confirm" value="确认">
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