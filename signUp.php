<?php
  require "./require.php";
  if(isset($_SESSION['uid'])){ //returns user to homepage if logged in
	header("Location: ".$web);
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <script type='text/javascript' src='/src/functions.js'></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="./css/signUpStyle.css">
  <link href="https://fonts.googleapis.com/css2?family=Muli:ital,wght@1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
  <script type="text/javascript" src="./js/register.js"></script>
</head>

<body onload="document.body.style.opacity='1'">
  <div class="container">
    <div class="row">
      <div class="parallelogram "></div>
      <div class="col-lg-6 column">
        <img src="/icon/screen.png" alt="screen image" class="animate__animated animate__zoomIn">
        <p class="left animate__animated animate__bounceInUp animate__bounceIn">Let's create<br> something amazing!<br>
          <img src="/icon/icons8-urgent-message-64.png" alt=""></p>
      </div>

      <div class="col-lg-6 column two">
        <div class="header"><img src="/icon/logo-white.png" alt="logo">
          <p id="sign-on-header">Already a member? <a id="sign-on-button" class="btn btn-sm btn-success">Sign
              On</a></p>
        </div>
        <form id="sign-up-form" action="" class="col-lg-11">
          <h3 style="line-height: 3;" class="animate__slideInUp animate__animated ">Sign up with your own username.</h3>
          <div class="inputs">
            <div class="form-group row" style="height: 38px;">
              <div class="form-group col-md-3" style="margin-right: 2px; padding-left: 10px; padding-right: 10px;">
                <input type="text" id="name-register" name="fname" placeholder="First Name"
                  class="form-control inputs animate__animated a">
              </div>
              <div class="form-group col-md-3" style="margin-left: 2px; padding-left: 10px; padding-right: 10px;">
                <input type="text" id="surname-register" name="lname" placeholder="Last Name"
                  class="form-control inputs animate__animated a">
              </div>
            </div>
            <div class="form-group">
              <input type="text" id="username-register" name="username" placeholder="Username"
                class="form-control inputs b">
            </div>
            <!--<div class="form-group">
              <input type="text" id="email-register" name="email" placeholder="Email" class="form-control inputs">
            </div>-->
            <div class="form-group">
              <input type="password" id="password-register" name="password" placeholder="Password"
                class="form-control inputs">
            </div>
            <div class="form-group">
              <input type="date" id="date-register" name="date" placeholder="Date of Birth" class="form-control inputs">
            </div>
          </div>
          <!--<div>
            <p id="copyright-footer"> <input id="user-agreement-button" type="checkbox">
              &#x29BE By creating an account, you agree with our <a href="Msg/Terms"> Terms Of
                Services</a>, <a href="Msg/PrivacyPolicy">Privacy Policy</a>, and our default <a
                href="Msg\.NotificationSettings.html">Notification Settings</a>.</li>
            </p>
          </div>-->
          <br>
          <button type="button" class="btn btn-primary animate__animated animate__bounceIn" id="register-button">SIGN
            UP</button>
          <br>
          <p class="animate__animated animate__zoomIn"> &copy ShareOn</p>
        </form>
      </div>

      <!-- Container div -->
    </div>
  </div>
  <script>
  if(window.location.href.indexOf("#go-to-sign-on") > -1){
    	document.getElementById("sign-on-button").click();
  }
  </script>
</body>

</html>
