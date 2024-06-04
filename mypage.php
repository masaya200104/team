<?php session_start(); ?>
<?php require 'header.php'; ?>


<script>
    const btn2 = document.getElementById('btn2');
    const btn2Text = document.getElementById('btn2-text');

    btn2.addEventListener('click', () => {
    // ボタンクリックでhiddenクラスを付け外しする
    btn2Text.classList.toggle('hidden');
    });
</script>
<style type="text/css">
.btn-container {
  text-align: center;
}

button {
  width: 80%;
  height: 50px;
  font-size: 18px;
  font-weight: bold;

  /* ボタンにカーソルを当てると、カーソルがポインターに変わる */
  cursor: pointer;
}

/* ボタンにカーソルを当てたとき、ボタンを半透明にする */
button:hover {
 opacity: 0.7;
}

/* クリックで表示させるテキストを隠す */
.hidden {
  display: none;
}

#btn2-text{
  margin: 16px 0;
  font-size: 14px;
  color: red;
}
</style> 
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
         echo '<div class="btn-container"><button id="btn2">ボタン２</button>';
         echo '<p id="btn2-text" class="hidden">';
         echo'<form action="mypage.php" method="post" class="hidden">';
         echo'ユーザー名　　';
         echo'<input type="text" align="center" name="name" class="hidden" value="',$row['name'],'">';
         echo'<br>';
         
         echo'メールアドレス';
         echo'<input type="text" name="address" class="hidden" value="',$row['client_address'],'">';
         echo'<br>';
         
         echo'パスワード　　';
         echo'<input type="text" name="password" class="hidden">';
         echo'<br>';
         
         echo '<input type="hidden" name="id" value="',$row['client_id'],'" class="hidden">';
         echo '<input type="submit" value="更新" class="hidden">';
         echo'</form>';
         echo '</p></div>';

         

         echo '<div align="center"><button><a href="*">投稿</a></button></div>';
         echo '<div align="center"><button><a href="login_input.php">ログアウト</a></button></div>';
         echo '<div align="center"><button><a href="account_delete_check.php?id=',$row['client_id'],'">アカウント削除</a></button></div>';
         
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
          
    echo '<tr><td><div align="center"><button><a href="Top_kensakukekka.php">戻る</a></button></div></td></tr>';
    echo '</table>';
    ?> 
            <?php require 'footer.php'; ?> 