<?php
session_start();
require_once 'function.php';


$result = logoutUser();


header("Location: login.php?msg=" . urlencode($result['message']) . "&status=" . $result['status']);
exit();
?>
