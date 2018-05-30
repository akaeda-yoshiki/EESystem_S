<?php
try {
    $db =new PDO('mysql:host=192.168.0.159;dbname=akaeda;','miyashita','sonicdance');

    $sqldata = $db->prepare("SELECT name, grade, enter_time, enter_time1 FROM user, now_enter WHERE now_enter.id = user.id");
    $sqldata->execute();
    while ($row = $sqldata->fetch()) {
    $db_data[] = array(
            'name'=>$row['name'],
            'grade'=>$row['grade'],
            'enter_time'=>$row['enter_time'],
            'enter_time1'=>$row['enter_time1']
          );
    }

    //JSONデータ出力
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($db_data);
    $db=null;
  } catch (Exception $e) {
}
?>