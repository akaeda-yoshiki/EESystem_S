<?php

 //ここでは送信されたデータをもとにSQLにデータを挿入してきます。
if(isset($_GET['id'])) {
    if(isset($_GET['time'])) {//入退室データを入力


        try{
            // 接続
            $db = new PDO('mysql:host=192.168.0.159;dbname=akaeda', 'miyashita', 'sonicdance');
            // echo 'データベース接続成功<br />';

            $sqldata = $db->prepare("SELECT *FROM user WHERE id=?");
            $sqldata->execute(array($_GET['id']));
         
            while ($row = $sqldata->fetch()) {
                 $data[] = array(
                         'id'=>$row['id'],
                         'name'=>$row['name'],
                         'grade'=>$row['grade'],
                         'stats'=>""
                             );
               }


            $sqldata = $db->prepare("SELECT id, enter_time FROM now_enter WHERE id = $_GET[id]");
            $sqldata->execute();
              while ($row = $sqldata->fetch()) {
                $db_data[] = array(
                        'id'=>$row['id'],
                        'enter_time'=>$row['enter_time']
                            );
              }
        
            // echo $db_data;
            if(empty($db_data[0]["id"])){
                $write=$db->prepare('INSERT INTO now_enter (id, enter_time) VALUES(:id, :enter_time)');
                $write->bindvalue(':id',$_GET['id']);
                $write->bindvalue(':enter_time',$_GET['time']);
                $write->execute();
                $data[0]['stats'] = "1";
                // echo "新規追加";
            }
            else{
                $write=$db->prepare('INSERT INTO data (id, enter_time, exit_time, stay) VALUES(:id, :enter_time, :exit_time, :stay)');
                $write->bindvalue(':id',$_GET['id']);
                $write->bindvalue(':enter_time',$db_data[0]["enter_time"]);
                $write->bindvalue(':exit_time',$_GET['time']);
                $write->bindvalue(':stay',0);
                $write->execute();
                // echo "累積データへ<br />";

                $sqldata = $db->prepare("DELETE FROM now_enter WHERE id = $_GET[id]");
                $sqldata->execute();
                $data[0]['stats'] = "0";

                // echo json_encode($db_data);
                // echo "削除";
            }

            //JSONデータ出力
            header("Content-type: application/json; charset=UTF-8");
            echo json_encode($data);
                
            // 切断
            $db = null;
            // header( "Location: http://192.168.0.159/2018grade4/kaihatu_zemi/akaeda/EESystem_W/modeselect.html" ) ;
        } catch(PDOException $e){
            // echo "データベース接続失敗" . PHP_EOL;
            echo $e->getMessage();
            exit;
        }
    }
    else if(isset($_GET['name']) && isset($_GET['grade'])){//新規ユーザ登録
        try{
            // 接続
            $db = new PDO('mysql:host=192.168.0.159;dbname=akaeda', 'miyashita', 'sonicdance');
            echo 'データベース接続成功';
            $write=$db->prepare('INSERT INTO user (id, name, grade) VALUES(:id, :name, :grade)');
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
}
echo calculate_staytime("2018_4_15_16:50", "2018_4_15_17:58");

    //滞在時間の計算
    function calculate_staytime($enter, $exit){
    $enter = explode("_", $enter);
    $exit = explode("_", $exit);
    // echo $enter;
    $enter_time = explode(":", $enter[3]);
    $exit_time = explode(":", $exit[3]);

    $stay_time = array("", "", "", "", "");
    $enter = array($enter[0], $enter[1], $enter[2], $enter_time[0], $enter_time[1]);
    $exit = array($exit[0], $exit[1], $exit[2], $exit_time[0], $exit_time[1]);
    for($i = 0; $i < 5; $i++){
        if($enter[$i] < $exit[$i]){//
            $stay_time[$i] = $exit[$i] - $enter[$i];
        }
        else{//繰り下げ計算

        }
    }
    
    $word = array("年間", "月間", "日間", "時間", "分間");
    $return_word = "";
    for($i = 0; $i < 5; $i++)
        if($stay_time[$i] != ""){
            $return_word .= $stay_time[$i];
            $return_word .= $word[$i];
        }
    return $return_word;
}

function calculate_support(){

}

?>