<?php
	include_once('header.php');
?>

<h1><center>Images Gallery</center></h1>

<?php
require 'vendor/autoload.php';

//Show the images only uploaded by the user
$id = $_SESSION["id"];

// Create a client to access rds db instance
$rds = new Aws\Rds\RdsClient(['version' => 'latest', 'region' => 'us-east-1', ]);
$result = $rds->describeDBInstances(array(
	'DBInstanceIdentifier' => 'itmo544-mrp-mysql-db-readonly',
));

// Print the endpoint of the database instance
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

//echo "\n============" . $endpoint . "================\n";
// Connect to the database
$link = mysqli_connect($endpoint, "controller", "ilovebunnies", "customerrecords", 3306) or die("Error " . mysqli_error($link));

// Check connection to database
if (mysqli_connect_errno())
	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}

// Select all records from the table
$link->real_query("SELECT * FROM gallery where userid='$id'");
$res = $link->use_result();

$upload=$_SESSION["upload"];
echo $upload;

if(!isset($upload)) {
?>
   
      <h2>Lightbox image gallery</h2> 
      <div class="links">
         <div id="links">    
                 
<?php
while ($row = $res->fetch_assoc())
	{
	echo '<a href="' . $row['s3rawurl'] . '" title="' . $row['filename'] . '" data-gallery ><img src="' . $row['s3rawurl']  . '" width="100" height="100"></a>';
      //echo '<a href="' . $row['s3finishedurl'] . '" title="' . $row['filename'] . '" data-gallery ><img src="' . $row['s3finishedurl'] . '" width="100" height="100"></a>';
	}
$link->close();
?>	 
         </div>
      </div>

<?php } else { ?>

       <h2>Sketch image gallery</h2>
      <div class="links">
         <div id="links">  
<?php
while ($row = $res->fetch_assoc())
	{
	//echo '<a href="' . $row['s3rawurl'] . '" title="' . $row['filename'] . '" data-gallery ><img src="' . $row['s3rawurl']  . '" width="100" height="100"></a>';
      echo '<a href="' . $row['s3finishedurl'] . '" title="' . $row['filename'] . '" data-gallery ><img src="' . $row['s3finishedurl'] . '" width="100" height="100"></a>';
	}
$link->close();
?>
         </div>
      </div>
      
<?php }  ?> 

      <!-- The Gallery as lightbox dialog, should be a child element of the document body -->
      <div id="blueimp-gallery" class="blueimp-gallery">
         <div class="slides"></div>
         <h3 class="title"></h3>
         <a class="prev">‹</a>
         <a class="next">›</a>
         <a class="close">×</a>
         <a class="play-pause"></a>
         <ol class="indicator"></ol>
      </div>
      
      <h2>Carousel image gallery</h2>
      <!-- The Gallery as inline carousel, can be positioned anywhere on the page -->
      <div id="blueimp-gallery-carousel" class="blueimp-gallery blueimp-gallery-carousel">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
      </div>
         
      <script src="js/blueimp-helper.js"></script>
      <script src="js/blueimp-gallery.js"></script>
      <script src="js/blueimp-gallery-fullscreen.js"></script>
      <script src="js/blueimp-gallery-indicator.js"></script>
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      
      <script>
         if (!window.jQuery) {
             document.write(
                 '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"><\/script>'
             );
         }
      </script>
      
      <script src="js/jquery.blueimp-gallery.js"></script>    
      <script src="js/demo.js"></script>
      
      <script>
      blueimp.Gallery(
      document.getElementById('links').getElementsByTagName('a'),
      {
        container: '#blueimp-gallery-carousel',
        carousel: true
      }
      );
      </script> 

<?php
	include_once('footer.php');
?>