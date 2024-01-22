<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_fundraiser'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   $old_image = $_POST['old_image'];

   $update_fundraiser = $conn->prepare("UPDATE `fundraiser` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
   $update_fundraiser->execute([$name, $category, $details, $price, $pid]);

   $message[] = 'fundraiser updated successfully!';

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{

         $update_image = $conn->prepare("UPDATE `fundraiser` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);

         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'image updated successfully!';
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update fundraiser</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-fundraiser">

   <h1 class="title">update fundraiser</h1>   

   <?php
      $update_id = $_GET['update'];
      $select_fundraiser = $conn->prepare("SELECT * FROM `fundraiser` WHERE id = ?");
      $select_fundraiser->execute([$update_id]);
      if($select_fundraiser->rowCount() > 0){
         while($fetch_fundraiser = $select_fundraiser->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_fundraiser['image']; ?>">
      <input type="hidden" name="pid" value="<?= $fetch_fundraiser['id']; ?>">
      <img src="uploaded_img/<?= $fetch_fundraiser['image']; ?>" alt="">
      <input type="text" name="name" placeholder="enter fundraiser name" required class="box" value="<?= $fetch_fundraiser['name']; ?>">
      <input type="number" name="price" min="0" placeholder="enter fundraiserfundraiser price" required class="box" value="<?= $fetch_fundraiser['price']; ?>">
      <select name="category" class="box" required>
         <option selected><?= $fetch_fundraiser['category']; ?></option>
         <option value="Heart_Operations">Heart Operations</option>
         <option value="Brain_Surgery">Brain Surgery</option>
         <option value="Accident">Accident</option>
         <option value="All_Treatment">All Treatment</option>
      </select>
      <textarea name="details" required placeholder="enter fundraiser details" class="box" cols="30" rows="10"><?= $fetch_fundraiser['details']; ?></textarea>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <div class="flex-btn">
         <input type="submit" class="btn" value="update fundraiser" name="update_fundraiser">
         <a href="fundraiser.php" class="option-btn">go back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no fundraiser found!</p>';
      }
   ?>

</section>

<script src="js/script.js"></script>
</body>
</html>