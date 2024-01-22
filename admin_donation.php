<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_donation'])){

   $donation_id = $_POST['donation_id'];
   $update_payment = $_POST['update_payment'];
   $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
   $update_donation = $conn->prepare("UPDATE `donation` SET payment_status = ? WHERE id = ?");
   $update_donation->execute([$update_payment, $donation_id]);
   $message[] = 'payment has been updated!';

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_donation = $conn->prepare("DELETE FROM `donation` WHERE id = ?");
   $delete_donation->execute([$delete_id]);
   header('location:admin_donation.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Donation Details</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="placed-donation">

   <h1 class="title">placed donation</h1>

   <div class="box-container">

      <?php
         $select_donation = $conn->prepare("SELECT * FROM `donation`");
         $select_donation->execute();
         if($select_donation->rowCount() > 0){
            while($fetch_donation = $select_donation->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> user id : <span><?= $fetch_donation['user_id']; ?></span> </p>
         <p> placed on : <span><?= $fetch_donation['placed_on']; ?></span> </p>
         <p> name : <span><?= $fetch_donation['name']; ?></span> </p>
         <p> email : <span><?= $fetch_donation['email']; ?></span> </p>
         <p> number : <span><?= $fetch_donation['number']; ?></span> </p>
         <p> address : <span><?= $fetch_donation['address']; ?></span> </p>
         <p> total fundraiser : <span><?= $fetch_donation['total_fundraiser']; ?></span> </p>
         <p> payment method : <span><?= $fetch_donation['method']; ?></span> </p>
         <form action="" method="POST">
            <input type="hidden" name="donation_id" value="<?= $fetch_donation['id']; ?>">
            <select name="update_payment" class="drop-down">
               <option value="" selected disabled><?= $fetch_donation['payment_status']; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
            <div class="flex-btn">
               <input type="submit" name="update_donation" class="option-btn" value="update">
               <a href="admin_donation.php?delete=<?= $fetch_donation['id']; ?>" class="delete-btn" onclick="return confirm('delete this donation?');">delete</a>
            </div>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no donation placed yet!</p>';
      }
      ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>