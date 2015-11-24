<?php
session_start();
?>

<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Salsa' rel='stylesheet' type='text/css'>
	  <meta charset="utf-8">
      <meta name="description" content="IMAGES GALLERY">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="css/blueimp-gallery.css">
      <link rel="stylesheet" href="css/blueimp-gallery-indicator.css">
      <link rel="stylesheet" href="css/demo.css">
      <link rel="stylesheet" type="text/css" href="header.css">
	  <title>ITMO 544 Imagica App</title>
	  </head>
    <body style="margin:0">
		<!-- Header -->
		<div style="height:100px;background-color:Plum">			
			<div style="width=100%">
				<div style="color:DeepSkyBlue;font-family:'Salsa';font-style:cursive;font-size:150%;font-weight:bold;width:100%;padding-left:20%;padding-top:2%;">
					Welcome to Imagica Application
				</div>
				<div style="color:DarkBlue;font-family:'Salsa';font-style:cursive;font-size:90%;font-weight:bold;height:40px;padding-left:30%;">
					~ Where memories are stored ~
				</div>
				<div style="padding-left:45%;">
					<ul style="list-style-type:none;margin:0">
					<?php
					if(isset($_SESSION['phone']))
					{
					?>
						<li style="display:inline;padding-left:10%"><a href="upload.php" style="color:FireBrick;font-weight:bold;">Upload Image</a></li>
						<li style="display:inline;padding-left:10%"><a href="gallery.php" style="color:FireBrick;font-weight:bold;">Gallery</a></li>
						<li style="display:inline;padding-left:10%"><a href="index.php" style="color:FireBrick;font-weight:bold;">Logout</a></li>
					<?php
					}
					?>
					</ul>
				</div>
			</div>
		</div>
		