<!-- 
    Authors: Abdul Moiz & Mohid Makhdoomi
 -->
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
        <link rel="stylesheet" href="../styles/search.css">
        <!-- Favicon for browser tab display (aesthetic purposes) -->
        <link rel="icon" href="../images/favicon.png">
        <!-- JS file link -->
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

        <!-- Container of the actual search form is used to style the image around the form for aesthetic purposes. -->
        <div class="centered" id="formContainer">
            <div id="formBox">
                <!-- The search form currently is hard coded to link to the results_samples.html page -->
                <!-- Bootstrap forms used for aesthetics -->
                <form id="searchForm" action="../src/results_sample.php" method="post" class="rounded">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <!-- Simple text input used for user to be able to search by name -->
                            <label for="gymName">By Gym Name</label>
                            <input type="text" class="form-control" name="gymName" id="gymName">
                        </div>
                        <div class="form-group col-md-6">
                            <!-- As requested user can also search by rating. This was accomplished using a Bootstrap styled select tag to create
                            a drop down menu -->
                            <label for="byRating">By Rating</label>
                            <select name="rating" id="byRating" class="form-control">
                                <option value="0"> - </option>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="longitude">Longitude</label>
                            <input type="number" id="longitude" name="longitude" step="any" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="latitude">Latitude</label>
                            <input type="number" name="latitude" step="any" class="form-control" id="latitude" required>
                        </div>
                    </div>
                <!-- The search button simply triggers the action indicated at the start of the form tag -->
                <div id="buttons">
                    <button id="searchButton" name="submit" type="submit" class="btn btn-success btn-block">Search</button>
                    <label id="locationButton" onclick="getLocation()" class="btn btn-success"><i class="fa fa-map-marker-alt "></i></label>
                </div>
                </form>
            </div>
        </div>

        <?php include 'footer.php';?>
    </body>
</html>