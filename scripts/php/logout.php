<?php 

    $_SESSION = array();

    // remove all session variables
    session_unset();

    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    
    // destroy the session
    session_destroy();
    header("Location: https://fitfinder.ga/src/search.php");
?>
