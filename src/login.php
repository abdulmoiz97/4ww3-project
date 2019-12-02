<!-- 
    Authors: Abdul Moiz & Mohid Makhdoomi
 -->

<?php

 //If session isnt started start it

    if (!isset($_SESSION)) {
        session_start();
    }

    //mysql connection file kept separate for security purposes
    require_once('../../mysaqli_connect.php');

    //login script with full input verification.
    if (
        isset($_POST['submit']) && ($_SERVER["REQUEST_METHOD"] == "POST")
    ) {
        $userErr = $passErr = "";
        $username = $password = "";
        $validated = true;

        if (empty($_POST["username"])) {
            $userErr = "Username is required";
            $validated = false;
        } else {
            $username = inputValidation($_POST['username']);
        }

        if (empty($_POST["password"])) {
            $passErr = "Password is required";
            $validated = false;
        } else {
            $password = inputValidation($_POST['password']);
        }

        if ($validated) {
            $sql = "Select * from users where username=? and password=?";

            $stmnt = $conn->prepare($sql);
            try {
                $stmnt->execute([$username, $password]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
    
            $rows = $stmnt->fetchAll();
    
            // If there is only one user
            if (count($rows) == 1) {
                // Setting the session to the returned user ID.
                $_SESSION['ID'] = $rows[0]['username'];
                // Redirect to table of users.
                header("Location: https://fitfinder.ga/src/search.php");
            } else {
                $successMsg = "Login Failed";
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
    <link rel="stylesheet" href="../styles/login.css">
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

    <?php include 'header.php'; ?>

    <!-- form container used to ensure styling can be kept separate on the form and its background -->
    <div class="centered" id="formContainer">
        <div id="formBox">
            <!-- Bootstrap forms are used for aesthetic appeal although functionality is still native HTML5 -->
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" id="loginForm" class="rounded">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <!-- Simple text box input for username -->
                        <label for="username">Username <span class="validation"><?php 
                            if(isset($userErr)){
                                echo $userErr;
                            }
                            ?></span></label>
                        <input type="text" class="form-control" name="username" id="username" maxlength="20">
                    </div>
                    <div class="form-group col-md-6">
                        <!-- password input is used for password field -->
                        <label for="password">Password <span class="validation"><?php 
                            if(isset($passErr)){
                                echo $passErr;
                            }
                            ?></span></label>
                        <input type="password" class="form-control" name="password" id="password" maxlength="12">
                    </div>
                </div>
                <div id="submitButton">
                    <button name="submit" type="submit" class="btn btn-success btn-block">Log In</button>
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

    <?php include 'footer.php'; ?>

</body>

</html>