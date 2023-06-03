<?php
include 'components/connect.php';

// Check if the user ID and content ID are provided
if(isset($_POST['user_id']) && isset($_POST['content_id'])){
   $user_id = $_POST['user_id'];
   $content_id = $_POST['content_id'];

   // Check if the user ID and content ID are not empty
   if(!empty($user_id) && !empty($content_id)){
      // Prepare and execute the SQL query to check if the attendance record already exists
      $check_attendance = $conn->prepare("SELECT * FROM `attendance` WHERE user_id = ? AND lecture_id = ?");
      $check_attendance->execute([$user_id, $content_id]);

      // Check if the attendance record already exists
      if($check_attendance->rowCount() > 0){
         echo "Attendance already recorded";
      }else{
         // Prepare and execute the SQL query to insert the attendance record
         $insert_attendance = $conn->prepare("INSERT INTO `attendance` (user_id, lecture_id, status, attentiveness) VALUES (?, ?, 'watched', 0)");
         $insert_attendance->execute([$user_id, $content_id]);

         // Check if the attendance record was successfully inserted
         if($insert_attendance->rowCount() > 0){
            echo "Attendance updated successfully";
         }else{
            echo "Failed to update attendance";
         }
      }
   }else{
      echo "Invalid user ID or content ID";
   }
}else{
   echo "User ID and content ID are required";
}
?>
