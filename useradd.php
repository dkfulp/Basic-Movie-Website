<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/20/2016
 * Time: 1:36 PM
 */?>
<?php

$pagetitle = "User Registration Form";
include_once "header.php";

$errormsg = "";
$showform = 1;

if(isset($_POST['submit']))
{
    /* ****************************************************************************
       CREATE NEW VARS TO STORE USER DATA & SANITIZE DATA FROM USER.
       ************************************************************************* */
    $formfield['fname'] = trim($_POST['fname']);
    $formfield['mi'] = trim($_POST['mi']);
    $formfield['lname'] = trim($_POST['lname']);
    $formfield['email'] = trim(strtolower($_POST['email']));
    $formfield['uname'] = trim(strtolower($_POST['uname']));
    $formfield['pwd'] = trim($_POST['pwd']); //we never force a lowercase password
    $formfield['pwd2'] = trim($_POST['pwd2']); //we never force a lowercase password
    $formfield['address1'] = trim($_POST['address1']);
    $formfield['address2'] = trim($_POST['address2']);
    $formfield['city'] = trim($_POST['city']);
    $formfield['state'] = trim($_POST['state']);
    $formfield['zip'] = trim($_POST['zip']);
    /* ****************************************************************************
        CHECK FOR EMPTY FIELDS
       ************************************************************************* */
    if(empty($formfield['fname'])){$errormsg .= "<p>The first nam is empty.</p>";}
    if(empty($formfield['lname'])){$errormsg .= "<p>The last name is empty.</p>";}
    if(empty($formfield['email'])){$errormsg .= "<p>The email is empty.</p>";}
    if(empty($formfield['uname'])){$errormsg .= "<p>The username is empty.</p>";}
    if(empty($formfield['pwd'])){$errormsg .= "<p>The password is empty.</p>";}
    if(empty($formfield['pwd2'])){$errormsg .= "<p>The confirmation password is empty.</p>";}
    if(empty($formfield['address1'])){$errormsg .= "<p>The primary address field is empty.</p>";}
    if(empty($formfield['city'])){$errormsg .= "<p>There is no city.</p>";}
    if(empty($formfield['state'])){$errormsg .= "<p>No state was never declared.</p>";}
    if(empty($formfield['zip'])){$errormsg .= "<p>No zipcode was entered.</p>";}

    /*  ****************************************************************************
          CHECK FOR MATCHING PASSWORDS
        **************************************************************************** */
    if($formfield['pwd'] != $formfield['pwd2']){$errormsg .= "<p>The passwords do not match.</p>";}

    /* ****************************************************************************
          CHECK FOR DUPLICATE USERS
       ************************************************************************* */
    try
    {
        $sqlusers = "SELECT * FROM dkfulp_users WHERE uname = :uname";
        $stmtusers = $pdo->prepare($sqlusers);
        $stmtusers->bindValue(':uname', $formfield['uname']);
        $stmtusers->execute();
        $countusers = $stmtusers->rowCount();
        if ($countusers > 0)
        {
            $errormsg .= "<p>The username is already taken.</p>";
        }
    }
    catch (PDOException $e)
    {
        echo "<div class='error'><p></p>ERROR selecting users!" .$e->getMessage() . "</p></div>";
        exit();
    }

    /* ****************************************************************************
          CHECK FOR DUPLICATE EMAILS
           ************************************************************************* */
    try
    {
        $sqlemail = "SELECT * FROM dkfulp_users WHERE email = :email";
        $sqlemail = $pdo->prepare($sqlemail);
        $sqlemail->bindValue(':email', $formfield['email']);
        $sqlemail->execute();
        $countemails = $sqlemail->rowCount();
        if ($countemails > 0)
        {
            $errormsg .= "<p>The email is already taken.</p>";
        }
    }
    catch (PDOException $e)
    {
        echo "<div class='error'><p></p>ERROR selecting emails!" .$e->getMessage() . "</p></div>";
        exit();
    }

    /*  ****************************************************************************
        HASH THE PASSWORD
           **************************************************************************** */

    $alphabet = "./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for ($i = 0; $i < 22; $i++) {
        $chars .= substr($alphabet, mt_rand(0, 63), 1);
    }
    $salt = '$2a$10$' . $chars;
    $securepwd = crypt($formfield['pwd'],$salt);


    /*  ****************************************************************************
        CONTROL FOR ERRORS.  IF ERRORS, DISPLAY THEM.  IF NOT, CONTINUE WITH FORM PROCESSING.
        **************************************************************************** */
    if($errormsg != "")
    {
        echo "<div class='error'><p>THERE ARE ERRORS!</p>";
        echo $errormsg;
        echo "</div>";
    }
    else
    {
        try
        {
            $sql = "INSERT INTO dkfulp_users (fname, mi, lname, email, uname, pwd, address1, address2, city, state, zip, salt)
								   VALUES (:fname, :mi, :lname, :email, :uname, :pwd, :address1, :address2, :city, :state, :zip, :salt)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindvalue(':fname', $formfield['fname']);
            $stmt->bindvalue(':mi', $formfield['mi']);
            $stmt->bindvalue(':lname', $formfield['lname']);
            $stmt->bindvalue(':email', $formfield['email']);
            $stmt->bindvalue(':uname', $formfield['uname']);
            $stmt->bindvalue(':pwd', $securepwd);
            $stmt->bindvalue(':address1', $formfield['address1']);
            $stmt->bindvalue(':address2', $formfield['address2']);
            $stmt->bindvalue(':city', $formfield['city']);
            $stmt->bindvalue(':state', $formfield['state']);
            $stmt->bindvalue(':zip', $formfield['zip']);
            $stmt->bindvalue(':salt', $salt);
            $stmt->execute();
            $showform=0; //hide the form
            echo "<div class='success'><p>There are no errors.  Thank you.  Go to <a href='userlist.php'>User Directory</a></p></div>";
        }//try
        catch(PDOException $e)
        {
            echo "<div class='error'><p>ERROR inserting data into the database!" .$e->getMessage() . "</p></div>";
            exit();
        }
    }//else errors
}//isset submit

if($showform == 1) {
    ?>
    <form method="post" action="useradd.php" name="registration">
        <fieldset>
            <legend>User Registration</legend>
            <table>
                <tr>
                    <th><label for="fname">First Name:</label></th>
                    <td><input type="text" name="fname" id="fname" value="<?php if (isset($formfield['fname'])){echo $formfield['fname'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="mi">Middle Initial:</label></th>
                    <td><input type="text" name="mi" id="mi" maxlength="1" size="3" value="<?php if (isset($formfield['mi'])){echo $formfield['mi'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="lname">Last Name:</label></th>
                    <td><input type="text" name="lname" id="lname" value="<?php if (isset($formfield['lname'])){echo $formfield['lname'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="email">Email:</label></th>
                    <td><input type="email" name="email" id="email" value="<?php if (isset($formfield['email'])){echo $formfield['email'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="uname">Username:</label></th>
                    <td><input type="text" name="uname" id="uname" value="<?php if (isset($formfield['uname'])){echo $formfield['uname'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="pwd">Password:</label></th>
                    <td><input type="password" name="pwd" id="pwd" /></td>
                </tr>
                <tr>
                    <th><label for="pwd2">Confirm Password:</label></th>
                    <td><input type="password" name="pwd2" id="pwd2"></td>
                </tr>
                <tr>
                    <th><label for="address1">Primary Address Field:</label></th>
                    <td><input type="text" name="address1" id="address1" value="<?php if (isset($formfield['address1'])){echo $formfield['address1'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="address2">Secondary Address Field:</label></th>
                    <td><input type="text" name="address2" id="address2" value="<?php if (isset($formfield['address2'])){echo $formfield['address2'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="city">City:</label></th>
                    <td><input type="text" name="city" id="city" value="<?php if (isset($formfield['city'])){echo $formfield['city'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="state">State:</label></th>
                    <td><input type="text" name="state" id="state" maxlength="2" size="5" value="<?php if (isset($formfield['state'])){echo $formfield['state'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="zip">Zip Code:</label></th>
                    <td><input type="text" name="zip" id="zip" maxlength="5" size="5" value="<?php if (isset($formfield['zip'])){echo $formfield['zip'];} ?>"/></td>
                </tr>
                <tr>
                    <th>Submit:</th>
                    <td><input type="submit" name="submit" value="submit"/></td>
                </tr>
            </table>
        </fieldset>
    </form>

    <img id="picture" src="images/newuser.jpg" alt="newuser" >
    <?php
} //showform
include_once "footer.php";
?>