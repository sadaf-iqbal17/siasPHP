<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>teachers</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      /* CSS */
      .t-text{
         margin : 10px;
      } 
      .banner {
         position: relative;
         height: 80vh;
         background: rgb(209,31,202);
         background: linear-gradient(90deg, rgba(209,31,202,1) 29%, rgba(0,212,255,1) 100%); /* Purple gradient background */
      }

      .overlay {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: transparent;
         border: 2px solid white; /* Border style */
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Shadow style */
      }

      .box.offer {
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         background-color: rgba(255, 255, 255, 0.5); /* Transparent background color */
         border-radius: 20px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Shadow style */
         padding: 20px;
         text-align: center;
         width: 300px;
      }

   </style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- teachers section starts  -->
<div class="banner">
   <div class="overlay"></div> <!-- Transparent div overlay -->
   <div class="box offer">
      <h1 class="t-text">Become a tutor</h1>
      <a href="admin/login.php" class="inline-option-btn"  >Login</a>
      <a href="admin/register.php" class="inline-option-btn">Register</a>
   </div>
   <img src="images/teacher.png "  style="width:35%;margin-bottom:40px;" alt="">

   <img class="teacher2"src="images/teacher2.png" style="height:95%; margin-top:15px; margin-left:30%; position:relative; float:right;" alt="">
</div>



<section class="teachers">

<h3 class="about-us" style=" margin-bottom:100px;   border-bottom: var(--border); ">Expert Tutors</h3>

   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="search tutor..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>

   <div class="box-container">

      <!-- <div class="box offer">
         <h3>Become a tutor</h3>
         <p>Inorder to upload your content as a teacher Register here</p>
         <a href="admin/login.php" class="inline-btn">Login as Techer</a>
         <a href="admin/register.php" class="inline-btn">Register as Techer</a>
      </div> -->
      

      <?php
         $select_tutors = $conn->prepare("SELECT * FROM `tutors`");
         $select_tutors->execute();
         if($select_tutors->rowCount() > 0){
            while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){

               $tutor_id = $fetch_tutor['id'];

               $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
               $count_playlists->execute([$tutor_id]);
               $total_playlists = $count_playlists->rowCount();

               $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
               $count_contents->execute([$tutor_id]);
               $total_contents = $count_contents->rowCount();

               $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
               $count_likes->execute([$tutor_id]);
               $total_likes = $count_likes->rowCount();

               $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
               $count_comments->execute([$tutor_id]);
               $total_comments = $count_comments->rowCount();
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_tutor['profession']; ?></span>
            </div>
         </div>
         <p>Courses : <span><?= $total_playlists; ?></span></p>
         <p>total videos : <span><?= $total_contents ?></span></p>
         <p>total likes : <span><?= $total_likes ?></span></p>
         <p>total comments : <span><?= $total_comments ?></span></p>
         <form action="tutor_profile.php" method="post">
            <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
            <input type="submit" value="view profile" name="tutor_fetch" class="inline-btn">
         </form>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">no tutors found!</p>';
         }
      ?>

   </div>

</section>

<!-- teachers section ends -->


<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>