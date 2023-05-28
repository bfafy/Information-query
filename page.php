<?php
//开启会话
session_start();
//判断用户是否登录
include "conf/pdlog.php";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Ajax Page Navigation</title>
  <link rel="stylesheet" href="css/page.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<nav>
  <ul>
    <li><a href="#" data-page="xx.php">信息查询</a></li>
    <li><a href="#" data-page="shebei.php">设备信息查询</a></li>
    <li class="nav-logout"><a href="#" data-page="logout.php">退出登录</a></li>
  </ul>
</nav>
<p style="text-align: center;"><?php echo $_SESSION['username'];?></p>
<div id="content"></div>

<script>
$(document).ready(function() {
  $('nav a').click(function(e) {
    e.preventDefault();

    var page = $(this).data('page');

    $.ajax({
      url: page,
      success: function(data) {
        $('#content').html(data);
      }
    });
  });
});
</script>
</body>
</html>