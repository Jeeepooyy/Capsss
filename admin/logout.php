<?php
session_start();
unset($_SESSION['admin_id']); // Clear the admin session
header('Location: ../index.php'); // Redirect to the index.php outside the admin folder
exit();
