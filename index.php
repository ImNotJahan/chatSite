<?php
    //index

	session_start();
	
	$host = "";
    $dbUsername = "";
    $dbPassword = "";
    $dbName = "";
	
	$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
	
	if(array_key_exists("username", $_SESSION))
	{
		$textElProfile = $_SESSION["username"];
		$linkElProfile = "user?v=".$_SESSION["username"];
		
		if(!isset($_GET["v"]))
		{
		    $chatBoxHide = "display:none !important";
		}
	} else if(isset($_COOKIE["username"]))
	{
	    $result = $conn->query("SELECT password FROM accounts WHERE email = '{$_COOKIE['email']}'");
	    
	    if(hash_equals(hash("sha512", $_COOKIE["password"]), $result->fetch_row()[0]))
	    {
	        $_SESSION["username"] = $_COOKIE["username"];
	        $_SESSION["email"] = $_COOKIE["email"];
	        $_SESSION["password"] = $_COOKIE["password"];
	        
	        $textElProfile = $_SESSION["username"];
		    $linkElProfile = "user?v=".$_SESSION["username"];
	    }
	}else
	{
		$textElProfile = "Login/Signup";
		$linkElProfile = "signup";
		$style = "display:none !important";
	}
    
    if(array_key_exists("username", $_SESSION))
    {
        $grabUsers = $conn->query("SELECT users FROM conversations WHERE users LIKE '%<{$_SESSION['username']}>%' ORDER BY lastSent DESC;");
        $userList = $grabUsers->fetch_all();
    }
    
    if(array_key_exists("v", $_GET))
    {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        $chats = $conn->query("SELECT RIGHT(messages, 50000) FROM conversations WHERE (users LIKE '%<{$_SESSION['username']}>%' AND users LIKE '%<{$_GET['v']}>%');")->fetch_row()[0];
    }
    
    if(array_key_exists("chat", $_POST) && $_GET["v"] != "")
    {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        
        if(strlen($chats) > 50000)
        {
            $chats = substr($chats, strlen($chats)-50000, strlen($chats));
        }
        
        $conn->query("UPDATE `conversations` SET `messages` = '{$chats}".filter_var($_SESSION['username'].": ".$_POST['chat'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)."<br>' WHERE (users LIKE '%<{$_SESSION['username']}>%' AND users LIKE '%<{$_GET['v']}>%');");

        $conn->query("UPDATE `conversations` SET `lastSent` = '".date("YmdHis")."' WHERE (users LIKE '%<{$_SESSION['username']}>%' AND users LIKE '%<{$_GET['v']}>%');");
        
        echo "<script>window.location.replace(\"/?v=".$_GET["v"]."\")</script>";
    }
    
    $conn->close();
?>

<!doctype HTML>

<html>
	<head>
	    <script src="/js/toHttps.js"></script>
	    
		<title>!Invasion</title>
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		
		<link rel="stylesheet" href="/css/main.css">
		
		<meta charset="UTF-8">
		
		<meta name="author" content="jahan">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	</head>
	
	<body>
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
    
        <div style="<?php echo $style ?>" class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
            <div class="mr-md-3 pt-3 px-3 pt-md-5 px-md-5" id="chatListy">
		        <?php
		            $userTxt = "";
		            
		            if($userList[0][0] != null)
		            {
    		            for($i = 0; $i < sizeof($userList); $i++)
    		            {
    		                $userTxt = str_replace(">", "", str_replace("<".$_SESSION["username"]."><", "", $userList[$i][0]));
    		                echo "<a href=\"/?v={$userTxt}\" style=\"text-decoration: none;\">".$userTxt."</a><br>";
    		            }
		            }
		        ?>
            </div>
            <div style="<?php echo $chatBoxHide; ?>">
                <div class="mr-md-3 pt-3 px-3 pt-md-5 px-md-5" id="chat">
    		        <?php
    		            echo $chats;
    		        ?>
    		    </div>
    		    <form id="msgSend" method="POST" target="sendMsg" autocomplete="off" onsubmit="getDaChats()">
        	        <input required name="chat" id="chatness" placeholder="send a msg">
        	        <button type="submit">send</button>
        	    </form>
		    </div>
        </div>
		
		<iframe width="0px" height="0px" name="sendMsg" src="/sendMsg.php"></iframe>
		
		<script>
            async function grabbyGrabby() {
                let response = await fetch("/php/getChats.php/?v=<?php echo $_GET["v"]; ?>&length=50000");
                let responseText = await getTextFromStream(response.body);
                
                document.getElementById("chat").innerHTML = responseText;
            }
            
            async function getTextFromStream(readableStream) {
                let reader = readableStream.getReader();
                let utf8Decoder = new TextDecoder();
                let nextChunk;
                
                let resultStr = "";
                
                while (!(nextChunk = await reader.read()).done) {
                    let partialData = nextChunk.value;
                    resultStr += utf8Decoder.decode(partialData);
                }
                
                return resultStr;
            }
            
            var chats = document.getElementById("chat");
            
            const delay = ms => new Promise(res => setTimeout(res, ms));
            
            const getDaChats = async()=>
            {
                await delay(2000);
                document.getElementById('chatness').value = "";
                chats.scrollTop = chats.scrollHeight - chatness.clientHeight;
            }
            
            setInterval(function(){(async()=>{await grabbyGrabby();})();}, 2000);
            
            chats.scrollTop = chats.scrollHeight - chatness.clientHeight;
        </script>
	</body>
</html>