<?php

if(isset($_GET['id'])) {

//SQLのデータandroidアプリに送信します。
try {
    $db =new PDO('mysql:host=192.168.0.159;dbname=akaeda;','miyashita','sonicdance');

//    $sqldata = $db->prepare('SELECT *FROM data WHERE id = '+$_GET['id']);
$sqldata = $db->prepare('SELECT *FROM data WHERE id = 5');
   $sqldata->execute();

   while ($row = $sqldata->fetch()) {
        $db_data[] = array(
                'id'=>$row['id'],
                'name'=>$row['name'],
                'grade'=>$row['grade']
                    );
      }

    //responseの準備
    $response['read_data'] = $db_data;

    //JSONデータ出力
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($db_data);
    // echo json_encode(array(
    //     "status" => 1, // 処理結果(今回は1なら正常という意味とする)
    //     "result" => array( // アプリ側に返却したいがあればresult以下にセットすることとする
            // "userList" => array(
            //     array("id" => "1", "name"=>"ユーザー1")))));
    $db=null;
    // header( "Location: http://192.168.0.159/2018grade4/kaihatu_zemi/akaeda/EESystem_W/modeselect.html" ) ;
  } catch (Exception $e) {
}
}
?>