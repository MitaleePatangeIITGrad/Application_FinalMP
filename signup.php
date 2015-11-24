<?php
	include_once('header.php');
?>
      <h3> Signup for ITMO 544 Imagica App  </h3>
      <div><br/></div>
      <h2><center> Enter User Details </center></h2>
      <center>
         <!-- The data encoding type, enctype, MUST be specified as below -->
         <form name="signup" action="insert.php" method="POST">
            <div>
               Enter User Name: <input name="name" type="text" /><br />
            </div>
            <div><br /></div>
            <div>
               Enter Email of user: <input name="email" type="email" ><br />
            </div>
            <div><br/></div>
            <div>
               Enter Phone of user: <input name="phone" type="phone" >
            </div>
            <div><br/></div>
            Do you want to subscribe for notifications via phone?:
            <select name="subscription" id="subscription">
               <option value="Y">Yes</option>
               <option value="N">No</option>
            </select>
            <br><br>
            <div><br/></div>
            <input type="submit" id="buttonSignUp" name="buttonSignUp" value="Sign Up" />
         </form>
      </center>

<?php
	include_once('footer.php');
?>