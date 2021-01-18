<?php
    session_start();
    $activePage = basename($_SERVER['SCRIPT_FILENAME'], '.php');

    // Turn off error reporting
    error_reporting(0);

    // lets PHP echo a console log statement similar to JavaScript's
    function logConsole($message) {
        is_array($message) ? $message = json_encode($message) : "";
        echo "<script>console.log('$message');</script>";
    }

?>

<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Time Explorer - <?php echo $pageTitle; ?></title>
		<link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Cinzel|Josefin+Sans|Josefin+Slab|Rye" rel="stylesheet">
	</head>

	<body>
        <header>
            <div>
                <h1>Time Explorer</h1>
            </div>
        </header>
		<main>