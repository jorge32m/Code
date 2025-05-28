<?php

  mysqli_report(  MYSQLI_REPORT_ERROR);
       
  $con = new mysqli("localhost","root","","24198_Loja");
  
  if ($con->connect_error)
  {
      die("connection failed: " . $con->connect_error);
  }

?>