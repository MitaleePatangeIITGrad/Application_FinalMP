<?php
session_start();
require 'vendor/autoload.php';

// Post variables fetch from the form

$uname = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$subscription = $_POST['subscription'];

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

if (!($stmt = $link->prepare("INSERT INTO user (id,uname,email,phone,issubscribed) VALUES (NULL,?,?,?,?)")))
	{
	echo "Prepare failed: (" . $link->errno . ") " . $link->error;
	}

echo "Statement succeeeded";

// Bind the parameters

$stmt->bind_param("ssss", $uname, $email, $phone, $subscription);

if (!$stmt->execute())
	{
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
  else
	{
	printf("Row inserted.", $stmt->affected_rows);
	
	$userid = mysqli_insert_id($link);

	// Update session parameters

	$_SESSION["id"] = $userid;
	$_SESSION["name"] = $uname;
	$_SESSION["phone"] = $phone;
	$_SESSION["email"] = $email;
	$_SESSION["subscription"] = $subscription;

	// Redirect the user to upload an image

	header('Location: upload.php', true, 303);
	}

/* explicit close recommended */
$stmt->close();

// Subscribe the user to the topic if opted by 'Yes'

if ($subscription == 'Y')
	{
	$link->real_query("SELECT arn FROM sns where displayname='mp2-notify-mrp'");
	$res = $link->use_result();
	echo "Result set order...\n";
	while ($row = $res->fetch_assoc())
		{
		$topicarn = $row['arn'];
		}

	echo "Topic arn is --- $topicarn";
	$sns = new Aws\Sns\SnsClient(['version' => 'latest', 'region' => 'us-east-1', ]);
	$result = $sns->subscribe(['TopicArn' => $topicarn, 'Protocol' => 'sms', 'Endpoint' => $phone, ]);
	}

$link->close();
?>
