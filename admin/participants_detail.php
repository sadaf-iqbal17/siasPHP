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
   <style>
      html,
      body {
         height: 100%;
      }

      body {
         margin: 0;
         background: linear-gradient(90deg, rgba(142, 68, 173, 1) 47%, rgba(91, 89, 215, 1) 100%);
         font-family: sans-serif;
         font-weight: 100;
      }

      .container {
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
      }

      table {
         margin: 50px auto;
         width: 800px;
         border-collapse: collapse;
         overflow: hidden;
         box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
         border-radius: 10px;
         transition: transform 0.3s;
      }

      th,
      td {
         padding: 15px;
         background-color: rgba(255, 255, 255, 0.2);
         font: 15px "Roboto", sans-serif;
         color: #fff;
      }

      th {
         font: bold 25px "Roboto", sans-serif;
         border-bottom: 1px solid #fff;
         text-align: left;
      }

      thead {
         th {
            background-color: #55608f;
         }
      }

      th:hover,
      td:hover {
         background-color: rgba(0, 0, 0, 0.5);
      }

      table:hover {
         transform: scale(1.1);
         /* Zoom in the table on hover */
      }
   </style>
</head>

<body>

   <?php include '../components/admin_header.php'; ?>
   <?php
   $select_videos = $conn->prepare("SELECT c.*, a.status, a.attentiveness FROM `content` c LEFT JOIN `attendance` a ON c.id = a.lecture_id AND a.user_id = ? WHERE c.tutor_id = ? AND c.playlist_id = ?");
   $select_videos->execute([$user_id, $tutor_id, $playlist_id]);
   if ($select_videos->rowCount() > 0) {
      ?>
      <div class="table-responsive">
         <table>
            <tr>
               <th>Title</th>
               <th>Thumbnail</th>
               <th>Status</th>
               <th>Attentiveness</th>
            </tr>
            <?php
            while ($fetch_videos = $select_videos->fetch(PDO::FETCH_ASSOC)) {
               $video_id = $fetch_videos['id'];
               ?>
               <tr>
                  <td>
                     <?= $fetch_videos['title']; ?>
                  </td>
                  <td><img src="../uploaded_files/<?= $fetch_videos['thumb']; ?>" class="thumb" alt=""
                        style="width: 100px; height: 50px;"></td>
                  <td>
                     <?= $fetch_videos['status'] ? 'Watched' : 'Not Watched'; ?>
                  </td>
                  <td>
                     <?= $fetch_videos['attentiveness']; ?>
                  </td>
               </tr>
               <?php
            }
            ?>
         </table>
      </div>
      <?php
   } else {
      echo '<p class="empty">No videos added yet! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">Lecture has no videos</a></p>';
   }
   ?>

   <?php include '../components/footer.php'; ?>

   <script src="../js/admin_script.js"></script>

</body>

</html>