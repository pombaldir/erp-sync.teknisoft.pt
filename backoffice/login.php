<?php include_once 'include/db_connect.php'; include_once 'include/functions.php'; sec_session_start();

if(isset($_GET['act'])){
$act=mysqli_real_escape_string($mysqli,$_GET['act']);
} else {$act="";}




if($act=="logout"){
addLog("Logout");
// Unset all session values 
$_SESSION = array();
// get session parameters 
$params = session_get_cookie_params();
// Delete the actual cookie. 
setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
// Destroy session 
session_destroy();
header("Location: ".URLBASE."/login");
exit();
}
 
if (login_check($mysqli) == true) {
    $logged = 'iniciada';
} else {
    $logged = 'terminada';
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo config_val('empresa');?> | Iniciar Sessão</title>

    <!-- Bootstrap -->
    <link href="<?php echo URLBASE;?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo URLBASE;?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo URLBASE;?>/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo URLBASE;?>/vendors/animate.css/animate.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo URLBASE;?>/build/css/custom.min.css" rel="stylesheet">
    
	<!-- jQuery -->
    <script src="<?php echo URLBASE;?>/vendors/jquery/dist/jquery.min.js"></script>
  
    <script type="text/javascript" src="<?php echo URLBASE;?>/build/js/forms.js"></script>
  	<script type="text/JavaScript" src="<?php echo URLBASE;?>/build/js/sha512.js"></script>
	<script type="text/javascript" src="<?php echo URLBASE;?>/build/js/login.js?v2"></script>
	<script>
	$(document).ready(function(){
	"use strict";
	Login.init(); // Init login JavaScript
		
		
	$( "#loginbtn" ).click(function() {
	formhash(loginform, document.getElementById("password"));	
	});		

		
	});
	</script>
    
    
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
 <form id="loginform"  action="login_do.php" method="post">
               <h1>Iniciar Sessão</h1>
              <div>
                <input name="username" id="username" type="text" class="form-control" placeholder="Utilizador" required="" />
              </div>
              <div>
                <input name="password" id="password" type="password" class="form-control" placeholder="Password" />
              </div>
              <div>
                <input type="submit" class="btn btn-default submit" id="loginbtn" value="Log In"></input>
                <a class="reset_pass" href="#">Esqueceu a password?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
              
              
              <span id="msglogin"></span>
               <!--
                <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>
              

                <div class="clearfix"></div>
                <br />  -->

                <div>
                  <h1><i class="fa fa-database"></i> <?php echo config_val('empresa');?></h1>
                  <p>©<?php echo date('Y');?> Todos os direitos reservados | Pombaldir.com</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form>
              <h1>Create Account</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="text" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input  type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <a class="btn btn-default submit" href="index.html">Submit</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                  <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
   
</html>
