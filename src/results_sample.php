<!-- 
    Authors: Abdul Moiz & Mohid Makhdoomi
 -->

<?php

//S3 client used for hosting of image files
require '../vendor/autoload.php';

use Aws\S3\S3Client;  

$gymInfo = array();
$latitude;
$longitude;

//When a search query is sent search the database for all gyms and dynamically load to the screen.
    function searchDatabase(){   
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
        
        if (
            isset($_POST['submit']) &&
            ($_SERVER["REQUEST_METHOD"] == "POST"))
            {
                $Lat = $_POST['latitude'];
                $GLOBALS['latitude'] = $Lat;
                $Long = $_POST['longitude'];
                $GLOBALS['longitude'] = $Long;
                $searchField = "";
                if (!empty($_POST['gymName'])){
                    $sql = "Select gymname,avg_rating,longitude,latitude,description,gym_id,imagekey,( 6371 * acos( cos( radians($Lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($Long) ) + sin( radians($Lat) ) * sin(radians(latitude)) ) ) AS distance from gyms where gymname=? HAVING distance < 50 ORDER BY distance";
                    $searchField = $_POST['gymName'];
                } elseif ($_POST['rating']!=0){
                    $sql = "Select gymname,avg_rating,longitude,latitude,description,gym_id,imagekey,( 6371 * acos( cos( radians($Lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($Long) ) + sin( radians($Lat) ) * sin(radians(latitude)) ) ) AS distance from gyms where avg_rating>=? HAVING distance < 50 ORDER BY distance";
                    $searchField = $_POST['rating'];
                }
                require('../../mysaqli_connect.php');
                $stmnt = $conn->prepare($sql);
                
                try {
                    $stmnt->execute([$searchField]);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
    
                if ($stmnt->rowCount() == 0){
                    echo "
                    <li>
                        <strong> Search returned no results! </strong>
                    </li>
                    ";
                }
                $resultNum = 0;
                
                while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
                    $resultNum += 1;
                        array_push($GLOBALS['gymInfo'], $row);
                        $cmd = $s3->getCommand('GetObject', [
                            'Bucket' => 'fitfinder',
                            'Key' => $row['imagekey']
                        ]);
                    
                        $request = $s3->createPresignedRequest($cmd, '+20 minutes');
                    
                        // Get the actual presigned-url
                        $presignedUrl = (string)$request->getUri();
                    
                    //script generates the tabular formatting of the results from the database search.
                    echo "
                        <li>
                            <img src='" . $presignedUrl ."' alt=''>
                            <div class='details'>
                            <div><strong> <a href='../src/individual_sample.php?id=" . $row['gym_id'] . "'>" . $resultNum . ". " . $row['gymname'] ."</a></strong></div>
                            <div>" . $row['avg_rating'] ." <i class='fas fa-star'></i></div>
                            <p>" . $row['description'] . "</p>
                            </div>
                        </li>
                        ";
                }
            }
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
         <link rel="stylesheet" href="../styles/results_sample.css">
         <!-- Favicon for browser tab display (aesthetic purposes) -->
         <link rel="icon" href="../images/favicon.png">
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

     <body>

        <?php include 'header2.php'; ?>

        <!-- Below div is where the results are contained. A Bootstrap container is used along with the grid system. This allows for easy
        scaling of web page when viewport size is adjusted. Each row presents elements that are to be put side by side, a cut off width is prescriped
        via bootstrap to ensure columns cascade vertically -->
        <div id="resultsContainer" class="container">
            <div class="row">
                <div class="col">
                    <!-- Separate columns are used in the same row so the tabular results and the map are side by side. This allows users to simultaneously
                    use both features. -->
                    <!-- The tabular results are simply presented in an unumbered list with no bullet points. -->
                    <ul id="tabular">
                        <?php searchDatabase(); ?>
                    </ul>
                </div>
                <!-- Div created to contain the live google maps instance -->
                <div id="map" class="col-md-5">
                    <!-- Image is hard coded for part 1 -->
                    <!-- <img id="googleMaps" src="../images/googleMaps.PNG" class="img-responsive" alt="Sample Map"> -->
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>

     </body>
    <!-- Google Maps API link -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJaN3HEdiPTG4Kh1gW0A2spA4-CbB52vk&callback=initMap"> </script>
    <script>
        var data = <?php echo json_encode($GLOBALS['gymInfo']); ?>;
        var userLat = Number(<?php echo json_encode($GLOBALS['latitude']); ?>);
        var userLong = Number(<?php echo json_encode($GLOBALS['longitude']); ?>);
        markerArray = [];
        contentString = [];
        infoWindows  = [];

        // The following function is called after the dependancies are loaded via the script tag in the HTML
        function initMap() {

            // New map is generated in the map div
            if(data.length == 1){
                userLat = parseFloat(data[0]['latitude']);
                userLong = parseFloat(data[0]['longitude'])
            }
            var map = new google.maps.Map(
            document.getElementById('map'), {zoom: 9, center: {lat: userLat, lng: userLong}});
            
            //The map markers are dynamically generated from the global variable in the php script.
            if(data.length > 0){
                for(var i = 0;  i < data.length; i++){
                markerArray.push(new google.maps.Marker({position: {lat: parseFloat(data[i]['latitude']), lng: parseFloat(data[i]['longitude'])}, map: map, title: data[i]['gymname'], icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+(i+1)+'|FE6256|000000'}));
                contentString.push('<div><a href="../src/individual_sample.php?id=' + data[i]['gym_id'] +'">'+ data[i]['gymname'] +'</a></div>');
                infoWindows.push(new google.maps.InfoWindow({ content: contentString[i]}));
                }
            }

            //event listeners added to the markers
            for (let i = 0; i < markerArray.length; i++) {
            markerArray[i].addListener('click', function() {
                infoWindows[i].open(map, markerArray[i]);
            });
            }
        }
    </script>
</html>