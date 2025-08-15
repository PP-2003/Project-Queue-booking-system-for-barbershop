<?php
session_start();
$_SESSION = [];
setcookie(session_name(), '', time() - 3600, '/'); // 🔥 ล้างเซสชันคุกกี้
session_destroy();
header("Location: homepage.php");
exit();

?>
