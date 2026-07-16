<?php
session_start();
session_unset();
session_destroy();

header("location:https://www.easthyde.com?val=signout");
exit();
?>