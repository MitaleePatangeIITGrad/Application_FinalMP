<?php
	include_once('header.php');
?>

<h3 style="padding:2px;width:100%;background-color:OldLace ;color:Purple ;text-align:center;">
   <center> Upload your image to Imagica Application </center>
</h3>
<div><br/></div>
<center>
<p> Thank you for using Imagica Application for your photos.</p>
<br>
       
     <?php
require 'vendor/autoload.php';

$subscription = $_SESSION["subscription"];

$rds = new Aws\Rds\RdsClient(['version' => 'latest', 'region' => 'us-east-1', ]);
$result = $rds->describeDBInstances(array(
	'DBInstanceIdentifier' => 'itmo544-mrp-mysql-db-readonly',
));

// Print the endpoint of the database instance

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
echo "\n============" . $endpoint . "================\n";

// Connect to the database

$link = mysqli_connect($endpoint, "controller", "ilovebunnies", "customerrecords", 3306) or die("Error " . mysqli_error($link));

// Check connection to database

if (mysqli_connect_errno())
	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}

// Check for readonly mode setting
$res = $link->query("SELECT * FROM introspection ");

if ($res->num_rows != 0)
	{
	while ($row = $res->fetch_assoc())
		{
		$mode = $row['mode'];
		}
	}

if ($mode == 'Y')
	{
	echo "The upload functionality currently is not available.";
	echo "</br>";
	echo "Please proceed to the gallery to view your images by clicking on the Gallery link";
	echo "</br>";
	}
  else
	{
      // Check for subscription details and display message accordingly
	if ($subscription == 'Y')
		{
		echo "Successful login. Please check your phone for subscription details and confirm.";
		echo "</br>";
		echo "Notifications will be sent once you have uploaded your photos.";
		echo "</br>";
		echo "</br>";
		}
	  else
		{
		echo "Successful login. Upload your images to the gallery.";
		echo "</br>";
		echo "</br>";
		}

?>
      
<div style="min-height:300px;padding-left:20%;padding-right:20%;padding-top:5%">
   <form enctype="multipart/form-data" id="upload" action="submit.php" method="POST">
      <!-- MAX_FILE_SIZE must precede the file input field -->
      <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
      <!-- Name of input element determines name in $_FILES array -->
      Send this file: <input name="file" type="file" /> </br></br>		
      <input type="submit" id="buttonFile" name="buttonFile" value="Upload"/>
   </form>
</div>

<?php
}
	include_once('footer.php');
?>