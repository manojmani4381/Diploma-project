<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['donation'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['state'] .' '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $donate_total = 0;
   $donate_fundraiser[] = '';

   $donate_query = $conn->prepare("SELECT * FROM `donate` WHERE user_id = ?");
   $donate_query->execute([$user_id]);
   if($donate_query->rowCount() > 0){
      while($donate_item = $donate_query->fetch(PDO::FETCH_ASSOC)){
         $donate_fundraiser[] = $donate_item['name'].' ( '.$donate_item['amount'].' )';
         $sub_total = ($donate_item['price'] * $donate_item['amount']);
         $donate_total += $sub_total;
      };
   };

   $total_fundraiser = implode(', ', $donate_fundraiser);

   $donation_query = $conn->prepare("SELECT * FROM `donation` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_fundraiser = ?");
   $donation_query->execute([$name, $number, $email, $method, $address, $total_fundraiser]);

   if($donate_total == 0){
      $message[] = 'your donate is empty';
   }elseif($donation_query->rowCount() > 0){
      $message[] = 'donation placed already!';
   }else{
      $insert_donation = $conn->prepare("INSERT INTO `donation`(user_id, name, number, email, method, address, total_fundraiser, placed_on) VALUES(?,?,?,?,?,?,?,?)");
      $insert_donation->execute([$user_id, $name, $number, $email, $method, $address, $total_fundraiser, $placed_on]);
      $delete_donate = $conn->prepare("DELETE FROM `donate` WHERE user_id = ?");
      $delete_donate->execute([$user_id]);
      $message[] = 'donation placed successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Donation Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-donation">

   <?php
      $donate_grand_total = 0;
      $select_donate_items = $conn->prepare("SELECT * FROM `donate` WHERE user_id = ?");
      $select_donate_items->execute([$user_id]);
      if($select_donate_items->rowCount() > 0){
         while($fetch_donate_items = $select_donate_items->fetch(PDO::FETCH_ASSOC)){
            $donate_total_price = ($fetch_donate_items['amount'] );
            $donate_grand_total += $donate_total_price;
   ?>
   <p> <?= $fetch_donate_items['name']; ?> <span>(<?= '$'.$fetch_donate_items['amount']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">your donate is empty!</p>';
   }
   ?>
   <div class="grand-total">grand total : <span>$<?= $donate_grand_total; ?>/-</span></div>
</section>

<section class="checkout-donation">

   <form action="" method="POST">

      <h3>DONER DETAILS</h3>

      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" placeholder="enter your name" class="box" required>
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" placeholder="enter your number" class="box" required>
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" placeholder="enter your email" class="box" required>
         </div>
         <div class="inputBox">
            <span>payment method :</span>
            
            <select name="method" class="box" required>
            <option value="" selected disabled>select Payment Methods</option>
               <option value="Google Pay">Google Pay</option>
               <option value="Phonepe">Phonepe</option>
               <option value="paytm">paytm</option>
               <option value="paypal">paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>address line 01 :</span>
            <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
         </div>
         <div class="inputBox">
            <span>address line 02 :</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" required>
         </div>
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" placeholder="e.g. chennai" class="box" required>
         </div>
         <div class="inputBox">
            <span>state :</span>
            <input type="text" name="state" placeholder="e.g.TamilNadu" class="box" required>
         </div>
         <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" placeholder="e.g.India" class="box" required>
         </div>
         <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" class="box" required>
         </div>
      </div>

      <input type="submit" name="donation" class="btn <?= ($donate_grand_total > 1)?'':'disabled'; ?>" value="Donate Now">

   </form>

</section>
<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>