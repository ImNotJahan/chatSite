<?php
    session_start();
    
    $host = "";
    $dbUsername = "";
    $dbPassword = "";
    $dbName = "";
    
    if(array_key_exists("v", $_GET))
    {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $chats = $conn->query("SELECT RIGHT(messages, 1000) FROM conversations WHERE (users LIKE '%<{$_SESSION['username']}>%' AND users LIKE '%<{$_GET['v']}>%');")->fetch_row()[0];
    
        $conn->close();
    }
    
    if(array_key_exists("chat", $_POST) && $_GET["v"] != "")
    {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        
        if(strlen($chats) > 1000)
        {
            $chats = substr($chats, strlen($chats)-1000, strlen($chats));
        }
        
        $conn->query("UPDATE `conversations` SET `messages` = '{$chats}".filter_var($_SESSION['username'].": ".$_POST['chat'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)."<br>' WHERE (users LIKE '%<{$_SESSION['username']}>%' AND users LIKE '%<{$_GET['v']}>%');");

        $conn->query("UPDATE `conversations` SET `lastSent` = '".date("YmdHis")."' WHERE (users LIKE '%<{$_SESSION['username']}>%' AND users LIKE '%<{$_GET['v']}>%');");
    
        $conn->close();
    }
?>