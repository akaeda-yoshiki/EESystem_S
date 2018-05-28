<?php
try {
    $db =new PDO('mysql:host=192.168.0.159;dbname=akaeda;','miyashita','sonicdance');

   $sqldata = $db->prepare('SELECT *FROM user');
   $sqldata->execute();

   while ($row = $sqldata->fetch()) {
        $db_data[] = array(
                'id'=>$row['id'],
                'name'=>$row['name'],
                'grade'=>$row['grade']
                    );
      }

    //responseの準備
    // $response['read_data'] = $db_data;

    //JSONデータ出力
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($db_data);
    $db=null;
  } catch (Exception $e) {
}
?>