<?php
   session_start();

   unset($_SESSION['CONNECT']);


   session_destroy();

   header("Location: login.html");
    exit();
?>
