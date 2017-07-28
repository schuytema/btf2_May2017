<?php
  if(session_status() !== PHP_SESSION_ACTIVE)
  {
    session_start();
  }
  if(!isset($_SESSION['time']))
  {
    $_SESSION['time'] = $_GET['time'];
  }
?>
