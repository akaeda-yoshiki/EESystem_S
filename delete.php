<?php

if(isset($_GET['input_id'])){
    try {
        $db =new PDO('mysql:host=192.168.0.159;dbname=akaeda;','miyashita','sonicdance');

         // DELETE文を変数に格納
        $sqldata = $db->prepare('DELETE FROM data WHERE id=2');//+$_GET['input_id']);
        $sqldata->execute();
        echo 'DELETE FROM data WHERE id=';
        echo $_GET['input_id'];
        // 削除完了のメッセージ
        //変更された行の数が1かどうか
        // if($sqldata->affected_rows == 1){
        //     echo "削除いたしました。";
        // }else{
        //     echo "削除失敗です";
        // }
        $db=null;

        } catch (Exception $e) {
    }
}


?>