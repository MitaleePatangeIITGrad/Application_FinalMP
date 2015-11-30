<!DOCTYPE html>
<meta charset="UTF-8"> 
<html>
  <body>

<?php

// Start the session

session_start();
require 'vendor/autoload.php';

// Create a client to access rds db instance

$rds = new Aws\Rds\RdsClient(['version' => 'latest', 'region' => 'us-east-1', ]);
$result = $rds->describeDBInstances(['DBInstanceIdentifier' => 'itmo544-mrp-mysql-db', ]);

// Print the endpoint of the database instance

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
echo "============" . $endpoint . "================";

// Connect to the database

$link = mysqli_connect($endpoint, "controller", "ilovebunnies", "customerrecords", 3306) or die("Error " . mysqli_error($link));

// Check connection to database

if (mysqli_connect_errno())
	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}

echo "<br />" . "Connection succeeeded";

// Sqldump to take a backup of database

$uploaddir = '/tmp/';
$bkpname = uniqid("itmo544-mrp-customerrecords", false);
$ext = $bkpname . '.' . 'sql';
$bkppath = $uploaddir . $ext;

// Print the backup path

echo "<br />" . "Backup Path is  --- $bkppath";
$command = "mysqldump --user=controller --password=ilovebunnies --host=$endpoint customerrecords > $bkppath";
exec($command);

// Create a bucket to store the backup
$backupbucket = uniqid("itmo544-mrp-dbbackupbucket", false);

use Aws\S3\S3Client;
$s3 = new Aws\S3\S3Client(['version' => 'latest', 'region' => 'us-east-1', ]);

if (!$s3->doesBucketExist($backupbucket))
	{
	$result = $s3->createBucket(['ACL' => 'public-read', 'Bucket' => $backupbucket, ]);
	$s3->waitUntil('BucketExists', array(
		'Bucket' => $backupbucket
	));
	echo "<br />" . "$sketchbucket Created";
	}

$result = $s3->putObject(['ACL' => 'public-read', 'Bucket' => $backupbucket, 'Key' => $bkppath, 'SourceFile' => $bkppath, 'Expires' => date("2015/12/25"), ]);

$startingdate = date("Y-m-d");
$newendingdate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($startingDate)) . " + 1 day"));

$objectruledb = $s3->putBucketLifecycleConfiguration([
    'Bucket' => $backupbucket,
    'LifecycleConfiguration' => [
        'Rules' => [ 
            [
                'Expiration' => [
                    'Date' => '$newendingdate',
                ],
                              
                'Prefix' => ' ',
                'Status' => 'Enabled',
                
            ],
            
        ],
    ],
]);

echo "<br />" . "Backup bucket link on s3 is --- " . $result['ObjectURL'];
echo "<br />" . "Backup was successful";
$link->close();
?>



<br>
<br>
<form name="mode" action="readonly.php" method="POST">
   Do you want to enable read-only mode on the website?
   <select name="mode" id="mode">
      <option value="Y">Yes</option>
      <option value="N">No</option>
   </select>
   <div><br/></div>
   <input type="submit" id="submit" name="submit" value="Submit" />
</form>
</body>
</html> 