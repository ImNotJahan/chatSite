<?php
    //signup

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
                $usernamey = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    
                $host = "";
                $dbUsername = "";
                $dbPassword = "";
                $dbName = "";
                
                $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
                
                $username = $conn->query("SELECT * FROM accounts WHERE username = '{$usernamey}'");
                
                if($username->num_rows == 0)
                {
                    $conn->query("INSERT INTO accounts (email, password, username) VALUES('{$email}', '{$password}', '{$usernamey}')");
                    
                    echo "<script>alert('Account created sucessfuly');window.location = '/login';</script>";
                }else
                {
                    echo "<script>alert('Username already in use')</script>";
                }
                
                $conn->close();
            }
        } else
        {
            echo "<script>alert('Retry captcha');</script>";
        }
    }
?>

<!doctype HTML>

<html>
    <head>
        <title>Sign up</title>
        
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
    
    
    
    <body style="overflow-x: hidden;">
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
                <form method="POST" autocomplete="off">
                    <h1 class="h3 mb-3 font-weight-normal">Sign up</h1>
                    <div class="brs"><p>.</p><p>.</p><p>.</p></div>
                    <label for="inputEmail" class="sr-only">Email address</label><br>
                    <input type="email" id="inputEmail" name="email" placeholder="Email address" required autofocus><br><br>
                    
                    <label for="inputPassword" class="sr-only">Password</label><br>
                    <input type="password" id="inputPassword" name="password" placeholder="Password" required><br><br>
                    
                    <label for="confirmPassword" class="sr-only">Confirm Password</label><br>
                    <input type="password" onblur="checkConfirm()" id="confirmPassword" placeholder="Confirm Password" required><br><br>
                    
                    <label for="username" class="sr-only">Username</label><br>
                    <input type="text" onblur="checkUsername()" id="username" name="username" placeholder="Username" required><br><br>
                    
                    <div class="elem-group">
                        <label for="captcha">Please Enter the Captcha Text</label><br>
                        <img src="/captcha.php" id="captcha" alt="CAPTCHA" onmouseover="this.width='400'; this.height='100'" onmouseout="this.width='200'; this.height='50'"><br>
                        <input type="text" name="captchaChallenge" autofill="off" placeholder="enter captcha text" required>
                        <button type="button" onclick="document.getElementById('captcha').src = '/captcha.php?'+Date();">Refresh Captcha</button>
                    </div>
                    
                    <br>
                    
                    <button type="submit">Sign up</button><br><br>
                    <label>Have an account? <a href="/login"><u>Log in.</u></a></label>
                </form>
            </div>
        </div>
    </body>
</html>

<script>
    var password = document.getElementById("inputPassword");

    function checkConfirm()
    {
        if(password.value === confirmPassword.value)
        {
            confirmPassword.setCustomValidity("");
        } else
        {
            confirmPassword.setCustomValidity("Passwords do not match.");
        }
    }
    
    var username = document.getElementById("username");
    var format = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    
    function checkUsername()
    {
        if(format.test(username.value))
        {
            username.setCustomValidity("Username can only contain letters and numbers");
        } else
        {
            username.setCustomValidity("");
        }
    }
</script>