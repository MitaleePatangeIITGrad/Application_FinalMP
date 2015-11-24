<?php

// Start the session

session_start();
require 'vendor/autoload.php';

// Print the phone provided by user

$phone= $_SESSION["phone"];
echo "Phone: $phone";

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

// Bucket name

//$bucket = uniqid("itmo544-mrp-image-", false);
$bucket = "mitalee-test";

// Create the bucket only if it exists

if (!$s3->doesBucketExist($bucket))
	{
	$result = $s3->createBucket(['ACL' => 'public-read', 'Bucket' => $bucket, ]);
	$s3->waitUntil('BucketExists', array(
		'Bucket' => $bucket
	));
	echo "$bucket Created";
	}

// Put the object in the s3 bucket

$result = $s3->putObject(['ACL' => 'public-read', 'Bucket' => $bucket, 'Key' => $uploadfile, 'SourceFile' => $uploadfile, ]);

//Expiration of s3 object
$objectrule = $s3->putBucketLifecycleConfiguration([
    'Bucket' => $bucket,
    'LifecycleConfiguration' => [
        'Rules' => [ 
            [
                'Expiration' => [
                    'Date' => '2015-11-24',
                ],
                              
                'Prefix' => ' ',
                'Status' => 'Enabled',
                
            ],
            
        ],
    ],
]);

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

$image = $uploadfile;
$fname = basename($_FILES['file']['name']);

$img = new Imagick($image);

$img->thumbnailImage(100, 100, true, true);

$ext = pathinfo($fname, PATHINFO_EXTENSION);

$imagename = uniqid("DestinationImage");

$image = $imagename . '_' . $ext;

$destpath = $uploaddir . $image;
echo "DEST PATH IS ------ $destpath";

$img->writeImage($uploaddir . $image);

$thumbnail = uniqid("thumbnails",false);
echo "BUCKET NAME IS $thumbnail";

$result = $s3->createBucket(['ACL' => 'public-read', 'Bucket' => $thumbnail, ]);

# PHP version 3
$result = $s3->putObject([
    'ACL' => 'public-read',
    'Bucket' => $thumbnail,
    'Key' => $destpath,
	'SourceFile' => $destpath,
]);

$finisheds3url=$result['ObjectURL'];
echo "FINISHED URL IS ------------  $finisheds3url";

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

//Select the topic of subscription from the database
printf("Row inserted.", $stmt->affected_rows);
$link->real_query("SELECT arn FROM sns where displayname='mp2-notify-mrp'");
$res = $link->use_result();

while ($row = $res->fetch_assoc())
	{
	$topicarn = $row['arn'];
	}

$sns = new Aws\Sns\SnsClient(['version' => 'latest', 'region' => 'us-east-1', ]);

//Publish message to all subscribers of the topic
$res = $sns->publish(['TopicArn' => $topicarn, 
'Message' => 'Congratulations', 
'Subject' => 'Imagica App - image upload success', ]);

/* explicit close recommended */
$stmt->close();
$link->close();

// Redirect the user to gallery page without seeing the internal debugging info

header('Location: gallery.php', true, 303);
?>