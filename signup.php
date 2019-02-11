<?php 
require_once("includes/config.php"); 
require_once("includes/classes/FormSanitization.php"); 
require_once("includes/classes/Account.php"); 
require_once("includes/classes/Constants.php"); 

$acc = new Account($con);


if (isset($_POST["submitButton"])) {
    
    $firstname  = FormSanitization::sanitzeFormString($_POST["firstname"]);
    $lastname   = FormSanitization::sanitzeFormString($_POST["lastname"]);
    $username   = FormSanitization::sanitzeFormUserName($_POST["username"]);
    $email      = FormSanitization::sanitzeFormEmail($_POST["email"]);
    $email2     = FormSanitization::sanitzeFormEmail($_POST["email2"]);
    $password1  = FormSanitization::sanitzeFormPassword($_POST["password1"]);
    $password2  = FormSanitization::sanitzeFormPassword($_POST["password2"]);

    $result = $acc->register($username, $firstname, $lastname, $email, $email2, $password1, $password2);

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
            <img src="assets/images/icons/VideoTubeLogo.png" alt="sitelogo" title="logo">
            <h3>Sign Up</h3>
            <span>to continue to video tube</span>
        </div>
        <div class="loginForm">
            <form action="signup.php" method="POST">
                <?php echo $acc->getError(Constants::$FIRSTNAME_LEGNTH); ?>
                <input type="text" name="firstname" placeholder="Enter Your First Name" value="<?php getInputValues('firstname') ?>"  autocomplete="off" required>

                <?php echo $acc->getError(Constants::$LASTNAME_LEGNTH); ?>
                <input type="text" name="lastname" placeholder="Enter Your Last Name" value="<?php getInputValues('lastname') ?>" autocomplete="off" required>

                <?php echo $acc->getError(Constants::$USERNAME_LENGTH);?>
			    <?php echo $acc->getError(Constants::$USERNAME_IN_USE);?>
                <input type="text" name="username" placeholder="Enter Your User Name" value="<?php getInputValues('username') ?>" autocomplete="off" required>
                
                <?php echo $acc->getError(Constants::$EMAILS_DO_NOT_MATCH ); ?>
				<?php echo $acc->getError(Constants::$EMAILS_FORMAT_WRONG); ?>
				<?php echo $acc->getError(Constants::$EMAIL_LENGTH); ?>
                <input type="email" name="email" placeholder="Enter Your Email Address" value="<?php getInputValues('email') ?>" autocomplete="off" required>
                <input type="email" name="email2" placeholder="Confirm Your Email Address" value="<?php getInputValues('email2') ?>" autocomplete="off" required>
                
                <?php echo $acc->getError(Constants::$PASSWORDS_DO_NOT_MATCH); ?>
				<?php echo $acc->getError(Constants::$PASSWORD_LENGTH); ?>
				<?php echo $acc->getError(Constants::$PASSWORD_FORMAT_WRONG); ?>
                <input type="password" name="password1" placeholder="Enter Your Password" autocomplete="off" required>
                <input type="password" name="password2" placeholder="Confirm Your Password" autocomplete="off" required>

                <input type="submit" name="submitButton" value="SUBMIT">

            </form>
        </div>
        <a href="signin.php" class="signInMessage">Have An Account? Sign In Here!</a>
    </div>
</div>
</body>
</html>