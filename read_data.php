<?php
try {
    $db =new PDO('mysql:host=192.168.0.159;dbname=akaeda;','miyashita','sonicdance');

    $sqldata = $db->prepare("SELECT name, grade, enter_time, exit_time, stay FROM user, data WHERE data.id = user.id");
    $sqldata->execute();
    while ($row = $sqldata->fetch()) {
    $db_data[] = array(
            'name'=>$row['name'],
            'grade'=>$row['grade'],
            'enter_time'=>$row['enter_time'],
            'exit_time'=>$row['exit_time'],
            'stay'=>$row['stay']
                );
    }

    //JSONデータ出力
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($db_data);
    $db=null;
  } catch (Exception $e) {
}
?>