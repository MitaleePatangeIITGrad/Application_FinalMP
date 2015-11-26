<?php
// Start the session
session_start();
require 'vendor/autoload.php';

$mode = $_POST["mode"];
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
if (!($stmt = $link->prepare("INSERT INTO introspection(mode) VALUES (?)")))
	{
	echo "Prepare failed: (" . $link->errno . ") " . $link->error;
	}

echo "Statement succeeeded";

$stmt->bind_param("s", $mode);

if (!$stmt->execute())
	{
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

printf("Value inserted.", $stmt->affected_rows);

$stmt->close();
$link->close();

header('Location: index.php', true, 303);
?>