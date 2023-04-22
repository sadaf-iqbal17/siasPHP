<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email']; 
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number']; 
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg']; 
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_contact->execute([$name, $email, $number, $msg]);

   if($select_contact->rowCount() > 0){
      $message[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->execute([$name, $email, $number, $msg]);
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- contact section starts  --->
<div class="banner" style="background-image:  linear-gradient(to right, rgba(200, 8,207, 0.3), rgba(28, 70, 184, 0.2)), url(images/ca.jpg);height:82vh;">

   </div>
   <h1 class="about-us">Contact Us</h1>
<section class="contact">

   <div class="row">
   <form action="" method="post">
         <h3>Get in Touch</h3>
         <input type="text" placeholder="enter your name" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="enter your email" required maxlength="100" name="email" class="box">
         <input type="text" min="0" max="9999999999" placeholder="enter your number" required maxlength="10" name="number" class="box">
         <textarea name="msg" class="box" placeholder="enter your message" required cols="30" rows="10" maxlength="1000"></textarea>
         <input type="submit" value="send message" class="inline-btn" style="background-color: var(--oragen);" name="submit">
      </form>
      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

   </div>
   

</section>
<section class="map_divs">
<div class="container">
    <div class="contact-details">
      <div class="left-side">
        <div class="address details">
          <i class="fas fa-map-marker-alt"></i>
          <div class="topic">Address</div>
          <div class="text-one">COMSATS University Islamabad Sahiwal campus.</div>
        </div>
        <div class="phone details">
          <i class="fas fa-phone-alt"></i>
          <div class="topic">Phone</div>
          <div class="text-one">+0098 9893 5647</div>
        </div>
        <div class="email details">
          <i class="fas fa-envelope"></i>
          <div class="topic">Email</div>
          <div class="text-two">sadafiqbal2001@gmail.com</div>
        </div>
      </div>
      <div class="right-side">
        <div class="mapouter"><div class="gmap_canvas"><iframe width="900" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=sahiwal&t=&z=10&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://2yu.co">2yu</a><br><style>.mapouter{position:relative;text-align:right;height:500px;width: 900px;;}</style><a href="https://embedgooglemap.2yu.co">html embed google map</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:900px;}</style></div></div>

 
    </div>
  </div>
        </section>
<!---
<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-8">
          <div class="mapouter"><div class="gmap_canvas"><iframe width="770" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=sahiwal&t=&z=10&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://2yu.co">2yu</a><br><style>.mapouter{position:relative;text-align:right;height:500px;width:770px;}</style><a href="https://embedgooglemap.2yu.co">html embed google map</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:770px;}</style></div></div>

			</div>
			<div class="col-6 col-md-4">
			
          <div class="box-container info " style="display:block !important">

            <div class="box" id="contact-div" >
               <i class="fas fa-phone" ></i>
               <h3>Phone number</h3>
               <a href="tel:1234567890">123-456-7890</a>
      
            </div>
          
      
            <div class="box"id="contact-div" >
               <i class="fas fa-envelope" ></i>
               <h3>Email address</h3>
               <a href="mailto:shaikhanas@gmail.com">sadafiqbal2001@@gmail.com</a>
            </div>
      
            <div class="box" id="contact-div" >
               <i class="fas fa-map-marker-alt"></i>
               <h3>Office address</h3>
               <a href="#">COMSATS University Islamabad Sahiwal Campus</a>
            </div>
            </div>
				</div>
			</div>
		</div>--->
<!---
<div class="container">
  <div class="row aa">
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="mapouter">
        <div class="gmap_canvas">
          <br>
          <style>.mapouter{position:relative;text-align:right;height:500px;width:770px;}</style>
          <a href="https://embedgooglemap.2yu.co">html embed google map</a>
          <style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:770px;}</style>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 box-container">
      <div class="box" id="contact">
        <i class="fas fa-phone" ></i>
        <h3>phone number</h3>
        <a href="tel:1234567890">123-456-7890</a>
      </div>

      <div class="box" id="contact">
        <i class="fas fa-envelope" ></i>
        <h3>email address</h3>
        <a href="mailto:shaikhanas@gmail.com">sadafiqbal2001@@gmail.com</a>
      </div>

      <div class="box" id="contact">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Office address</h3>
        <a href="#">COMSATS University Islamabad Sahiwal Campus</a>
      </div>
    </div>
  </div>
</div>
--->
<!-- contact section ends -->











<?php include 'components/footer.php'; ?>  

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>