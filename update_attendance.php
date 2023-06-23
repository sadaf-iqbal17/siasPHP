<?php
include 'components/connect.php';

// Check if the user ID, content ID, and attentiveness value are provided
if(isset($_POST['user_id']) && isset($_POST['content_id']) && isset($_POST['attentiveness'])){
   $user_id = $_POST['user_id'];
   $content_id = $_POST['content_id'];
   $attentiveness = $_POST['attentiveness'];

   // Check if the user ID, content ID, and attentiveness value are not empty
   if(!empty($user_id) && !empty($content_id) && !empty($attentiveness)){
      // Prepare and execute the SQL query to check if the attendance record already exists
      $check_attendance = $conn->prepare("SELECT * FROM `attendance` WHERE user_id = ? AND lecture_id = ?");
      $check_attendance->execute([$user_id, $content_id]);

      // Check if the attendance record already exists
      if($check_attendance->rowCount() > 0){
         // Attendance record already exists, update the attentiveness value
         $update_attendance = $conn->prepare("UPDATE `attendance` SET attentiveness = ? WHERE user_id = ? AND lecture_id = ?");
         $update_attendance->execute([$attentiveness, $user_id, $content_id]);

         // Check if the attendance record was successfully updated
         if($update_attendance->rowCount() > 0){
            echo "Attentiveness updated successfully";
         }else{
            echo "Failed to update attentiveness";
         }
      }else{
         // Attendance record does not exist, insert a new record with the attentiveness value
         $insert_attendance = $conn->prepare("INSERT INTO `attendance` (user_id, lecture_id, status, attentiveness) VALUES (?, ?, 'watched', ?)");
         $insert_attendance->execute([$user_id, $content_id, $attentiveness]);

         // Check if the attendance record was successfully inserted
         if($insert_attendance->rowCount() > 0){
            echo "Attendance updated successfully";
         }else{
            echo "Failed to update attendance";
         }
      }
   }else{
      echo "Invalid user ID, content ID, or attentiveness value";
   }
}else{
   echo "User ID, content ID, and attentiveness value are required";
}
?>
