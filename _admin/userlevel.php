<?php
    session_start();
    if($_SESSION['userlevel'] != "sadmin_df56fdg")
    {
        header("location:https://www.easthyde.com?val=timeout");
    }

    include("../assets/custom/connect.php");
    include("../assets/custom/fy_access.php");
    if(isset($_SESSION['username']) && isset($_SESSION['userlevel'])){
        fy_set_session_for_user($db, $_SESSION['username'], $_SESSION['userlevel']);
    }
?>