<?php
session_start();
require 'vendor/autoload.php';

if (isset($_POST["buttonLogin"]))
	{
	$phone = $_POST['phone'];
	$_SESSION["phone"] = $phone;

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
	$res = $link->query("SELECT * FROM user where phone='$phone'");

	// Check if the user is already signed up

	if ($res->num_rows != 0)
		{
		while ($row = $res->fetch_assoc())
			{
			echo $row['phone'];
			$_SESSION["id"] = $row['id'];
			$_SESSION["name"] = $row['uname'];
			$_SESSION["phone"] = $row['phone'];
			$_SESSION["subscription"] = $row['issubscribed'];
			header('Location: upload.php', true, 303);
			}
		}
	  else
		{
		echo "No records found";

		// Redirect to signup.php

		header('Location: signup.php', true, 303);
		}

	$link->close();
	}
  else
if (isset($_POST["buttonSignUp"]))
	{

	// Redirect to signup.php

	header('Location: signup.php', true, 303);
	}

?>