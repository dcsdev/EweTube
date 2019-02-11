<?php require_once("includes/config.php");
require_once("includes/classes/Account.php"); 
require_once("includes/classes/Constants.php"); 

$acc = new Account($con);

if (isset($_POST["loginButton"])) {
    
    $username   = $_POST["username"];
    $password  =  $_POST["password"];

    $result = $acc->login($username, $password);

    if ($result) {
        $_SESSION["userLoggedIn"] = $username;
         header("Location: index.php");
    }
}

function getInputValues($name) {
	if (isset($_POST[$name])) {
		echo $_POST[$name];
	}
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>EweTube</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
         
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</head>
<body>
<div class="signInContainer">
    <div class="column">
        <div class="header">
            <img src="assets/images/icons/VideoTubeLogo.jpg" alt="sitelogo" title="logo">
            <h3>Sign In</h3>
            <span>to continue to video tube</span>
        </div>
        <div class="loginForm">
            <form action="signin.php" METHOD="POST">
                <input type="text" name="username" placeholder="Enter Your User Name" autocomplete="off" value="<?php getInputValues('username') ?>" required>
                <input type="password" name="password" placeholder="Enter Your Password" autocomplete="off" required>
                <input type="submit" name="loginButton" value="SUBMIT">
            </form>
        </div>
        <a href="signup.php" class="signInMessage">Need An Account? Sign Up For One Here!</a>
    </div>
</div>
</body>
</html>