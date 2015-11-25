<?php

// Start the session
session_start();
require 'vendor/autoload.php';

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

$uploaddir = '/tmp/';
$date = date("Y-m-d H:i:s");

$bkpname = "customerrecords" . $date;
//$bkpname = uniqid("customerrecords", false);

$ext = $bkpname . '.' . 'sql';

$bkppath = $uploaddir. $ext;

echo $bkppath;

$command="mysqldump --user=controller --password=ilovebunnies --host=$endpoint customerrecords > $bkppath";

exec($command);

//$bucketname = uniqid("dbbackupbucket", false);
$backupbucket = "mitu-backup-test";

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

if (!$s3->doesBucketExist($backupbucket))
	{
	$result = $s3->createBucket(['ACL' => 'public-read', 'Bucket' => $backupbucket, ]);
	$s3->waitUntil('BucketExists', array('Bucket' => $backupbucket));
	echo "$sketchbucket Created";
	}

$result = $s3->putObject([
    'ACL' => 'public-read',
    'Bucket' => $backupbucket,
   'Key' => $bkppath,
    'SourceFile' => $bkppath,
]);

echo $result['ObjectURL'];

echo "\n Backup was successful";

$link->close();
?>

<!DOCTYPE html>
 <meta charset="UTF-8"> 
<html>
<body>
<br>
<br>
<a href="index.php"> Go to Main page</a>
</body>
</html> 