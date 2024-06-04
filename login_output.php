<?php session_start(); ?>
<?php require 'header.php'; ?>
<?php require 'db_conect.php'; ?>
<h1>ログイン<h1>
<?php
//dbで使う名前などはまだ決めてないので仮入力 
unset($_SESSION['customer']);
if($_POST['password'] != null && $_POST['login'] != null){
$pdo = new PDO($connect,USER,PASS);
$sql = $pdo->prepare('select * from client where client_address=?');//user_name ユーザー名をメアドに変更
$sql->execute([$_POST['login']]);
$pass=password_hash($_POST['password'],);//ハッシュ化
foreach($sql as $row){
    if($pass==$row['password']){
    $_SESSION['customer']=[
        'id'=>$row['client_id'],'name'=>$row['name'],
        'password'=>$row['password'],'address'=>$row['client_address']
    ];
    }
}
if(isset($_SESSION['customer'])){
    echo '<p class="log">いらっしゃいませ、',$_SESSION['customer']['name'],'さん。</p>';
   echo '<a href="shohin_top.php" id="my"><button>TOP画面へ</button></a>';

}else{
    echo $pass;
    echo '<p class="log">ログイン名またはパスワードが違います。</p>';
    echo '<a href="login_input.php" id="my"><button>ログイン画面へ</button></a>';
}
}else{
    echo '<p class="log">ログイン名またはパスワードを入力してください。</p>';
    echo '<a href="login_input.php" id="my"><button>ログイン画面へ</button></a>';
}
?>
<?php require 'footer.php'; ?>