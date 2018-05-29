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
        
            if(empty($db_data[0]["id"])){//入室
                $write=$db->prepare('INSERT INTO now_enter (id, enter_time) VALUES(:id, :enter_time)');
                $write->bindvalue(':id',$_GET['id']);
                $write->bindvalue(':enter_time',$_GET['time']);
                $write->execute();
                $data[0]['stats'] = "1";
            }
            else{//退室
                //累積データへ入力
                $write=$db->prepare('INSERT INTO data (id, enter_time, exit_time, stay) VALUES(:id, :enter_time, :exit_time, :stay)');
                $write->bindvalue(':id',$_GET['id']);
                $write->bindvalue(':enter_time',$db_data[0]["enter_time"]);
                $write->bindvalue(':exit_time',$_GET['time']);
                $write->bindvalue(':stay',calculate_staytime($db_data[0]["enter_time"], $_GET['time']));
                $write->execute();

                //現在の入室者データから削除
                $sqldata = $db->prepare("DELETE FROM now_enter WHERE id = $_GET[id]");
                $sqldata->execute();
                $data[0]['stats'] = "0";
            }

            //JSONデータ出力
            header("Content-type: application/json; charset=UTF-8");
            echo json_encode($data);
                            
            $db = null;// 切断
        } catch(PDOException $e){ //データベース接続失敗
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
// echo calculate_staytime("2018_3_15_16:50", "2019_1_13_19:38");

    //滞在時間の計算
function calculate_staytime($enter, $exit){
    $stay_time = array("", "", "", "", "");//滞在時間の計算結果を格納する変数

    //文字列から数字へ分割
    $enter = explode("_", $enter);
    $exit = explode("_", $exit);
    $enter_time = explode(":", $enter[3]);
    $exit_time = explode(":", $exit[3]);
    //分割したものを計算しやすいようにまとめる
    $enter = array($enter[0], $enter[1], $enter[2], $enter_time[0], $enter_time[1]);
    $exit = array($exit[0], $exit[1], $exit[2], $exit_time[0], $exit_time[1]);

    //計算
    for($i = 4; $i > -1; $i--){
        if($enter[$i] < $exit[$i]){
            $stay_time[$i] = $exit[$i] - $enter[$i];
        }
        else if($enter[$i] > $exit[$i]){//繰り下げ計算
            switch ($i) {
                case 4://分
                    $stay_time[$i] = 60 + $exit[$i] - $enter[$i];
                    $exit[$i - 1]--;
                    break;
                case 3://時
                    $stay_time[$i] = 24 + $exit[$i] - $enter[$i];
                    $exit[$i - 1]--;
                    break;
                case 2://日
                    $exit[$i - 1]--;
                    if($exit[4] > 1)
                        $stay_time[$i] = date("t", mktime(0, 0, 0, $exit[3] % 12 + 1, $exit[4])) + $exit[$i] - $enter[$i];
                    else
                        $stay_time[$i] = date("t", mktime(0, 0, 0, $exit[3] % 12, $exit[4] - 1)) + $exit[$i] - $enter[$i];
                    break;
                case 1://月
                    $stay_time[$i] = 12 + $exit[$i] - $enter[$i];
                    $exit[$i - 1]--;
                    break;
    

            }
        }
    }
    
    $word = array("年間", "ヵ月", "日", "時間", "分");
    $return_word = "";//返り値
    for($i = 0; $i < 5; $i++)
        if($stay_time[$i] != ""){
            $return_word .= $stay_time[$i];
            $return_word .= $word[$i];
        }
    return $return_word;
}

?>