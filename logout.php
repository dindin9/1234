<?php
// Just destroy the session to forget a user.
session_start();
session_unset();

session_destroy();

header("Location: /login.php");
exit();
?>
