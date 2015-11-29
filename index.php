<?php
	include_once('header.php');
?>

<div style="min-height:300px;padding-left:20%;padding-right:20%;padding-top:5%">
   <form name="login"  method="post" action="login.php">
      <div style="padding-left:20%;padding-right:20%;padding-top:10%;padding-bottom:10%">
         Enter Phone of user (Prefix with 1) : <input name="phone" type="phone" /><br /><br />
         <input type="submit"  id= "buttonLogin" name="buttonLogin" value="Login" />
         <input type="submit"  id= "buttonSignUp" name="buttonSignUp" value="Sign Up" />
         </br></br>
         <a href="gallery.php" style="color:Teal;font-family:'Salsa';font-style:cursive;font-size:70%;font-weight:bold;">View our gallery</a>
         </br></br>
         <a href="introspection.php" style="color:Teal;font-family:'Salsa';font-style:cursive;font-size:70%;font-weight:bold;">Backup Database</a>
      </div>
   </form>
</div>

<?php 
	include_once('footer.php');
?>
