<?php
    //prefs

	session_start();
    
    ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	error_reporting(E_ALL);
    	
	if(array_key_exists("username", $_SESSION))
	{
		$textElProfile = $_SESSION["username"];
		$linkElProfile = "user?v=".$_SESSION["username"];
	} else if(false)
	{
	
	} else
	{
		$textElProfile = "Login/Signup";
		$linkElProfile = "signup";
		
		echo "<script>alert(\"you needa account\")</script>";
		echo "<script>window.location.replace(\"/\")</script>";
	}
	
	$host = "";
    $dbUsername = "";
    $dbPassword = "";
    $dbName = "";
    
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    
    if(array_key_exists("desc", $_POST))
    {
        $conn->query("UPDATE `accounts` SET `description` = '".filter_var($_POST['desc'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)."' WHERE `accounts`.`username` = '{$_SESSION['username']}';");
    }
    
    $conn->close();
?>

<html>
	<head>
		<title>!Invasion</title>
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		
		<link rel="stylesheet" href="/css/main.css">
		
		<meta charset="UTF-8">
		
		<meta name="author" content="jahan">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	
	<body style="overflow-x: hidden">
		<header>
            <nav class="navbar navbar-expand-md" style="background-color: #101820">
                <a class="navbar-brand" href="/">!Invasion</a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="true" aria-label="Toggle navigation">
                    O
                </button>
                
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav mr-auto">
                        <li>
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        
                        <li>
                            <a class="nav-link" href="/<?php echo $linkElProfile ?>"><?php echo $textElProfile; ?></a>
                        </li>
                    </ul>
                    <form class="form-inline mt-2 mt-md-0" autocomplete="off" action="/search/" method="get">
                        <input name="search" type="text" placeholder="Search" aria-label="Search">
                        <button type="submit">Search</button>
                    </form>
                </div>
            </nav>
        </header>
		
		<div class="row">
		    <div class="col-md-8 mx-auto">
    		    <label >set your profiles description:</label>
    		    
    		    <form class="nobr" method="POST">
    		        <input name="desc">
    		        <button type="submit">set</button>
    		    </form>
    		</div>
		</div
	</body>
</html>