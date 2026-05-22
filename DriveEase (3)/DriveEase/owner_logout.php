<?php
session_start();
session_unset();
session_destroy();
header("Location: owner_portal.php");
exit;
?>