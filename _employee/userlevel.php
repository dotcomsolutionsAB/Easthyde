<?php
    session_start();
    if($_SESSION['userlevel'] != "employee_jhkFNDdd")
    {
        header("location:https://easthyde.in?val=timeout");
    }

    include("../assets/custom/connect.php");
    include("../assets/custom/fy_access.php");
    if(isset($_SESSION['username']) && isset($_SESSION['userlevel'])){
        $fySession = fy_set_session_for_user($db, $_SESSION['username'], $_SESSION['userlevel']);
        if(!$fySession[0]){
            session_unset();
            header("location:https://easthyde.in?val=timeout");
        }
    }
?>