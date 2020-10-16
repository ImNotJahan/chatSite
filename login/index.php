<?php
    //login
    
    session_start();
    
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
	}
    
    if(array_key_exists("captchaChallenge", $_POST))
    {
        if(strtoupper($_POST["captchaChallenge"]) == $_SESSION["captcha_text"])
        {
            if(array_key_exists("email", $_POST) && array_key_exists("password", $_POST))
            {
                $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
                $password = hash("sha512", $_POST["password"]);

                $host = "";
                $dbUsername = "";
                $dbPassword = "";
                $dbName = "";
                
                $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
                
                $result = $conn->query("SELECT password FROM accounts WHERE email = '{$email}'");
                
                if($result->num_rows == 0)
                {
                    echo "<script>alert('Email not found');</script>";
                } else
                {
                    if(hash_equals($password, $result->fetch_row()[0]))
                    {
                        $data = $conn->query("SELECT * FROM accounts WHERE email = '{$email}'")->fetch_row();
                        
                        $_SESSION["username"] = $conn->query("SELECT username FROM accounts WHERE email = '{$email}'")->fetch_row()[0];
                        $_SESSION["password"] = $_POST["password"];
                        $_SESSION["email"] = $conn->query("SELECT email FROM accounts WHERE email = '{$email}'")->fetch_row()[0];
                        
                        setcookie("username", $_SESSION["username"], time() + (86400 * 30), "/");
                        setcookie("password", $_SESSION["password"], time() + (86400 * 30), "/");
                        setcookie("email", $_SESSION["email"], time() + (86400 * 30), "/");
                        
                        echo "<script>window.location = '/';</script>";
                    } else
                    {
                        echo "<script>alert('Wrong password')</script>";
                    }
                }
            }
        } else
        {
            echo "<script>alert('Retry captcha');</script>";
        }
    }
?>

<html>
    <head>
        <title>Login</title>
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" href="/css/main.css">
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Jahan">
        <meta name="robots" content="noindex">
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
            <div class="col-md-3 mx-auto">
                <form autocomplete="off" class="form-signin" method="POST">
                    <h1>Log in</h1>
                    <div class="brs"><p>.</p><p>.</p><p>.</p></div>
                    <label for="inputEmail">Email address</label><br>
                    <input type="email" name="email" placeholder="Email address" required autofocus><br><br>
                    
                    <label for="inputPassword">Password</label><br>
                    <input type="password" name="password" placeholder="Password" required><br><br>
                    
                    <div>
                        <label for="captcha">Please Enter the Captcha Text</label><br>
                        <img src="/captcha.php" id="captcha" alt="CAPTCHA" onmouseover="this.width='400'; this.height='100'" onmouseout="this.width='200'; this.height='50'"><br>
                        <input type="text" name="captchaChallenge" autofill="off" required>
                        <button type="button" onclick="document.getElementById('captcha').src = '/captcha.php?'+Date();">Refresh Captcha</button>
                    </div>
                    
                    <br>
                    
                    <button type="submit">Log in</button><br><br>
                    <label>Don't have an account? <a href="/signup">Sign up.</a></label>
                </form>
            </div>
        </div>
    </body>
</html>