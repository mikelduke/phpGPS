<?php
  
  class login {
    var $con;
    var $userGroups;
    
    var $userStatus  = false;
    var $userId      = "";
    var $userName    = "";
    var $userPass    = "";
    var $userPassEnc = "";
    var $userType    = "";
    
    /**
     * Creates a new login object using the SQL connection con and user group 
     * array
     * 
     * @param SQL $con database connection
     * @param Array $userGroups String array of usergroup names
     */
    public function __construct($con, $userGroups) {
      session_start();
      
      if (isset($_GET["out"])) { //delete session vars and show logout msg w/ refresh link
        $this->logout();
      }
      
      $this->con = $con;
      $this->userGroups = $userGroups;
      
      $this->userStatus = $this->checkLogin();
      
      if ($this->userStatus == false)
        $this->logout();
    }
    
    /**
     * Checks if a user has appropriate access to the current page. 
     * Includes checking pass and group.
     * 
     * @return boolean true is user should access page
     */
    public function checkLogin() {
      $success = $this->loadUserInfo();
      //echo "loadUserInfo: $success<br>";
      $success = $success && $this->checkPass();
      //echo "checkPass: $success<br>";
      $success = $success && $this->loadUserGroup();
      //echo "loadUserGroup: $success<br>";
      $success = $success && $this->checkGroup();
      //echo "checkGroup: $success<br>";
      return $success;
    }
    
    private function loadEncryptedPass($userPass) {
      if ($this->userName == "") {
        $this->userPassEnc = "";
        return false;
      }
      
      $saltSql = "SELECT user_salt FROM users WHERE user_name='$this->userName'";
      $result = mysqli_query($this->con, $saltSql);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
      
      $row = @mysqli_fetch_assoc($result);
      if ($row != null) {
        $salt = $row['user_salt'];
        $this->userPassEnc = crypt($userPass, $salt);
        return true;
      } else {
        $this->userPassEnc = "";
        return false;
      }
    }
    
    private function checkPass() {
      if ($this->userPassEnc == "") return false;
      
      $passSql = "SELECT user_name, user_id FROM users WHERE user_name='$this->userName' AND user_pass='$this->userPassEnc'";
      $result = mysqli_query($this->con, $passSql);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
      
      $row = @mysqli_fetch_assoc($result);
      if ($row != null && ($row['user_name'] == $this->userName)) {
        $this->setSession();
        $this->userId = $row['user_id'];
        return true;
      }
      else return false;
    }
    
    private function loadUserInfo() {
      //print_r($_SESSION);
      if (isset($_SESSION["user"]) && isset($_SESSION["pass"])) {
        $user = addslashes(strip_tags($_SESSION["user"]));
        $this->userName = $user;
        
        $pass = addslashes(strip_tags($_SESSION["pass"]));
        $this->userPass = $pass;
        $this->loadEncryptedPass($pass);
      }
      else if (isset($_POST["user"]) && isset($_POST["pass"])) {
        $user = addslashes(strip_tags($_POST["user"]));
        $this->userName = $user;
        
        $pass = addslashes(strip_tags($_POST["pass"]));
        $this->userPass = $pass;
        $this->loadEncryptedPass($pass);
      }
      
      //echo "Load User Info: $this->userName $this->userPass $this->userPassEnc<br>";
      
      if ($this->userPassEnc != "") return true;
      else return false;
    }
    
    private function loadUserGroup() {
      if ($this->userName != "" && $this->userPassEnc != "") {
        $groupSql = 
        "SELECT user_type_name 
           FROM users u 
           INNER JOIN user_types ut on u.user_type_id = ut.user_type_id
         WHERE u.user_name = '$this->userName'
           AND u.user_pass = '$this->userPassEnc'";
        $result = mysqli_query($this->con, $groupSql);
        if (!$result) {
          die('Invalid query: ' . mysql_error());
        }
        
        $row = @mysqli_fetch_assoc($result);
        if ($row != null) {
          $this->userType = $row['user_type_name'];
          return true;
        } else {
          $this->userType = "";
          return false;
        }
      }
    }
    
    public function checkGroup() {
      if ($this->userType == "") return false;
      if ($this->userGroups == null) return true;
      if ($this->userGroups == "all") return true;
      
      if (is_array($this->userGroups)) {
        foreach($this->userGroups as $group) {
          if ($group == $this->userType) return true;
        }
        return false;
      } else {
        if ($this->userGroups == $this->userType) return true;
        else return false;
      }
    }
    
    public function logout() {
      $_SESSION["user"] = null;
      $_SESSION["pass"] = null;
    }
    
    private function setSession() {
      $_SESSION["user"] = $this->userName;
      $_SESSION["pass"] = $this->userPass;
      
      //echo "session set";
    }
    
    public function getStatus() {
      return $this->userStatus;
    }
    
    public function getType() {
      return $this->userType;
    }
    
    public function getName() {
      return $this->userName;
    }
    
    public function getUserId() {
      return $this->userId;
    }
    
    public function changePassword() {
      
    }
    
    public static function showLogin() {
      echo "Login:<br>";
      echo "<form name=\"login\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">";
      echo "Username: <input type=\"text\" name=\"user\"><br>";
      echo "Password: <input type=\"password\" name=\"pass\"><br>";
      echo "<input type=\"submit\" name=\"submit\" value=\"submit\">";
      echo "</form>";
    }
    
    /**
     * Checks the pass for user_id to see if it matches what is in the database
     * 
     * @param SQL con
     * @param string $userid
     * @param string $pass
     */
    public static function checkPassForUser($con, $userid, $pass) {
      if ($con == null || $userid == null || $pass == null || $userid == "" || $pass == "") return false;
      
      //Load Salt and compare passwords
      $saltSql = "SELECT user_salt, user_pass FROM users WHERE user_id='$userid'";
      $result = mysqli_query($con, $saltSql);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
      $row = @mysqli_fetch_assoc($result);
      if ($row != null) {
        $salt = $row['user_salt'];
        $oldPass = $row['user_pass'];
        
        $encpass = crypt($pass, $salt);
        
        if ($encpass = $oldPass) return true;
        else return false;
      } else {
        return false;
      }
    }
  }
?>
