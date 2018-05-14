<?php

 //ここでは送信されたデータをもとにSQLにデータを挿入してきます。
if(isset($_GET['id']) && isset($_GET['name']) && isset($_GET['grade'])) {
    // if(isset($_POST['ID']) && isset($_POST['NAME']) && isset($_POST['GRADE'])) {


    try{
        // 接続
        $db = new PDO('mysql:host=192.168.0.159;dbname=akaeda', 'miyashita', 'sonicdance');
        echo 'データベース接続成功';
        $write=$db->prepare('INSERT INTO user (ID, name, grade) VALUES(:id, :name, :grade)');
        $write->bindvalue(':id',$_GET['id']);
        $write->bindvalue(':name',$_GET['name']);
        $write->bindvalue(':grade',$_GET['grade']);

        $write->execute();
        // 切断
        $db = null;
        header( "Location: http://192.168.0.159/2018grade4/kaihatu_zemi/akaeda/EESystem_W/modeselect.html" ) ;
    } catch(PDOException $e){
        echo "データベース接続失敗" . PHP_EOL;
        echo $e->getMessage();
        exit;
    }
    
}


?>