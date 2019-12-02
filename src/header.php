<?php
//function below checks if user is currently logged in and prints the buttons to be able to either login and register or logout and
// see their account information.
    function loginCheck(){
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['ID'])) {
            echo "
            <li>
                <button class='btn btn-success btn-sm'>" . $_SESSION['ID'] . "</button>
            </li>
            <li>
                <form action='../scripts/php/logout.php' method='POST'>
                    <button type='submit' class='btn btn-success btn-sm'>Log Out</button>
                </form>
            </li>
            ";
        } else {
            echo "
            <li>
                <form action='../src/login.php' method='POST'>
                    <button type='submit' class='btn btn-success btn-sm'>Login</button>
                </form>
            </li>
            <li>
                <form action='../src/registration.php' method='POST'>
                    <button type='submit' class='btn btn-success btn-sm'>Register</button>
                </form>
            </li>
            ";
        }
    }
    //The submit button is also subject to the same requirements and is checked whether the user is logged in before making it visible
    function submitGymCheck(){
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['ID'])) {
            echo "
            <a class='nav-link' href='../src/submission.php'>Submit a Gym</a>
            ";
        }
    }
?>

<!-- This division is reversed for a reusable navigation menu with 4 links: register, log in, submit a gym and going to the home page -->
<div class="centered" id="header">
    <!-- HTML5 nav is used along with styling from bootstraps navbar classes. -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top centered">
        <!-- an anchor tag is used to contain a hyperlinked logo for the website -->
        <a class="navbar-brand" href="../src/search.php">
            <!-- the d-inlin-block and align-top classes are from the Bootstrap framework and ensure the image is flush to the left of the
                    navbar -->
            <img src="../images/favicon.png" id="navBrandIcon" alt="Fit Finder" class="d-inline-block align-top">
            Fit Finder
        </a>
        <!-- The submit a gym link is done through a standard navbar anchor tag with Bootstrap styling. mr-auto class ensures that all elements
                added to the right of the anchor tag are flush to the right of the navbar -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <?php
                    submitGymCheck();
                ?>
            </li>
        </ul>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dropdown" aria-controls="dropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="dropdown">
            <!-- Nav buttons are also contained in a similar unordered list. The buttons are styled using bootstraps btn class-->
            <ul id="navButtons" class="nav navbar-nav ml-auto">
                <?php
                    loginCheck();
                ?>
            </ul>
        </div>
    </nav>
</div>