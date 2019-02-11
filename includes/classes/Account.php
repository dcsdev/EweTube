<?php 
require_once("DataAccess.php");
class Account {

  private $errorArr;
  private $con;

  public function __construct($con) {
    $this->errorArr = array();
    $this->con      = $con;
  }

  public function login($username, $password){
    $password = md5($password);

    $params = array(
      "username" => $username,
      "password" => $password
    );

    $rowcount = DataAccess::SelectRowCount($this->con, "SELECT * from users", $params);

    if ($rowcount == 1) {
      return true;
    } else {
        echo "User Not Found";
        exit();
      array_push($this->errorArr, Constants::$LOGIN_FAILED);
      return false;
    }
  }

  public function register($username, $firstname, $lastname, $email1, $email2, $password1, $password2) {

    $this->validateUserName($username);
    $this->validateFirstName($firstname);
    $this->validateLastName($lastname);
    $this->validateEmails($email1,$email2);
    $this->validatePassword($password1, $password2 );

     if (empty($this->errorArr == true)) {
       $this->insertUserDetails($username, $firstname, $lastname, $password1, $email1);
       return true;
     } else {
       //TODO: Display Error
       return false;
     }
  }

  private function insertUserDetails($username, $firstname, $lastname, $password, $email) {

    $hashedPW = md5($password);
    $profilepic = "/assets/images/profilephotos/head.png";
    $date = date("Y-m-d");

     $insertQuery = $this->con->prepare("INSERT INTO users 
     Values('',:username, :firstname, :lastname, :email,:password, '1000-01-01 00:00:00', :profilepic)");
     $insertQuery->bindParam(":username", $username);
     $insertQuery->bindParam(":firstname", $firstname);
     $insertQuery->bindParam(":lastname", $lastname);
     $insertQuery->bindParam(":email", $email);
     $insertQuery->bindParam(":password", $hashedPW);
     $insertQuery->bindParam(":profilepic", $profilepic);

     $insertQuery->execute();
  }

  public function getError($error) {    
     if (!in_array($error, $this->errorArr)) {
       $error = "";
     }

     return "<span class='errorMessage'>$error</span>";
  }

	//Validation Functions
private function validateUserName($username) {

  if (strlen($username) < 5) {     
      array_push($this->errorArr, Constants::$USERNAME_LENGTH);
      return;
  }

    $params = array(
      "username" => $username
    );

    $rowcount = DataAccess::SelectRowCount($this->con, "SELECT * FROM users", $params);

    if ($rowcount  == 0) {
      return true;
    } else {
        echo "User Not Found";
      array_push($this->errorArr, Constants::$$USERNAME_IN_USE);
      return false;
    }

}
private function validateFirstName($firstname) {
if (strlen($firstname) > 25 || strlen($firstname) < 2) {
      array_push($this->errorArr, Constants::$FIRSTNAME_LEGNTH);
      return;
    }
}

private function validateLastName($lastname) {
if (strlen($lastname) > 25 || strlen($lastname) <2) {
      array_push($this->errorArr, Constants::$LASTNAME_LEGNTH);
      return;
    }
}

private function validateEmails($email1, $email2) {
  if ($email1 != $email2) {
    array_push($this->errorArr, Constants::$EMAILS_DO_NOT_MATCH);
    return;
  }

  if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
    array_push($this->errorArr, Constants::$EMAILS_FORMAT_WRONG);
    return;
  }
}

private function validatePassword($password1, $password2) {
if ($password1 != $password2) {
    array_push($this->errorArr, Constants::$PASSWORDS_DO_NOT_MATCH);
    return;
  }

  if (preg_match('/[^A-Za-z0-9]/', $password1)) {
    array_push($this->errorArr, Constants::$PASSWORD_FORMAT_WRONG);
    return;
  }

  if (strlen($password1) > 40 || strlen($password1)  <10 ) {
      array_push($this->errorArr, Constants::$PASSWORD_LENGTH);
      return;
    }
}

private function validateEmail($email1, $username) {
  if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
    array_push($this->errorArr, Constants::$EMAILS_FORMAT_WRONG);
    return;
  }

  $whereParams = array(
    "email" => $email1,
    "username" => "<> " . $username
  );

  $rowcount = DataAccess::SelectRowCount($this->con, "SELECT email from users", $whereParams);
  
  if ($rowcount != 0) {
    array_push($this->errorArray, Constants::$EMAIL_TAKEN);
  }
}

public function getFirstErrorMessage() {
  if (!empty($this->errorArr)) {
    return $this->errorArr[0];
  }
}

private function validateOldPassword($pw, $un) {
  
    $pw = md5($pw);

    $params = array(
      "username" => $un,
      "password" => $pw
    );

    $rowcount = DataAccess::SelectRowCount($this->con, "SELECT * FROM users", $params);

    if ($rowcount) {
      array_push($this->errorArr, Constants::$PASSWORD_INCORRECT);
  }
}


public function updateUserPassword($oldPassword,$newPassword1,$newPassword2,$username) {
  $this->validateOldPassword($oldPassword, $username);
  $this->validatePassword($newPassword1, $newPassword2);

  $pw = md5($newPassword1);

  $columns = array(
    "password" => $pw
  );

  $whereParams = array(
    "username" => $un
  );

  if (empty($this->errorArr)) {
   return DataAccess::Update($con, "users", $columns,$whereParams);

  } else {
    return false;
  }

}

public function updateUserDetails($firstname,$lastname,$email,$username) {
          $this->validateFirstName($firstname);
          $this->validateLastName($lastname);
          $this->validateEmail($email, $username);
          
          if (empty($this->errorArr)) {


  $columns = array(
    "firstname" => $firstname,
    "lastname"  => $lastname,
    "email"     => $email
  );

  $whereParams = array(
    "username" => $username
  );
            return DataAccess::Update($this->con, "users", $columns, $whereParams);
          } else {
            return false;
          }

        }
}
?>