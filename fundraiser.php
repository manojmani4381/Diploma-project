<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_fundraiser'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $issue = $_POST['issue'];
   $issue = filter_var($issue, FILTER_SANITIZE_STRING);

   $date = $_POST['date'];
   $date = filter_var($date, FILTER_SANITIZE_STRING);

   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   
   $hospital = $_POST['hospital'];
   $hospital = filter_var($hospital, FILTER_SANITIZE_STRING);

   $phoneno = $_POST['phoneno'];
   $phoneno = filter_var($phoneno, FILTER_SANITIZE_STRING);

   $image2 = $_FILES['image2']['name'];
   $image2 = filter_var($image2, FILTER_SANITIZE_STRING);
   $image2_size = $_FILES['image2']['size'];
   $image2_tmp_name = $_FILES['image2']['tmp_name'];
   $image2_folder = 'uploaded_img2/'.$image2;

   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $select_fundraiser = $conn->prepare("SELECT * FROM `fundraiser` WHERE name = ?");
   $select_fundraiser->execute([$phoneno]);

   if($select_fundraiser->rowCount() > 0){
      $message[] = 'fundraiser phonenumber already exist!';
   }else{

      $insert_fundraiser = $conn->prepare("INSERT INTO `fundraiser`(name, issue, date, category, price, address, image, hospital, phoneno, image2, details ) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
      $insert_fundraiser->execute([$name, $issue, $date, $category, $price, $address, $image, $hospital, $phoneno, $image2, $details]);

      if($insert_fundraiser){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name,$image_folder);
            
         }

      }
      
      if($insert_fundraiser){
         if($image2_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image2_tmp_name,$image2_folder);
            $message[] = 'new fundraiser added!';
         }

   }
}

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `fundraiser` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_fundraiser = $conn->prepare("DELETE FROM `fundraiser` WHERE id = ?");
   $delete_fundraiser->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE fid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_donate = $conn->prepare("DELETE FROM `donate` WHERE fid = ?");
   $delete_donate->execute([$delete_id]);
   header('location:fundraiser.php');


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>fundraiser</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="add-fundraiser">

   <h1 class="title">StartFundraiser</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="Enter Your name">
         <input type="text" name="issue" class="box" required placeholder="What a Issue?">
         Date of Birth
         <input type="date" name="date" class="box" required placeholder="">
           <select name="category" class="box" required>
            <option value="" selected disabled>select category</option>
               <option value="Heart_Operations">Heart Operations</option>
               <option value="Brain_Surgery">Brain Surgery</option>
               <option value="Accident">Accident</option>
               <option value="All_Treatment">All Treatment</option>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="price" class="box" required placeholder="Your Goal Amount">
         <input type="text" name="address" class="box" required placeholder="Address">
         Your Image
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         <input type="text" name="hospital" class="box" required placeholder="Hospital Name">
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="phoneno" class="box" required placeholder="Phone No">
         Doctor Certificate
         <input type="file" name="image2" required class="box" accept="image2/jpg, image2/jpeg, image2/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="enter fundraiser details" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="add fundraiser" name="add_fundraiser">
   </form>

</section>

<section class="show-fundraiser">

   <h1 class="title">StartFundraiser</h1>

   <div class="box-container">

   <?php
      $donation_fundraiser = $conn->prepare("SELECT * FROM `fundraiser`");
      $donation_fundraiser->execute();
      if($donation_fundraiser->rowCount() > 0){
         while($fetch_fundraiser = $donation_fundraiser->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price">$<?= $fetch_fundraiser['price']; ?>/-</div>
      <img src="uploaded_img/<?= $fetch_fundraiser['image']; ?>" alt="">
      <div class="name"><?= $fetch_fundraiser['name']; ?></div>
      <div class="cat"><?= $fetch_fundraiser['category']; ?></div>
      <div class="details"><?= $fetch_fundraiser['details']; ?></div>
      <div class="flex-btn">
         <a href="admin_update_fundraiser.php?update=<?= $fetch_fundraiser['id']; ?>" class="option-btn">update</a>
         <a href="fundraiser.php?delete=<?= $fetch_fundraiser['id']; ?>" class="delete-btn" onclick="return confirm('delete this fundraiser?');">delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">now fundraiser added yet!</p>';
   }
   ?>

   </div>

</section>
<script src="js/script.js"></script>
</body>
</html>