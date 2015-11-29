<?php
	include_once('header.php');
?>


<h3>
   <center> Signup for ITMO 544 Imagica App </center>
</h3><br/>
<h2>
   <center> Enter User Details </center>
</h2>
<center>
   <div style="min-height:300px;padding-left:20%;padding-right:20%;padding-top:5%">
      <form name="signup" action="insert.php" method="POST">
         <div>
            Enter User Name: <input name="name" type="text" /><br />
         </div>
         <div><br/></div>
         <div>
            Enter Email of user: <input name="email" type="email" ><br />
         </div>
         <div><br/></div>
         <div>
            Enter Phone of user (Prefix with 1): <input name="phone" type="phone" >
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
   </div>
</center>



<?php
	include_once('footer.php');
?>