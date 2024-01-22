<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

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
      $insert_donate->execute([$user_id, $fid, $f_name, $f_price, $f_qty, $f_image]);
      $message[] = 'added to donate!';
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
   $delete_wishlist_item->execute([$delete_id]);
   header('location:wishlist.php');

}

if(isset($_GET['delete_all'])){

   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>wishlist</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="wishlist">

   <h1 class="title">FundRaiser added</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);
      if($select_wishlist->rowCount() > 0){
         while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="POST" class="box">
      <a href="wishlist.php?delete=<?= $fetch_wishlist['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from wishlist?');"></a>
      <a href="view_page.php?pid=<?= $fetch_wishlist['fid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" alt="">
      <div class="name"><?= $fetch_wishlist['name']; ?></div>
      <div class="price">$<?= $fetch_wishlist['price']; ?>/-</div>

      <input type="number" min="1" value="1" class="amt" name="f_amt">
      <input type="hidden" name="fid" value="<?= $fetch_wishlist['fid']; ?>">
      <input type="hidden" name="f_name" value="<?= $fetch_wishlist['name']; ?>">
      <input type="hidden" name="f_price" value="<?= $fetch_wishlist['price']; ?>">
      <input type="hidden" name="f_image" value="<?= $fetch_wishlist['image']; ?>">
      <input type="submit" value="add to donate" name="add_to_donate" class="btn">
   </form>
   <?php
      $grand_total += $fetch_wishlist['price'];
      }
   }else{
      echo '<p class="empty">your wishlist is empty</p>';
   }
   ?>
   </div>

   <div class="wishlist-total">
      <p>grand total : <span>$<?= $grand_total; ?>/-</span></p>
      <a href="fundraiser list.php" class="option-btn">ADDITIONAL DONATION</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">delete all</a>
   </div>

</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>