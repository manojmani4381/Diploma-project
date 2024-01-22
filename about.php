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
   <title>about</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="row">

      <div class="box">
         <img src="images/donate.jpg" alt="">
         <h3>why choose us?</h3>
         <p>At LAMANI, we believe that nobody should have to forgo their second chance at
         life due to insufficient funds.Instead, you can choose Medical Fundraising to cover
         your treatment costs and get medical help at the earliest.No matter how well you plan
         ahead, a medical emergency is always unexpected.Moreover, affording quality healthcare
         can easily take a toll on your savings and cause financial stress to you and your family.
</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

      <div class="box">
         <img src="images/donation.jpeg" alt="">
         <h3>what we donation?</h3>
         <p>A donation is a gift for charity, humanitarian aid, or to benefit a cause.
             A donation may satisfy medical needs such as blood or organs for transplant .
             Charitable donations of goods or services are also called gifts in kind.</p>
         <a href="donation.php" class="btn">our donations</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">clients reivews</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/manoj.jpeg" alt="">
         <p>Really this is my future plan to donating this peoples,And I will be good donar.Hats'of to this developers.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Manoj</h3>
      </div>

      <div class="box">
         <img src="images/lalith.jpeg" alt="">
         <p>I get this information from my friend sharing,And it provides my family from this corona problems.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Lalith</h3>
      </div>

      <div class="box">
         <img src="images/naveen.jpeg" alt="">
         <p>For my father's operation i can't get money from any other policy or persons.It saves his life.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Naveen</h3>
      </div>

      <div class="box">
         <img src="images/palanivel.jpeg" alt="">
         <p>I really happy for giving oppertunity.And,It should be more payment options.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Palanivel</h3>
      </div>

      <div class="box">
         <img src="images/dhaarani.png" alt="">
         <p>I completed this register with simple steps,And it is more secure.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Dhaarani</h3>
      </div>

      <div class="box">
         <img src="images/nithya.png" alt="">
         <p>I got this through my freind,I was happy after getting this.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Nithya</h3>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>