<?php session_start(); ?>
<html>
   <head>
      <title>ITMO 544 Imagica App</title>
   </head>
   <body>
      <h3>
         <center> Welcome to Imagica Application </center>
      </h3>
      <div><br/></div>
      <center>
         <form name="login"  method="post" action="login.php">
            <div>
               Enter Phone of user : <input name="phone" type="phone" /><br /><br />
               <input type="submit"  id= "buttonLogin" name="buttonLogin" value="Login" />
               <input type="submit"  id= "buttonSignUp" name="buttonSignUp" value="Sign Up" />	
            </div>
         </form>
      </center>
      </center>
   </body>
</html>