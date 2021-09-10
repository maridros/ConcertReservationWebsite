<?php 
  session_start(); 
  header('Content-Type: text/html; charset=utf-8');

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
  
  if (isset($_SESSION['p_id'])) {
	unset ($_SESSION['p_id']);
  }
  if (isset($_SESSION['concert'])) {
	unset ($_SESSION['concert']);
  }
  if (isset($_SESSION['seats'])) {
	unset ($_SESSION['seats']);
  }
  if(isset($_SESSION['add_for_reservation'])) {
	  unset($_SESSION['add_for_reservation']);
  }
  
  // connect to the database
  $db = mysqli_connect("localhost", "root", "", "concerts_db");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Home</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
<header>
            <h1>My Account</h1>
</header>

<div class="topnav">
  <a href="index.php?logout='1'">Logout</a>
  <a href="reserve.php">New Reservation</a>
  <a href="index.php">My Reservations</a>
  <a href="card.php">My Card</a>
</div>

<div class="content">
    <?php  if (isset($_SESSION['username'])) : ?>
		
		
    	<h2>Reservations Cancellation</h2>
		
    	
<?php
	if(!empty($_POST['reservation']) && array_filter($_POST['reservation'])){
	 $cnt=array();
     $cnt=count($_POST['reservation']);
     if($cnt > 0){
       for($i=0;$i<$cnt;$i++)
       {
           $r_id=$_POST['reservation'][$i];
       
           $delete_reservation_query="delete from reservations where r_id=".$r_id;
           $delete_reservation_result = $db->query($delete_reservation_query);
       }
     }
  }
  header("location: index.php");
  endif
?>
</div>



</body>
</html>
