

<?php

// Start the session

session_start();
require 'vendor/autoload.php';

// Print the phone provided by user

$phone = $_SESSION["phone"];
echo "Phone: $phone";

$upload = $_POST["buttonFile"];
$_SESSION["upload"] = $upload;

// Upload file to tmp folder with the filename specified

$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

// Print whether file upload was successful or not

echo '<pre>';

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
	{
	echo "File is valid, and was successfully uploaded.\n";
	}
  else
	{
	echo "Possible file upload attack!\n";
	}

// Print debugging info

echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";

// Use a s3Client to create a bucket

use Aws\S3\S3Client;
$s3 = new Aws\S3\S3Client(['version' => 'latest', 'region' => 'us-east-1', ]);

// Image bucket name
// $imagebucket = uniqid("itmo544-mrp-image-", false);

$imagebucket = "mitu-test";

// Create the bucket only if it exists

if (!$s3->doesBucketExist($imagebucket))
	{
	$result = $s3->createBucket(['ACL' => 'public-read', 'Bucket' => $imagebucket, ]);
	$s3->waitUntil('BucketExists', array(
		'Bucket' => $imagebucket
	));
	echo "$imagebucket Created";
	}

// Put the object in the s3 bucket

$result = $s3->putObject(['ACL' => 'public-read', 'Bucket' => $imagebucket, 'Key' => $uploadfile, 'SourceFile' => $uploadfile, ]);
$startingdate = date("Y-m-d");
$newendingdate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($startingdate)) . " + 1 day"));

// Expiration of s3 object

$objectrule = $s3->putBucketLifecycleConfiguration(['Bucket' => $imagebucket, 'LifecycleConfiguration' => ['Rules' => [['Expiration' => ['Date' => '$newendingdate', ], 'Prefix' => ' ', 'Status' => 'Enabled', ], ], ], ]);

// Print the s3 url

$url = $result['ObjectURL'];
echo $url;

// Create a client to access rds db instance

$rds = new Aws\Rds\RdsClient(['version' => 'latest', 'region' => 'us-east-1', ]);
$result = $rds->describeDBInstances(['DBInstanceIdentifier' => 'itmo544-mrp-mysql-db', ]);

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

echo "Connection succeeeded";

// Prepared Statement to insert data

if (!($stmt = $link->prepare("INSERT INTO gallery(id,userid,s3rawurl,s3finishedurl,filename,status) VALUES (NULL,?,?,?,?,?)")))
	{
	echo "Prepare failed: (" . $link->errno . ") " . $link->error;
	}

echo "Statement succeeeded";

// Create sketch of the uploaded images

$image = $uploadfile;
$fname = basename($_FILES['file']['name']);

// Create an Imagick instance for sketch

$img = new Imagick($image);
$img->sketchImage(10, 0, 45);
$imagename = uniqid("sketchImage"); //Unique name for output image
$ext = pathinfo($fname, PATHINFO_EXTENSION); //Get file extension
$image = $imagename . '.' . $ext;
$destpath = $uploaddir . $image;
$img->writeImage($uploaddir . $image); // Write the image to destination

// $sketchbucket = uniqid("sketch",false);
$sketchbucket = "mitu-thumbnail-test";

if (!$s3->doesBucketExist($sketchbucket))
	{
	$result = $s3->createBucket(['ACL' => 'public-read', 'Bucket' => $sketchbucket, ]);
	$s3->waitUntil('BucketExists', array(
		'Bucket' => $sketchbucket
	));
	echo "$sketchbucket Created";
	}

$result = $s3->putObject(['ACL' => 'public-read', 'Bucket' => $sketchbucket, 'Key' => $destpath, 'SourceFile' => $destpath, ]);
$objectrulesk = $s3->putBucketLifecycleConfiguration(['Bucket' => $sketchbucket, 'LifecycleConfiguration' => ['Rules' => [['Expiration' => ['Date' => '$newendingdate', ], 'Prefix' => ' ', 'Status' => 'Enabled', ], ], ], ]);
$finisheds3url = $result['ObjectURL'];

// Initialize table insert values

$userid = $_SESSION["id"];
$s3rawurl = $url; //  $result['ObjectURL']; from above
$s3finishedurl = $finisheds3url;
$filename = basename($_FILES['file']['name']);
$status = 0;

// Bind the parameters

$stmt->bind_param("isssi", $userid, $s3rawurl, $s3finishedurl, $filename, $status);

if (!$stmt->execute())
	{
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

printf("Row inserted.", $stmt->affected_rows);

// Select the topic of subscription from the database

$link->real_query("SELECT arn FROM sns where displayname='mp2-notify-mrp'");
$res = $link->use_result();

while ($row = $res->fetch_assoc())
	{
	$topicarn = $row['arn'];
	}

$sns = new Aws\Sns\SnsClient(['version' => 'latest', 'region' => 'us-east-1', ]);

// Publish message to all subscribers of the topic

$res = $sns->publish(['TopicArn' => $topicarn, 'Message' => 'Congratulations', 'Subject' => 'Imagica App - image upload success', ]);
/* explicit close recommended */
$stmt->close();
$link->close();

// Redirect the user to gallery page without seeing the internal debugging info

header('Location: gallery.php', true, 303);
?>

