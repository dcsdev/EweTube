<?php

require_once("includes/header.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitization.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/SettingsFormProvider.php");

if (!User::isLoggedIn()) {
    header("Location: signin.php");
}

$detailsMessage = "";
$passwordMessage = "";

$formProvider = new SettingsFormProvider();

if (isset($_POST["saveDetailsButton"])) {
    $account    = new Account($con);
    $firstName  = FormSanitization::sanitzeFormString($_POST["firstName"]);
    $lastName   = FormSanitization::sanitzeFormString($_POST["lastName"]);
    $email      = FormSanitization::sanitzeFormString($_POST["email"]);

    if ($account->updateUserDetails($firstName,$lastName,$email,$userLoggedInInstance->getUsername())) {
        $detailsMessage = "<div class='alert alert-success'>
                            <strong>SUCCESS! SAVED USER DETAILS</strong>
                          </div>";        
                            header("Location: settings.php");
    } else {
        $errorMessage = $account->getFirstErrorMessage();

        if ($errorMessage == "") $errorMessage = "Something Went Wrong";

        $detailsMessage = "<div class='alert alert-danger'>
        <strong>Cannot Update User Details</strong>
      </div>";
    }
}

if (isset($_POST["savePasswordButton"])) {
    $account        = new Account($con);
    $oldPassword    = FormSanitization::sanitzeFormPassword($_POST["oldPassword"]);
    $newPassword1   = FormSanitization::sanitzeFormPassword($_POST["newPassword"]);
    $newPassword2   = FormSanitization::sanitzeFormPassword($_POST["newPassword2"]);

    if ($account->updateUserPassword($oldPassword, $newPassword1, $newPassword2, $userLoggedInInstance->getUsername())) {
        $detailsMessage = "<div class='alert alert-success'><strong>SUCCESS! PASSWORD UPDATED</strong></div>";
    } else {
        $errorMessage = $account->getFirstErrorMessage();

        if ($errorMessage == "") $errorMessage = "Something Went Wrong";

        $detailsMessage = "<div class='alert alert-danger'>
        <strong>Cannot Update Password</strong>
      </div>";
    }
}

?>

<div class="settingsContainer column">
    <div class="formSection">
        <div class="message">
            <?php echo $detailsMessage; ?>
        </div>

        <?php echo $formProvider->createUserDetailsForm(
            isset($_POST["firstname"]) ? $_POST["firstname"] : $userLoggedInInstance->getFirstName(),
            isset($_POST["lastname"]) ? $_POST["lastname"] : $userLoggedInInstance->getLastName(),
            isset($_POST["email"]) ? $_POST["email"] : $userLoggedInInstance->getEmail());
        ?>
    </div>

    <div class="formSection">
    <div class="message">
            <?php echo $passwordMessage; ?>
        </div>
        <?php echo $formProvider->createPasswordForm();?>
    </div>
</div>