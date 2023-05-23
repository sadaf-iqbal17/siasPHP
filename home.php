<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- font awsm script -->
    <script src="https://kit.fontawesome.com/dbb1a1b31e.js" crossorigin="anonymous"></script>
        <!-- bootstrap script -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jquery cdn -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
            <!---font awesome-->
    <script src="https://kit.fontawesome.com/094eaba97f.js" crossorigin="anonymous"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
       <!-- bootstrap css 
       <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">--->


</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner" style="background-image: linear-gradient(to right, rgba(200, 8,207, 0.3), rgba(28, 70, 184, 0.3)), url(images/banner_img.jpg); height:90vh;">
     <div class="column1" style=" ">
       <h1>Turn your ambition into a success story</h1>
       <p class="intro">SIAS provide easy and more effective way to learn by analyzing you all the time during taking your lectures</p>
       <button>Get started</button>
     </div>
     <div class="column2" >
     </div>
   </div>
   



   <!---Testimonials
   

<section id="features">

  <div class="container my-5 sec-features">
    <h1 class="text-center font-weight-bold" style="font-family: 'Roboto Condensed', sans-serif;font-size: 3.5rem; color: #007bff;">Features</h1>
    <div class="d-flex flex-wrap justify-content-center mt-lg-5">
      <div class="card mx-3 my-3 shadow" style="width: 18rem;">
        <div class="card-body d-flex align-items-center" style="height: 150px;">
          <i class="fas fa-user-circle fa-7x mx-auto" style="color: #007bff;"></i>
        </div>
        <div class="card-body">
          <h5 class="card-title text-center font-weight-bold">User-friendly interface</h5>
          <p class="card-text text-center">Our website offers a modern, user-friendly interface that makes it easy to use and navigate.</p>
        </div>
      </div>
  
      <div class="card mx-3 my-3 shadow" style="width: 18rem;">
        <div class="card-body d-flex align-items-center" style="height: 150px;">
          <i class="fas fa-robot fa-7x mx-auto" style="color: #007bff;"></i>
        </div>
        <div class="card-body">
          <h5 class="card-title text-center font-weight-bold">AI-powered analysis</h5>
          <p class="card-text text-center">Our website uses artificial intelligence and computer vision to analyze student attentiveness during video lectures.</p>
        </div>
      </div>
  
      <div class="card mx-3 my-3 shadow" style="width: 18rem;">
        <div class="card-body d-flex align-items-center" style="height: 150px;">
          <i class="fas fa-chart-line fa-7x mx-auto" style="color: #007bff;"></i>
        </div>
        <div class="card-body">
          <h5 class="card-title text-center font-weight-bold">Real-time reports</h5>
          <p class="card-text text-center">Our website provides real-time reports and monitoring of student attentiveness, allowing teachers to track progress and improvement.</p>
        </div>
      </div>
  
      <div class="card mx-3 my-3 shadow" style="width: 18rem;">
        <div class="card-body d-flex align-items-center" style="height: 150px;">
          <i class="fas fa-cog fa-7x mx-auto" style="color: #007bff;"></i>
        </div>
        <div class="card-body">
          <h5 class="card-title text-center font-weight-bold">Customizable settings</h5>
          <p class="card-text text-center">Our website allows users to customize their settings and create a personalized experience tailored to their needs.</p>
        </div>
      </div>
  
    
      <div class="card mx-3 my-3 shadow" style="width: 18rem;">
        <div class="d-flex justify-content-center">
          <i class="fas fa-lock fa-5x mt-3" style="color: #6c757d;"></i>
        </div>
        <div class="card-body">
          <h5 class="card-title text-center font-weight-bold">Secure data storage</h5>
          <p class="card-text text-center">Our website uses secure servers and data encryption to ensure that your data is safe and protected.</p>
        </div>
      </div>
      </div>
    </div>
  </section> --->
  
<section class="quick-select">
<h3 class="about-us" style=" margin-bottom:100px;   border-bottom: var(--border); ">Testimonials</h3>

   <div class="box-container">
    <div class="box testimonial-style" style=" padding:35px 30px 25px 30px;">
   <center> <i class="fa-sharp fa-solid fa-gear" style="color: #ffffff; font-size:5em"></i></center>
          <h3  class="about-us" style="color:white; font-size:2rem;  font-weight:900 !important" >User Friendly Interface</h3>
<p style="color:white ;font-size:1.5em;">Our website offers a modern, user-friendly interface that makes it easy to navigate</p>
    </div>

    <div class="box testimonial-style" style=" padding:35px 30px 30px 30px; ">
    <center> <i class="fa-sharp fa-solid fa-users-viewfinder" style="color: #ffffff; font-size:5em"></i></center>
<h3  class="about-us" style=" color:white;font-size:2rem; font-weight:900 !important" >AI-powered analysis</h3>
<p style="color:white ;font-size:1.5em;">Our website uses artificial intelligence and computer vision to analyze student attentiveness during video lectures</p>
    </div>

<div class="box testimonial-style" style="padding:60px 30px 40px 30px; ">
   <center> <i class="fa-sharp fa-solid fa-magnifying-glass-chart fa-2xl" style="color: #ffffff; font-size:5em"></i></center>
        <h3  class="about-us" style="color:white;font-size:2rem; font-weight:900  !important" >Real-time reports</h3>
<p style="color:white ;font-size:1.5em;">Our website provides real-time reports and monitoring of student attentiveness, allowing teachers to track progress.</p>
</div>

   <div class="box testimonial-style" style="color:white; padding:60px 30px 40px 30px; ">
   <center> <i class="fa-sharp fa-solid fa-user-lock fa-2xl" style="color: #ffffff; font-size:5em"></i></center>
        <h3  class="about-us" style="color:white;font-size:2rem; font-weight:900  !important" >Secure data storage</h3>
<p style="color:white ;font-size:1.5em;">Our website uses secure servers and data encryption to ensure that your data is safe and protected.</p>
   

   </div>

</section>
<!-- quick select section starts  -->

<section class="quick-select">

<h1 class="about-us" style=" margin-bottom:100px;   border-bottom: var(--border); ">Quick Options</h1>

   <div class="box-container">

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">likes and comments</h3>
         <p>total likes : <span><?= $total_likes; ?></span></p>
         <a href="likes.php" class="inline-btn">view likes</a>
         <p>total comments : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">view comments</a>
         <p>saved Courses : <span><?= $total_bookmarked; ?></span></p>
         <a href="bookmark.php" class="inline-btn">view bookmark</a>
      </div>
      <?php
         }else{ 
      ?>
      <div class="box" style="text-align: center;">
         <h3 class="title">please login or register</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>
      <?php
      }
      ?>

      <div class="box">
         <h3 class="title">top categories</h3>
         <div class="flex">
            <a href="search_course.php?"><i class="fas fa-code"></i><span>development</span></a>
            <a href="#"><i class="fas fa-chart-simple"></i><span>business</span></a>
            <a href="#"><i class="fas fa-pen"></i><span>design</span></a>
            <a href="#"><i class="fas fa-chart-line"></i><span>marketing</span></a>
            <a href="#"><i class="fas fa-music"></i><span>music</span></a>
            <a href="#"><i class="fas fa-camera"></i><span>photography</span></a>
            <a href="#"><i class="fas fa-cog"></i><span>software</span></a>
            <a href="#"><i class="fas fa-vial"></i><span>science</span></a>
         </div>
      </div>

      <div class="box">
         <h3 class="title">popular topics</h3>
         <div class="flex">
            <a href="#"><i class="fab fa-html5"></i><span>HTML</span></a>
            <a href="#"><i class="fab fa-css3"></i><span>CSS</span></a>
            <a href="#"><i class="fab fa-js"></i><span>javascript</span></a>
            <a href="#"><i class="fab fa-react"></i><span>react</span></a>
            <a href="#"><i class="fab fa-php"></i><span>PHP</span></a>
            <a href="#"><i class="fab fa-bootstrap"></i><span>bootstrap</span></a>
         </div>
      </div>

      <div class="box tutor">
         <h3 class="title">become a tutor</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa, laudantium.</p>
         <a href="admin/register.php" class="inline-btn">get started</a>
      </div>

   </div>

</section>

<!-- quick select section ends -->

<!-- courses section starts  -->

<section class="courses">

<h3 class="about-us" style=" margin-bottom:100px;   border-bottom: var(--border); ">Latest Courses</h3>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">view Courses</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no courses added yet!</p>';
      }
      ?>

   </div>

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">view more</a>
   </div>

</section>

<!-- courses section ends -->


<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>