<?php
if(isset($_GET['id']) && isset($_GET['pass'])) {
    if($_GET['id'] == "rwe" && $_GET['pass'] == "wblrwewblrwe")
        header( "Location: http://192.168.0.159/2018grade4/kaihatu_zemi/akaeda/EESystem_W/modeselect.html" ) ;
    else
        header( "Location: http://192.168.0.159/2018grade4/kaihatu_zemi/akaeda/EESystem_W/login.html" ) ;
}
?>