<!-- 
    Authors: Abdul Moiz & Mohid Makhdoomi
 -->
 <?php
 //If session isnt started start it
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }      
//global variable to only require sql query for gym information once.
    $gymInfo;
//If a post request is sent from the submission form, perform sql query and insert new review to db.
    if (
        isset($_POST['submit']) &&
        ($_SERVER["REQUEST_METHOD"] == "POST")
    ) {
        require('../../mysaqli_connect.php');

        //Input validation done on all inputs
        $rating = inputValidation($_POST['rating']);

        $review = inputValidation($_POST['review']);

        $sql = "INSERT INTO reviews (review, rating, reviewer, gym) VALUES (?,?,?,?)";
        // Prepared statements: For when we don't have all the parameters so we store a template to be executed
        $stmnt = $conn->prepare($sql);

        try {
            $stmnt->execute([$review, $rating, $_SESSION['ID'], $_GET['id']]);
            updateRating();
        } catch (PDOException $e) {
            echo "Failed to add review! Please try again!";
        }
    }

    //Current reviews for the selected gym are displayed to the user
    function pullReviews(){
        require('../../mysaqli_connect.php');
        $sql = "Select review,rating,reviewer from reviews where gym=?";
        $stmnt = $conn->prepare($sql);
        
        try {
            $stmnt->execute([$_GET['id']]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        if ($stmnt->rowCount() == 0){
            echo "
            <li>
                <strong> No reviews yet! Be the first to leave a review.</strong>
            </li>
            ";
        }
        //Simply prints all the available reviews for the selected gym referencing gym_id from GET header.
        while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
            echo "
            <li>
            <img src='../images/user.png' alt=''>
            <div class='details'>
                <div><strong>" . $row['reviewer'] . "</strong></div>
                <div>" . $row['rating'] . " <i class='fas fa-star'></i></div>
                <p>" . $row['review'] . "</p>
            </div>
            </li>
            ";
        }
    }

    // The gym's details are selected from db and saved to global variable
    function pullGymDetails(){
        require('../../mysaqli_connect.php');

        $sql = "Select gymname,avg_rating,description,longitude,latitude from gyms where gym_id=?";
        $stmnt = $conn->prepare($sql);
        
        try {
            $stmnt->execute([$_GET['id']]);
            $row = $stmnt->fetchAll();
            $GLOBALS['gymInfo'] = $row[0];
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //Allows for the dynamic printing of the reviews via the sql query in the function above and some formatting.
    function printDetails($row){
        if ($row['avg_rating'] == NULL) {
            echo "<h1 class='display-4'><strong>" . $row['gymname'] . " (No Ratings Yet) </strong></h1>";
        } else {
            echo "<h1 class='display-4'><strong>" . $row['gymname'] . " (" . $row['avg_rating'] . " <i class='fas fa-star'></i>) </strong></h1>";
        }

        if (isset($_SESSION['ID'])){
            echo "<button id='reviewBtn' class='btn btn-warning btn-lg' data-toggle='modal' data-target='#reviewModal'><strong>Leave A Review!</strong></button>";
        }
    }

    //Each time a review is submitted it must be updated for the selected gym in the db as the average rating has now gone up
    function updateRating(){
        $numOfReviews = 0.0;
        $sumOfReviews = 0.0;

        require('../../mysaqli_connect.php');
        $sql = "Select rating from reviews where gym=?";
        $stmnt = $conn->prepare($sql);
        
        try {
            $stmnt->execute([$_GET['id']]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
            $numOfReviews += 1;
            $sumOfReviews += $row['rating'];
        }

        $sql = "UPDATE gyms SET avg_rating=ROUND($sumOfReviews/$numOfReviews,2) WHERE gym_id=?";

        $stmnt = $conn->prepare($sql);
        
        try {
            $stmnt->execute([$_GET['id']]);
        } catch (PDOException $e) {
            echo $e->getMessage();
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
         <!-- Links to stylesheets -->
         <link rel="stylesheet" href="../styles/common.css">
         <link rel="stylesheet" href="../styles/individual_sample.css">
         <!-- Favicon for browser tab display (aesthetic purposes) -->
         <link rel="icon" href="../images/favicon.png">
         <!-- Bootstrap CDN to prevent website from looking hideous with approval from TA Yu -->
         <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
         <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
         <!-- Google fonts import -->
         <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap" rel="stylesheet">    
         <!-- Title tag for labeling in tab names of browsers -->         
         <title>Fit Finder</title>
     </head>
    <!-- Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?id=" . $_GET['id']; ?>" method="POST" id="submissionForm">
                    <div class="form-rw">
                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <select name="rating" id="rating" class="form-control" required>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-rw">
                        <div class="form-group">
                            <label for="review">Review</label>
                            <textarea class="form-control" name="review" id="review" rows="3" maxlength="500" required></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button name="submit" type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
            </div>
        </div>
    </div>
     <body>
        <?php include 'header2.php'; ?>

        <!-- Below div is where the gym details are contained. A Bootstrap container is used along with the grid system. This allows for easy
        scaling of web page when viewport size is adjusted. Each row presents elements that are to be put side by side, a cut off width is prescriped
        via bootstrap to ensure columns cascade vertically -->
        <div id="gymContainer" class="container">
            <div class="row">
                <div class="col">
                    <!-- Bootstrap jumbotron used to present gym name and details -->
                    <div id="gymHeader" class="jumbotron jumbotron-fluid">
                        <div class="container">
                            <?php
                                pullGymDetails();
                                printDetails($GLOBALS['gymInfo']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <!-- The gym details below are hard coded for part 1 -->
                    <p id="gymDetails"> <?php echo $GLOBALS['gymInfo']['description'] ?> </p>
                    <h2>Reviews</h2>
                    
                    <!-- Reviews are listed using an unumbered list and line items with no bullet points. Within each line item is the name of the reviewer,
                    rating and comments on the location. Different lines of the review are separated into Divs to allow for varying styles within the element -->
                    <ul id="reviews">
                        <?php
                            pullReviews();
                        ?>
                    </ul>
                </div>
                <div id="map" class="col-lg-4">
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>

     </body>
    <!-- Script tag for google maps API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJaN3HEdiPTG4Kh1gW0A2spA4-CbB52vk&callback=initMap"></script>
    <script>
        var data = <?php echo json_encode($GLOBALS['gymInfo']); ?>;
        var latitude = data['latitude'];
        var longitude = data['longitude'];
        function initMap() {
                // New map is generated in the map div
                var map = new google.maps.Map(
                document.getElementById('map'), {zoom: 14, center: {lat: latitude, lng: longitude}});
                
                // Marker drawn on map for individual result
                let marker = new google.maps.Marker({position: {lat: latitude, lng: longitude}, map: map, title: data['gymname']});
                
                // Details of gym saved to variable to be presented
                var contentString = '<div><a>' + data['gymname'] + '</a></div>';

                // Popup box on marker to show user details about gym on map
                var infoWindow = new google.maps.InfoWindow({content: contentString});
            
                // Click listeners added to markers on map to show pop up when clicked.
                marker.addListener('click', function() {
                    infoWindow.open(map, marker);
                });
            }
        console.log(data);
    </script> 
    </html>