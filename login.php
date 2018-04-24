<?php

// Include all required files:
// config - for configuration variables. They will be used later
// functions - for functions
// connect - for connect to db
// session - for checking user in session
require_once 'config.php';
require_once 'functions.php';
require_once 'connect.php';
require_once 'session.php';

// If it's posting a form try to login
if($_SERVER["REQUEST_METHOD"] == "POST") {
      $myusername = trim($_POST['name']);
      $mypassword = trim($_POST['password']);

      $stmt = $db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
      $stmt->execute(array($myusername, $mypassword));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


      if(count($rows) > 0) {
            // If ok - save it to session and redirect to dashboard
            $_SESSION['user'] = $rows[0];
            header('Location: /index.php');
      } else if(!count($rows)) {
            // If no rows mutching username/password - give an error
            add_msg('Your Login Name or Password is invalid. Try another one.');
            header('Location: /login.php');
      } 
} else {
    // if no - just display html to login.
	?> <?php
      require_once 'html/login.php';
}
