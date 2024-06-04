<?php session_start();?>
<?php require 'header.php'; ?>
<?php require 'db_conect.php';?>
<div align="center"><h1>新規登録</h1></div>
<?php
     $pdo=new PDO($connect,USER,PASS);
     if(isset($_SESSION['customer'])){
        $id=$_SESSION['customer']['id'];
        $sql=$pdo->prepare('select * from client where client_id!=? and name=?');
        $sql->execute([$id,$_REQUEST['name']]);
     }else{
        $sql=$pdo->prepare('select * from client where name=?');
        $sql->execute([$_REQUEST['name']]);
     }
     //mail
     if(isset($_SESSION['customer'])){
        $id2=$_SESSION['customer']['id'];
        $sql2=$pdo->prepare('select * from client where client_id!=? and client_address=?');
        $sql2->execute([$id2,$_REQUEST['address']]);
     }else{
        $sql2=$pdo->prepare('select * from client where client_address=?');
        $sql2->execute([$_REQUEST['address']]);
     }

     if(empty($sql2->fetchAll())){
     if(empty($sql->fetchAll())){
            $pass=password_hash($_REQUEST['password'],PASSWORD_DEFAULT);//ハッシュ化
            $sql=$pdo->prepare('insert into client values(null,?,?,?,1)');
            $sql->execute([
                $_REQUEST['address'],$pass,$_REQUEST['name']   
            ]);
                echo'<div align="center">情報を登録しました。</div>';
                echo '<div align="center"><a href="login_input.php"><button>ログインへ戻る</button></a></div>';
    }else{
        echo'<div align="center">ログイン名が既に使用されています。</div>';
        echo '<div align="center"><a href="customer_input.php"><button>登録画面へ戻る</button></a></div>';
    }
    }else{
        echo'<div align="center">メールアドレスが既に使用されています。</div>';
        echo '<div align="center"><a href="customer_input.php"><button>登録画面へ戻る</button></a></div>';
    }
?>
<?php require 'footer.php'; ?>