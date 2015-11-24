<?php
	include_once('header.php');
?>


         <form name="login"  method="post" action="login.php">
            <div>
               Enter Phone of user : <input name="phone" type="phone" /><br /><br />
               <input type="submit"  id= "buttonLogin" name="buttonLogin" value="Login" />
               <input type="submit"  id= "buttonSignUp" name="buttonSignUp" value="Sign Up" />	
            </div>
         </form>

<?php
	include_once('footer.php');
?>
