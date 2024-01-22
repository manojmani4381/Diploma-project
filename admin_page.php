<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

      <div class="box">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `donation` WHERE payment_status = ?");
         $select_pendings->execute(['pending']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            
         };
      ?>
      <h3>$<?= $total_pendings; ?>/-</h3>
      <p>total pendings</p>
      <a href="admin_donation.php" class="btn">see donation</a>
      </div>

      <div class="box">
      <?php
         $total_completed = 0;
         $select_completed = $conn->prepare("SELECT * FROM `donation` WHERE payment_status = ?");
         $select_completed->execute(['completed']);
         while($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)){
            
         };
      ?>
      <h3>$<?= $total_completed; ?>/-</h3>
      <p>completed donation</p>
      <a href="admin_donation.php" class="btn">see donation</a>
      </div>

      <div class="box">
      <?php
         $select_donation = $conn->prepare("SELECT * FROM `donation`");
         $select_donation->execute();
         $number_of_donation = $select_donation->rowCount();
      ?>
      <h3><?= $number_of_donation; ?></h3>
      <p>donation placed</p>
      <a href="admin_donation.php" class="btn">see donation</a>
      </div>

      <div class="box">
      <?php
         $select_fundraiser = $conn->prepare("SELECT * FROM `fundraiser`");
         $select_fundraiser->execute();
         $number_of_fundraiser = $select_fundraiser->rowCount();
      ?>
      <h3><?= $number_of_fundraiser; ?></h3>
      <p>fundraiser added</p>
      <a href="fundraiser.php" class="btn">see fundraiser</a>
      </div>

      <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
         $select_users->execute(['user']);
         $number_of_users = $select_users->rowCount();
      ?>
      <h3><?= $number_of_users; ?></h3>
      <p>total users</p>
      <a href="admin_users.php" class="btn">see accounts</a>
      </div>

      <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
         $select_admins->execute(['admin']);
         $number_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $number_of_admins; ?></h3>
      <p>total admins</p>
      <a href="admin_users.php" class="btn">see accounts</a>
      </div>

      <div class="box">
      <?php
         $select_accounts = $conn->prepare("SELECT * FROM `users`");
         $select_accounts->execute();
         $number_of_accounts = $select_accounts->rowCount();
      ?>
      <h3><?= $number_of_accounts; ?></h3>
      <p>total accounts</p>
      <a href="admin_users.php" class="btn">see accounts</a>
      </div>

      <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `message`");
         $select_messages->execute();
         $number_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $number_of_messages; ?></h3>
      <p>total messages</p>
      <a href="admin_contacts.php" class="btn">see messages</a>
      </div>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>