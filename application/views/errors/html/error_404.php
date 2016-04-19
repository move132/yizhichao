<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<style type="text/css">

/* ::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; } */
.layui-layer-content{
	background:#fff;
	color:#666;
	border:1px solid #efefef;
	box-shadow:0 0 50px #ccc;
}
.layui-layer-content #container{ color:#EC7878;}
</style>
</head>
<body>
	<div id="container">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
</body>
</html>