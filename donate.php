<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_donate_item = $conn->prepare("DELETE FROM `donate` WHERE id = ?");
   $delete_donate_item->execute([$delete_id]);
   header('location:donate.php');
}

if(isset($_GET['delete_all'])){
   $delete_donate_item = $conn->prepare("DELETE FROM `donate` WHERE user_id = ?");
   $delete_donate_item->execute([$user_id]);
   header('location:donate.php');
}

if(isset($_POST['update_amt'])){
   $donate_id = $_POST['donate_id'];
   $f_amt = $_POST['f_amt'];
   $f_amt = filter_var($f_amt, FILTER_SANITIZE_STRING);
   $update_amt = $conn->prepare("UPDATE `donate` SET amount = ? WHERE id = ?");
   $update_amt->execute([$f_amt, $donate_id]);
   $message[] = 'donate amount updated';
   header('location:donate.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>choosing donate</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="choosing-donate">

   <h1 class="title">fundraiser added</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_donate = $conn->prepare("SELECT * FROM `donate` WHERE user_id = ?");
      $select_donate->execute([$user_id]);
      if($select_donate->rowCount() > 0){
         while($fetch_donate = $select_donate->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="POST" class="box">
      <a href="donate.php?delete=<?= $fetch_donate['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from donate?');"></a>
      <a href="view_page.php?fid=<?= $fetch_donate['fid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_donate['image']; ?>" alt="">
      <div class="name"><?= $fetch_donate['name']; ?></div>
      <div class="price">$<?= $fetch_donate['price']; ?>/-</div>
      <input type="hidden" name="donate_id" value="<?= $fetch_donate['id']; ?>">
      <div class="flex-btn">
      <BR>
      <H1><B>₹ ENTER AMOUNT</B></H1>
      <BR>
      <input type="number" min="1" value="<?= $fetch_donate['amount']; ?>" class="amt" name="f_amt">
      <input type="submit" value="update" name="update_amt" class="option-btn">
      </div>
      <div class="sub-total"> sub total : <span>$<?= $sub_total = ($fetch_donate['amount'] ); ?>/-</span> </div>
   </form>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">your donate is empty</p>';
   }
   ?>
   </div>

   <div class="donate-total">
      <p>grand total : <span>$<?= $grand_total; ?>/-</span></p>
      <a href="fundraiser list.php" class="option-btn">ADD DONATION </a>
      <a href="donate.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">delete all</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">NEXT PAGE</a>
   </div>

</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>