<?php session_start(); ?>
<html>
   <head>
      <title>ITMO 544 Imagica Upload</title>
   </head>
   <body>
      <h3>
         <center> Upload your image to Imagica Application </center>
      </h3>
      <div><br/></div>
      <center>
            <p> Thank you for using Imagica Application for your photos.</p><br>
      <?php
      $subscription=$_SESSION["subscription"];
		if($subscription == 'Y')
		{
                  echo "Successful login. Please check your phone for subscription details and confirm";
                  echo "</br>";
                  echo "Notifications will be sent once you have uploaded your photos";
                  echo "</br>";
                  echo "</br>";
            }else {
                  echo "Successful login. Upload your images to the gallery";
                  echo "</br>";
                  echo "</br>";
            }
	?>
         <form enctype="multipart/form-data" id="upload" action="submit.php" method="POST">
            <!-- MAX_FILE_SIZE must precede the file input field -->
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
            <!-- Name of input element determines name in $_FILES array -->
            Send this file: <input name="file" type="file" /> </br>		
            <input type="submit" id="buttonFile" name="buttonFile" value="Upload" />
         </form>
      </center>
   </body>
</html>