<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<style type="text/css">
body {
  background-size: cover;
}

h1 {
  color: #666;
}

main {
  display: block;
  width: 960px;
  height: 540px;
  border-radius: 5px;
  background: rgba(255, 255, 255, 0.3);
  box-sizing: border-box;
  border: 1px solid #666;
  box-shadow: 5px 5px 5px rgb(0 0 0 / 30%);
  padding: 20px;
  text-align: center;
}

.section-container {
  display: flex;
  width: 100%;
}

section {
  width: calc(100% / 3);
  padding-left: 5px;
  padding-right: 5px;
  margin: o;
  box-sizing: border-box;
}

.button {
  width: 100%;
  text-transform: none;
  height: 40px;
  line-height: 40px;
  font-size: 20px;
  background-color: #54cb8e;
  border: none;
}

.button:hover {
  opacity: 0.8;
}

.button:active {
  background-color: #54cb8e;
}

.button + .box {
  display: none;
  font-size: 20px;
  margin-top: 10px;
  border: 1px solid white;
  background: rgba(0, 0, 0, 0.7);
  border-radius: 2px;
  padding: 10px;
  box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3) inset;
  text-align: left;
  color: white;
}

.button + .box.show {
  display: block;
}
</style>
<script>
document.querySelectorAll(".button").forEach((button) => {
  // .button要素をクリックしたときに発火
  button.addEventListener("click", () => {
    // .button要素の次の要素のクラス(.show)で切り替える
    button.nextElementSibling.classList.toggle("show");
  });
});
</script>
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

         
         echo  '<div class="section-container">';
         
         echo '<section><button class="button" type="button" >クリックして開く</button><div class="box">';
         echo'<input type="text" name="name" value="ユーザー名：',$row['name'],'">';
         echo'</div></section>';
         
         echo '<section><button class="button" type="button" >クリックして開く</button><div class="box">';
         echo'<p>メールアドレス</p>';
         echo'<input type="text" name="address"value="',$row['client_address'],'">';
         echo'</div></section>';
         
         echo '<section><button class="button" type="button" >クリックして開く</button><div class="box">';
         echo'<p>パスワード　　</p>';
         echo'<input type="text" name="password">';
         echo'</div></section>';
         
         echo '<input type="hidden" name="id" value="',$row['client_id'],'">';

         echo '</div>';

         echo '<div align="center"><input type="submit" value="更新"></div>';
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
            <?php require 'footer.php'; ?> 