<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
 } else {
    $tutor_id = '';
    header('location:login.php');
 }
 
 if (isset($_GET['user_id']) && isset($_GET['playlist_id'])) {
    $user_id = $_GET['user_id'];
    $playlist_id = $_GET['playlist_id'];
 } else {
    header('location:playlist.php');
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Student Details</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>


<section class="contents">
   <h1 class="heading"></h1>
   <div class="box-container">
   <?php
      $select_videos = $conn->prepare("SELECT c.*, a.status FROM `content` c LEFT JOIN `attendance` a ON c.id = a.lecture_id AND a.user_id = ? WHERE c.tutor_id = ? AND c.playlist_id = ?");
      $select_videos->execute([$user_id, $tutor_id, $playlist_id]);
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
      <div class="box">
         <img src="../uploaded_files/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>
         <h2>Status: <?= $fecth_videos['status'] ? 'Watched' : 'Not Watched'; ?></h2>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no videos added yet! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">Lecture has No videos</a></p>';
      }
   ?>
   </div>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>