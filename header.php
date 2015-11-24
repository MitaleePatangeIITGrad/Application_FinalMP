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
      <link href='http://fonts.googleapis.com/css?family=Salsa' rel='stylesheet' type='text/css'>
	  <title>ITMO 544 Imagica App</title>
	  </head>
    <body style="margin:0">
		<!-- Header -->
		<div style="height:100px;background-color:97B95A">			
			<div id="text" style="width=95%">
				<div id="headerText" style="color:FFFFCC;width:75%;padding-left:20%;font-family:calibri;font-size:300%;font-style:oblique;font-weight:bold;float:left">
					Welcome to Imagica Application
				</div>
				<!-- Tag line-->
				<div id="headerText1" style="color:FFFFCC;padding-left:25%;height:40px;font-family:calibri;font-size:100%;font-style:oblique;font-weight:bold;float:left">
					~ Where memories are stored ~
				</div>
				<!-- End of Tag line -->
				<!-- Menu Tabs -->
				<div id="menu" style="padding-left:65%;">
					<ul style="list-style-type:none;margin:0">
					<?php
					if(isset($_SESSION['useremail']))
					{
					?>
						<li style="display:inline;padding-left:10%"><a href="index.php" style="text-decoration:none;text-align:center;color:FFFFCC;font-weight:bold;">Logout</a></li>
						<li style="display:inline;padding-left:10%"><a href="upload.php" style="text-decoration:none;text-align:center;color:FFFFCC;font-weight:bold;">Upload Image</a></li>
						<li style="display:inline;padding-left:10%"><a href="gallery.php" style="text-decoration:none;text-align:center;color:FFFFCC;font-weight:bold;">Gallery</a></li>
					<?php
					}
					?>
					</ul>
				</div>
				<!-- End of Menu Tabs -->
			</div>
		</div>
		