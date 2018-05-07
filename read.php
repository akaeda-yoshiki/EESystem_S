<?php
//SQLのデータandroidアプリに送信します。
try {
    $db =new PDO('mysql:host=192.168.0.159;dbname=akaeda;','miyashita','sonicdance');

   $sqldata = $db->prepare('SELECT *FROM data');
   $sqldata->execute();

   while ($row = $sqldata->fetch()) {
        $db_data[] = array(
                'id'=>$row['id']."(id)",
                'name'=>$row['name']."(name)",
                'grade'=>$row['grade']."(grade)"
                    );
      }

    //responseの準備
    $response['read_data'] = $db_data;

    //JSONデータ出力
    // header("Content-type: application/json; charset=UTF-8");
    echo json_encode($response);
    $db=null;

  } catch (Exception $e) {
}
?>