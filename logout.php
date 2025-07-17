<?php
session_start();
session_destroy();
error_log("User logged out, redirecting to index.php");
echo "<script>window.location.href='index.php';</script>";
exit;
?>
