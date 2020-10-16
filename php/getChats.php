<?php
    session_start();

    $host = "";
    $dbUsername = "";
    $dbPassword = "";
    $dbName = "";
	
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    
    if(array_key_exists("username", $_SESSION))
    {
        $messages = $conn->query("SELECT RIGHT(messages, {$_GET['length']}) FROM conversations WHERE (users LIKE '%<{$_SESSION['username']}>%' AND users LIKE '%<{$_GET['v']}>%');")->fetch_row()[0];
    }
    
    $conn->close();
    
    echo $messages;
?>