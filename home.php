<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $fid = $_POST['fid'];
   $fid = filter_var($fid, FILTER_SANITIZE_STRING);
   $f_name = $_POST['f_name'];
   $f_name = filter_var($f_name, FILTER_SANITIZE_STRING);
   $f_price = $_POST['f_price'];
   $f_price = filter_var($f_price, FILTER_SANITIZE_STRING);
   $f_image = $_POST['f_image'];
   $f_image = filter_var($f_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$f_name, $user_id]);

   $check_donate_numbers = $conn->prepare("SELECT * FROM `donate` WHERE name = ? AND user_id = ?");
   $check_donate_numbers->execute([$f_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_donate_numbers->rowCount() > 0){
      $message[] = 'already added to donate!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, fid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $fid, $f_name, $f_price, $f_image]);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_donate'])){

   $fid = $_POST['fid'];
   $fid = filter_var($fid, FILTER_SANITIZE_STRING);
   $f_name = $_POST['f_name'];
   $f_name = filter_var($f_name, FILTER_SANITIZE_STRING);
   $f_price = $_POST['f_price'];
   $f_price = filter_var($f_price, FILTER_SANITIZE_STRING);
   $f_image = $_POST['f_image'];
   $f_image = filter_var($f_image, FILTER_SANITIZE_STRING);
   $f_amt = $_POST['f_amt'];
   $f_amt = filter_var($f_amt, FILTER_SANITIZE_STRING);

   $check_donate_numbers = $conn->prepare("SELECT * FROM `donate` WHERE name = ? AND user_id = ?");
   $check_donate_numbers->execute([$f_name, $user_id]);

   if($check_donate_numbers->rowCount() > 0){
      $message[] = 'already added to donate!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$f_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$f_name, $user_id]);
      }

      $insert_donate = $conn->prepare("INSERT INTO `donate`(user_id, fid, name, price, amount, image) VALUES(?,?,?,?,?,?)");
      $insert_donate->execute([$user_id, $fid, $f_name, $f_price, $f_amt, $f_image]);
      $message[] = 'added to donate!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

   <section class="home">

      <div class="content">
         <span></span>
         <h3>NO ONE HAS EVER BECOME POOR BY GIVING</h3>
         <p>You have two hands One to help yourself,the second to help others</p>
         <a href="about.php" class="btn">about us</a>
      </div>

   </section>

</div>

<section class="home-category">

   <h1 class="title">TOP categories</h1>

   <div class="box-container">

      <div class="box">
         <img src="image/heart.png" alt="">
         <h3>Heart Surgery</h3>
         <p>Heart surgery is done to correct problems with the heart.Many heart surgeries are done each year United States for various heart problems</p>
         <a href="category.php?category=Heart_Surgery" class="btn">Heart Surgery</a>
      </div>

      <div class="box">
         <img src="image/brain.png" alt="">
         <h3>Brain Surgery</h3>
         <p>A surgical procedure performed to make correctional changes in the structure of a part of the brain for various complications.</p>
         <a href="category.php?category=Brain_Surgery" class="btn">Brain Surgery</a>
      </div>

      <div class="box">
         <img src="image/accident.png" alt="">
         <h3>Accident</h3>
         <p>An accident is an unintended, normally unwanted event that was not directly caused by humans. The term accident implies that nobody should be blamed, but the event may have been caused by unrecognized or unaddressed risks.</p>
         <a href="category.php?category=Accident" class="btn">Accident</a>
      </div>

      <div class="box">
         <img src="image/all.jpg" alt="">
         <h3>All Treatement</h3>
         <p>The use of an agent, procedure, or regimen, such as a drug, surgery, or exercise, in an attempt to cure or mitigate a disease, condition, or injury.</p>
         <a href="category.php?category=All_Treatement" class="btn">All Treatement</a>
      </div>
      

   </div>

</section>

<section class="fundraiser">

   <h1 class="title">latest Fundraiser</h1>

   <div class="box-container">

   <?php
      $select_fundraiser = $conn->prepare("SELECT * FROM `fundraiser` LIMIT 6");
      $select_fundraiser->execute();
      if($select_fundraiser->rowCount() > 0){
         while($fetch_fundraiser = $select_fundraiser->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">$<span><?= $fetch_fundraiser['price']; ?></span>/-</div>
      <a href="view_page.php?fid=<?= $fetch_fundraiser['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_fundraiser['image']; ?>" alt="">
      <div class="name"><?= $fetch_fundraiser['name']; ?></div>
      <input type="hidden" name="fid" value="<?= $fetch_fundraiser['id']; ?>">
      <input type="hidden" name="f_name" value="<?= $fetch_fundraiser['name']; ?>">
      <input type="hidden" name="f_price" value="<?= $fetch_fundraiser['price']; ?>">
      <input type="hidden" name="f_image" value="<?= $fetch_fundraiser['image']; ?>">
      <BR>
      <H1><B>₹ ENTER AMOUNT</B></H1>
      <input type="number" min="1" value="1" name="f_amt" class="amt">
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to donate" class="btn" name="add_to_donate">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no fundraiser added yet!</p>';
   }
   ?>

   </div>

</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>