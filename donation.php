<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Show Donation Details</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="donation-show">

   <h1 class="title">Donation Show</h1>

   <div class="box-container">

   <?php
      $select_donation = $conn->prepare("SELECT * FROM `donation` WHERE user_id = ?");
      $select_donation->execute([$user_id]);
      if($select_donation->rowCount() > 0){
         while($fetch_donation = $select_donation->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <p> placed on : <span><?= $fetch_donation['placed_on']; ?></span> </p>
      <p> name : <span><?= $fetch_donation['name']; ?></span> </p>
      <p> number : <span><?= $fetch_donation['number']; ?></span> </p>
      <p> email : <span><?= $fetch_donation['email']; ?></span> </p>
      <p> address : <span><?= $fetch_donation['address']; ?></span> </p>
      <p> payment method : <span><?= $fetch_donation['method']; ?></span> </p>
      <p> your Donation : <span><?= $fetch_donation['total_fundraiser']; ?></span> </p>
      <p> payment status : <span style="color:<?php if($fetch_donation['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_donation['payment_status']; ?></span> </p>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no donation placed yet!</p>';
   }
   ?>

   </div>

</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>