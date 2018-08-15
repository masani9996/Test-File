<form action="test4.php" method="post"> <!--フォーム諸々-->
        <input type="text" name="名前" value="" size="" placeholder="名前">
        <input type="text" name="編集" value="" size="3" placeholder="編集"><br>
        <input type="text" name="コメント" value="" size="" placeholder="コメント">
        <input type="text" name="編パス" value="" size="6" placeholder="編集パス">
        <input type="submit" name="" value="編集" size=""><br>
        <input type="text" name="パス作成" value="" size="6" placeholder="パス作成">
        <input type="submit" name="" value="送信" size=""><br>
    <br>
        <!--<input type="hidden" name="投稿番号" value="" size="" placeholder="投稿番号">-->
        
        <input type="text" name="削除" size="" placeholder="削除対象番号"><br>
        <input type="text" name="削パス" value="" size="6" placeholder="削除パス">
        <input type="submit" name="" value="削除" size=""><br>
    <br>
</form>
<?php

$name = $_POST['名前']; //以下フォームの値を変数へ
$comment = $_POST['コメント'];
$edit = $_POST['編集'];
$del = $_POST['削除'];
$date = date('Y/m/d/ H:i:s');
$pass = $_POST['パス作成'];
$pass_e = $_POST['編パス'];
$pass_d = $_POST['削パス'];

//try {
    //DBへ接続
    $dsn = 'データベース';
    $user = 'ユーザー';
    $password = 'パスワード';
    $pdo = new PDO($dsn,$user,$password); //new演算子を用いる
    
    //テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS masani"
.'('
.'id INT not null auto_increment primary key,' //自動で増分
.'name char(32),'
.'comment TEXT,'
.'reg_datetime DATETIME,' //時間の取得
.'pass char(8)'
.');';
$stmt = $pdo->query($sql);
    
    //DBへ登録
    if(empty($_POST['編集'])&&!empty($_POST['名前'])&&!empty($_POST['コメント'])&&!empty($_POST['パス作成'])) {
    
    $sql = $pdo->prepare("INSERT INTO masani (name,comment,reg_datetime,pass) VALUES (:name,:comment,:reg_datetime,:pass)");
    $sql->bindParam(':name',$name,PDO::PARAM_STR); //以下全て文字列
    $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
    $sql->bindParam(':reg_datetime',$date,PDO::PARAM_STR);
    $sql->bindParam(':pass',$pass,PDO::PARAM_STR);
    
    $sql->execute();
    }
    
    //編集
if(!empty($_POST['編集'])) { //編集が空で無ければ
    
    $sql = "SELECT * FROM masani ORDER BY id";

    $results = $pdo->query($sql);
    foreach ($results as $row) { //投稿番号及びパスを抽出
    
    $id_e = $row['id'];
    $pass_e = $row['pass'];
        
    if($id_e == $edit && $pass_e == $pass_e){ //抽出した物の一致により動作

    $id = $edit;
    $nm = $name;
    $kome = $comment;
    
    $sql = "update masani set id='$id',name='$nm',comment='$kome',reg_datetime='$date' where id = $id"; //whereによりidを指定
    $result = $pdo->query($sql);
    
        }
    }
}
    //削除
if(!empty($_POST['削除'])) {

    $sql = "SELECT * FROM masani ORDER BY id"; //消去後投稿番号順に並べるようORDER BY id使用(空欄にならないよう)

    $results = $pdo->query($sql); //投稿番号及びパスを抽出
    foreach ($results as $row) {
    
    $id_d = $row['id'];
    $pass_d = $row['pass'];
        
    if($id_d == $del && $pass_d == $pass_d){ //抽出した物の一致により動作
    
    $sql = "delete from masani where id=$del"; //whereによりidを指定
    $results = $pdo->query($sql);
        }
    }
}

    //表示
$sql = "SELECT * FROM masani ORDER BY id"; //投稿番号順に並べる

$results = $pdo->query($sql);
foreach ($results as $row) {
    
    echo $row['id'].' ';
    echo $row['name'].' ';
    echo $row['comment'].' ';
    echo $row['reg_datetime'].'<br>';
}
/*
} catch(PDOException $e) {
    
    echo $e->getMessage();
    die();
}*/

?>