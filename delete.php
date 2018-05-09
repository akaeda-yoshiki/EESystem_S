<?php

if(isset($_GET['input_id'])){
    try {
        $db =new PDO('mysql:host=192.168.0.159;dbname=akaeda;','miyashita','sonicdance');

        $id = $_GET['input_id'] - '0';
         // DELETE文を変数に格納
        $sqldata = $db->prepare("DELETE FROM data WHERE id=?");
        $sqldata->execute(array($_GET['input_id']));
        $db=null;
        header( "Location: http://192.168.0.159/2018grade4/kaihatu_zemi/akaeda/EESystem_W/modeselect.html" ) ;
        } catch (Exception $e) {
    }
}


?>