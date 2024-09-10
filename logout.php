<?php

session_start();
session_unset();
session_destroy();

// Set the cookie expiration time to the past
$cookieName = 'userName';
$cookieValue = ""; // Empty value
$expirationTime = time() - 3600; // Set the expiration time to an hour ago

// Set the cookie with the past expiration time
setcookie($cookieName, $cookieValue, $expirationTime, "/");

// Optionally unset the cookie variable from the $_COOKIE superglobal
unset($_COOKIE[$cookieName]);

header("Location: home");

?>