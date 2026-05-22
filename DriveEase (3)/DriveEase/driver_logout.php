<?php
session_start();
session_unset();
session_destroy();
header("Location: driver_portal.php");
exit;
?>