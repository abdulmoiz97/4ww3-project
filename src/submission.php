<!-- 
    Authors: Abdul Moiz & Mohid Makhdoomi
 -->

 <?php

require '../vendor/autoload.php';

use Aws\S3\S3Client; 

include '../../key.php';

$s3 = S3Client::factory(
    array(
        'credentials' => array(
            'key' => KEY,
            'secret' => SECRETKEY,
        ),
        'version' => 'latest',
        'region' => 'us-east-2',
    )
);

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['ID'])) {
        header("Location: https://fitfinder.ga/src/search.php");
    }

    require_once('../../mysaqli_connect.php');

    if (
        isset($_POST['submit']) &&
        ($_SERVER["REQUEST_METHOD"] == "POST")
    ) {   
        $nameErr = $longErr = $latErr = $descErr = "";
        $gymName = $longitude = $latitude = $description = "";
        $validated = true;

        if (empty($_POST["gymName"])) {
            $nameErr = "Gym name is required";
            $validated = false;
        } else {
            $gymName = inputValidation($_POST['gymName']);
        }
        
        if (empty($_POST["longitude"])) {
            $longErr = "longitude is required";
            $validated = false;
        } else {
            $longitude = inputValidation($_POST['longitude']);
        }

        if (empty($_POST["latitude"])) {
            $latErr = "Latitude is required";
            $validated = false;
        } else {
            $latitude = inputValidation($_POST['latitude']);
        }

        if (empty($_POST["description"])) {
            $descErr = "Description is required";
            $validated = false;
        } else {
            $description = inputValidation($_POST['description']);
        }

        if (isset($_FILES['imagefile'])){
            $image = basename($_FILES['imagefile']['name']);
            $file = $_FILES['imagefile']['tmp_name'];
    
            $result = $s3->putObject([
                'Bucket' => 'fitfinder',
                'Key' => $image,
                'SourceFile' => $file,
            ]);
        }
        
        if ($validated) {
            $sql = "INSERT INTO gyms (gymname, avg_rating, longitude, latitude, description,imagekey,gym_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

            // Prepared statements: For when we don't have all the parameters so we store a template to be executed
            $stmnt = $conn->prepare($sql);
    
            try {
                $stmnt->execute([$gymName, NULL, $longitude, $latitude, $description, $image, NULL]);
                $successMsg = "Thank you for your submission!";
            } catch (PDOException $e) {
                $successMsg = "Error adding Gym, please try again.";
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
         <link rel="stylesheet" href="../styles/submission.css">
         <!-- Favicon for browser tab display (aesthetic purposes) -->
         <link rel="icon" href="../images/favicon.png">
         <script src="../scripts/location.js"></script>         
         <!-- Bootstrap CDN to prevent website from looking hideous with approval from TA Yu -->
         <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
         <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />         
         <!-- Google fonts import -->
         <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap" rel="stylesheet">         
         <!-- Title tag for labeling in tab names of browsers -->
         <title>Fit Finder</title>
     </head>
     <!-- Core content of page in body tag -->
     <body>
        
        <?php include 'header.php';?>
        
         <!-- Container of the actual gym submission form is used to style the image around the form for aesthetic purposes. -->
         <div class="centered" id="formContainer">
             <div id="formBox">
                 <!-- Form not given an action as the back end is not part of part 1 -->
                 <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" id="submissionForm" class="rounded">
                     <!-- Similar to the search form a simple bootstrap styled form tag is used. The simplest and logical input type is
                    used for each of the various required inputs. eg. Latitude is number, Description is a larger textarea etc... -->
                     <div class="form-row">
                         <div class="form-group col-md-4">
                             <label for="gymName">Gym Name <span class="validation"><?php 
                            if(isset($nameErr)){
                                echo $nameErr;
                            }
                            ?></span></label>
                             <!-- Required field for validation -->
                             <input type="text" class="form-control" id="gymName" name="gymName"maxlength="50">
                         </div>
                         <div class="form-group col-md-4">
                             <label for="longitude">Longitude <span class="validation"><?php 
                            if(isset($longErr)){
                                echo $longErr;
                            }
                            ?></span></label>
                             <!-- Required field for validation also restricted to numerical inputs only -->
                             <input type="number" name="longitude" step="any" class="form-control" id="longitude">
                         </div>
                         <div class="form-group col-md-4">
                             <label for="latitude">Latitude <span class="validation"><?php 
                            if(isset($latErr)){
                                echo $latErr;
                            }
                            ?></span></label>
                             <!-- Required field for validation also restricted to numerical inputs only -->
                             <input type="number" name="latitude" step="any" class="form-control" id="latitude">
                         </div>
                    </div>
                    <!-- Separate form groups were used to ensure everything is aeshetically laid out. This is done to mimic the layout
                    of an actual hand filled form. -->
                    <div class="form-group">
                        <label for="description">Gym Description <span class="validation"><?php 
                            if(isset($descErr)){
                                echo $descErr;
                            }
                            ?></span></label>
                        <!-- Required field for validation with a max length to prevent spam -->
                        <textarea class="form-control" name="description" id="description" rows="3" maxlength="500"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input name="imagefile" type="file" class="custom-file-input">
                            <label class="custom-file-label" for="imagefile"></label>
                        </div>
                    </div>
                 <div id="buttons">
                    <input id="submitButton" name="submit" type="submit" value="Submit" class="btn btn-success btn-block">
                    <label id="locationButton" onclick="getLocation()" class="btn btn-success"><i class="fa fa-map-marker-alt "></i></label>
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