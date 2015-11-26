<?php
	include_once('header.php');
?>

<div style="min-height:300px;padding-left:20%;padding-right:20%;padding-top:5%">
         <form name="login"  method="post" action="login.php">
            <div style="padding-left:20%;padding-right:20%;padding-top:10%;padding-bottom:10%">
               Enter Phone of user : <input name="phone" type="phone" /><br /><br />
               <input type="submit"  id= "buttonLogin" name="buttonLogin" value="Login" />
               <input type="submit"  id= "buttonSignUp" name="buttonSignUp" value="Sign Up" />
               </br></br>
               <a href="introspection.php">Backup Database</a>	
               <a href="gallery.php">View our gallery</a>	
            </div>
         </form>
</div>

<?php 
	include_once('footer.php');
?>
