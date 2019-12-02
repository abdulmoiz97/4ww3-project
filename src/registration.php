<!-- 
    Authors: Abdul Moiz & Mohid Makhdoomi
 -->

 <?php

 //sqli connection file separate for security
    require_once('../../mysaqli_connect.php');

    //if post request is sent from the registration form, validate all inputs using empty checks and regex and then add to db
    if (
        isset($_POST['submit']) &&
        ($_SERVER["REQUEST_METHOD"] == "POST")
    ) {
        $userErr = $emailErr = $passErr = $genderErr = $ageErr = "";
        $username = $email = $password = $gender = $age =  "";
        $validated = true;
        
        if (empty($_POST["username"])) {
            $userErr = "Username is required";
            $validated = false;
        } else {
            $username = inputValidation($_POST['username']);
            if (!preg_match("/^[a-zA-Z ]*$/",$username)) {
                $validated = false;
                $userErr = "Only letters and white space allowed";
              }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
            $validated = false;
        } else {
            $email = inputValidation($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validated = false;
            $emailErr = "Invalid email format";
            }
        }

        if (empty($_POST["password"])) {
            $passErr = "Password is required";
            $validated = false;
        } else {
            $password = inputValidation($_POST['password']);
            if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,12}$/",$password)) {
                $validated = false;
                $passErr = "Password must be 6 to 12 characters with at least one numeric digit, one uppercase and one lowercase letter";
              }
            else if ($_POST["password"] != $_POST["password2"]) {
                $validated = false;
                $passErr = "Passwords must match!";
            }
        }

        if (empty($_POST["genderOptions"])) {
            $genderErr = "Please select";
            $validated = false;
        } else {
            $gender = inputValidation($_POST["genderOptions"]);
        }

        if ($_POST["ageRange"] == "Age") {
            $ageErr = "Please select";
            $validated = false;
        } else {
            $age = inputValidation($_POST["ageRange"]);
        }

        if ($validated){
            $sql = "INSERT INTO users (username, emailaddress, password, gender, agerange) VALUES (?, ?, ?, ?, ?)";
            
            // Prepared statements: For when we don't have all the parameters so we store a template to be executed
            $stmnt = $conn->prepare($sql);

            try {
                $stmnt->execute([$username, $email, $password, $gender, $age]);
                $successMsg = "Thank you for signing up!";
            } catch (PDOException $e) {
                $userErr = "Username already in use";
            }
        }
    }

    function inputValidation($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
      }
?>

 <!-- Standard HTML-5 Boilerplate -->
 <!DOCTYPE html>
 <!-- Root element with language declaration -->
 <html lang="en">
     <!-- Meta data container -->
     <head>
         <!-- Meta data to ensure compatability with varying devices, viewports and character sets -->
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta http-equiv="X-UA-Compatible" content="ie=edge">
         <link rel="stylesheet" href="../styles/common.css">
         <link rel="stylesheet" href="../styles/registration.css">
         <script src="../scripts/registration.js"></script>         
         <!-- Favicon for browser tab display (aesthetic purposes) -->
         <link rel="icon" href="../images/favicon.png">
         <!-- Bootstrap CDN to prevent website from looking hideous with approval from TA Yu -->
         <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
         <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
         <!-- Google fonts import -->
         <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap" rel="stylesheet">         
         <!-- Title tag for labeling in tab names of browsers -->
         <title>Fit Finder</title>
     </head>

     <!-- Core content of page in body tag -->
     <body>
         
        <?php include 'header.php';?>
        
        <!-- form container used to ensure styling can be kept separate on the form and its background -->
         <div class="centered" id="formContainer">
             <div id="formBox">
                 <!-- Bootstrap forms are used for aesthetic appeal although functionality is still native HTML5 -->
                 <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" id="registrationForm" class="rounded">
                     <div class="form-row">
                         <div class="form-group col-md-6">
                             <!-- Simple text box input for username -->
                             <label for="username">Username <span class="validation"><?php 
                            if(isset($userErr)){
                                echo $userErr;
                            }
                            ?></span></label>
                             <input type="text" class="form-control" name="username" id="username">
                         </div>
                         <div class="form-group col-md-6">
                             <!-- email input is used for email address field -->
                             <label for="email">Email Address <span class="validation"><?php 
                            if(isset($emailErr)){
                                echo $emailErr;
                            }
                            ?></span></label>
                             <input type="email" class="form-control" name="email" id="email">
                         </div>
                     </div>
                     <div class="form-row">
                        <div class="form-group col-md-6">
                            <!-- Password input specifically used to ensure entries are confidential -->
                            <label for="password">Password <span class="validation"><?php 
                            if(isset($passErr)){
                                echo $passErr;
                            }
                            ?></span></label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password2">Confirm Password</label>
                            <input type="password" class="form-control" name = "password2" id="password2">
                        </div>
                    </div>

                    <!-- Below are form elements to ensure atleast 4 are used. along with the varied number of input types -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <!-- The select form item is used with Bootstrap styling. -->
                            <span class="validation"><?php 
                            if(isset($ageErr)){
                                echo $ageErr;
                            }
                            ?></span>
                            <select name="ageRange" class="form-control">
                                <option>Age</option>
                                <option value="16-25">16-25</option>
                                <option value="26-35">26-35</option>
                                <option value="36-45">36-45</option>
                                <option value="46-55">46-55</option>
                                <option value="56-65">56-65</option>
                                <option value="66-75">66-75</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                        <span class="validation"><?php 
                            if(isset($genderErr)){
                                echo $genderErr;
                            }
                            ?></span>
                            <!-- Below is a set of radio buttons to complete the 4 form types requirement -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="genderOptions" id="male" value="Male">
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="genderOptions" id="female" value="Female">
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="genderOptions" id="other" value="Other" >
                                <label class="form-check-label" for="other">Other</label>
                            </div>
                        </div>
                    </div>
                 <div id="submitButton">
                     <input name="submit" type="submit" class="btn btn-success btn-block"></input>
                     <!-- <label onclick="validate()" class="btn btn-success btn-block">Register</label> -->
                 </div>
                
                <?php 
                    if(isset($successMsg)){
                        echo "
                        <div class = 'success'>
                            <span>" . $successMsg . "</span>
                        </div>";
                    }
                ?>
                 </form>
             </div>
         </div>
 
        <?php include 'footer.php';?>

     </body>

 </html>