<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width,initial-scale=1.0">
<title>チャットアプリ開発</title>
</head>
<body>
<?php require 'db_conect.php';?>
<?php
     $pdo=new PDO($connect,USER,PASS);
     $name=$address=$password='';
     if($_SERVER["REQUEST_METHOD"]=='POST'){
        if(isset($_SESSION['customer'])){
            $id=$_SESSION['customer']['id'];
            $sql=$pdo->prepare('update client set name=?, password=?,client_address=? where client_id=?');
            $pass=password_hash($_POST['password'],PASSWORD_DEFAULT);//ハッシュ化 
            $sql->execute([
                $_POST['name'],$pass,
                $_POST['address'],$id
            ]);
            $_SESSION['customer']=[
                'id'=>$id,'name'=>$_POST['name'],
                'password'=>$pass,'address'=>$_POST['address'],
            ];
            echo 'お客様情報を更新しました。';    
        }
    }
     if (isset($_SESSION['customer'])){
        $id=$_SESSION['customer']['id'];
         $name=$_SESSION['customer']['name'];
         $address=$_SESSION['customer']['address'];
         $password=$_SESSION['customer']['password'];
     }
         
         $pdo = new PDO($connect,USER,PASS);
         $sql = $pdo->prepare('select * from client where client_id = ?');
         $sql ->execute([
             $_SESSION['customer']['id']
         ]);
         
         echo '<div align="center"><h1>マイページ</h1></div>'; 
         
         foreach($sql as $row){
         
         echo'<div align="center"><h2>ユーザープロフィール</h2><br></div>';
         echo'<form action="mypage.php" method="post" class="hidden">';

         echo  '<div class="D">プロフィール更新する</div>';
         echo'<p >ユーザーネーム</p>';
         echo '<div class="E">';
         echo '<input type="text" name="name" value="',$row['name'],'">';
         echo '</div>';       
         
         echo'<p>メールアドレス</p>';
         echo '<div class="E">';
         echo'<input type="text" name="address"value="',$row['client_address'],'">';
         echo '</div>';

         echo'<p>パスワード　　</p>';
         echo '<div class="E">';
         echo'<input type="text" name="password">';
         echo '</div>';

         echo '<input type="hidden" name="id" value="',$row['client_id'],'">';

         
         echo '<div class="E">';
         echo '<input type="submit" value="更新">';
         echo '</div>';

         echo'</form>';
         echo '<div align="center"><a href="*">投稿</a></div>';
         echo '<div align="center"><a href="login_input.php">ログアウト</a></div>';
         echo '<div align="center"><a href="account_delete_check.php?id=',$row['client_id'],'">アカウント削除</a></div>';
         
         }
         

    $id = $pdo->prepare('select client_id from client where name=?');
    $id->execute([$name]);
    $sql = $pdo->prepare('select * from thread where client_id=?');
    foreach($id as $myid){
    $sql->execute([$myid['client_id']]);
    }
    $tr=0;
    echo '<table align="center">';
    echo '<tr><td>Myスレッド一覧</td></tr>';
    echo '<tr>';
    foreach($sql as $row){
    echo '<td>';
    echo '<a href="partner.php?genre=',$row['title'],'">',$row['title'],'</a>';
    echo '</td>';
        $tr++;
        if($tr==3){
        echo '</tr>';
        $tr=0;
        echo '<tr>';
        }
    }
          
    echo '<tr><td><div align="center"><a href="Top_kensakukekka.php">戻る</a></div></td></tr>';
    echo '</table>';
    ?> 
<script src="https://code.jquery.com/jquery.min.js"></script>
<script>
$(function() {
    $(".D").click(function() {
        $(".E").slideToggle("");
    });
});
</script>
<style>
.D{
    background: #b6beff;
    cursor: pointer;
    text-align:center;
    width: 200px;
    margin-right:auto;
    margin-left:auto;
}
.E{
    background: #ffaf74;
    height: 50px;
    display:none;
    text-align:center;
    width: 200px;
    margin-right:auto;
    margin-left:auto;
}
p{
  text-align:center;
}
</style>
            <?php require 'footer.php'; ?> 