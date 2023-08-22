<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>

        <body>
        <?php
        //データベース作成
         $dsn = 'mysql:dbname=XXXDB;host=localhost';
         $user = 'tb-XXXUSER';
         $password = 'XXXPASSWORD';
         $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
         $sql = "CREATE TABLE IF NOT EXISTS tbtest"
            . " ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "date DATETIME,"
            . "word char(20)"
            . ");";
            $stmt = $pdo->query($sql);
            
 if(isset($_POST["submit"])){
         if(isset($_POST["name"]) && isset($_POST["str"]) && isset($_POST["pass"])){
             $name = $_POST["name"];
             $comment = $_POST["str"];
             $date = date("Y/n/j G:i:s");
             $password = $_POST["pass"];
             
             if(empty($_POST["input_edit"])){
                 //新規作成
            
                 $sql = $pdo->prepare("INSERT INTO tbtest (name, comment, date, word) VALUES (:name, :comment, :date, :word)");
                 $sql->bindParam(':name', $name, PDO::PARAM_STR);
                 $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
                 $sql->bindParam(':date', $date, PDO::PARAM_STR);
                 $sql->bindParam(':word', $password, PDO::PARAM_STR);
                 $sql->execute();
             } else {
                 
                //編集
                 $edit_id = $_POST["input_edit"];
                 $sql = 'SELECT * FROM tbtest WHERE id=:id';
                 $stmt = $pdo->prepare($sql);
                 $stmt->bindparam('id', $edit_id, PDO::PARAM_INT);
                 $stmt->execute();
                 $result = $stmt->fetch();
                 $stored_password = $result['word'];
                 $id = $_POST["input_edit"];
                 $name = $_POST["name"];
                 $comment = $_POST["str"];
                 $password = $_POST["pass"];
                 $sql = 'UPDATE tbtest SET name=:name, comment=:comment, word=:word WHERE id=:id';
                 $stmt = $pdo->prepare($sql);
                 $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                 $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                 $stmt->bindParam(':word', $password, PDO::PARAM_STR);
                 $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                 $stmt->execute();
         }
     }
     }
     
     //削除
     if(isset($_POST["delete_submit"]) && isset($_POST["delete"])) {
         $delete_num = $_POST["delete"];
                 
                 $sql = 'SELECT * FROM tbtest WHERE id=:id';
                 $stmt = $pdo->prepare($sql);
                 $stmt->bindparam('id', $delete_num, PDO::PARAM_INT);
                 $stmt->execute();
                 $result = $stmt->fetch();
                 $stored_password = $result['word'];
     if($_POST["deletepass"] === $stored_password){
         $sql = 'DELETE FROM tbtest WHERE id=:id';
         $stmt = $pdo->prepare($sql);
         $stmt->bindParam(':id', $delete_num, PDO::PARAM_INT);
         $stmt->execute();
                 }
     }

?>

        <form action="" method="post">
            <input type="text" name="name" value="<?php
                  if (!empty($_POST["edit"]) && isset($_POST["edit_submit"])) {
                     $id = $_POST["edit"];
                     $sql = 'SELECT * FROM tbtest WHERE id=:id';
                     $stmt = $pdo->prepare($sql);
                     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                     $stmt->execute();
                     $result = $stmt->fetch();
                     $stored_password = $result['word'];
                     if ($result && $_POST["editpass"] == $stored_password) {
                         echo $result['name'];
                    }
                }
            ?>" placeholder="名前"><br>
            <input type="text" name="str" value="<?php
                  if (!empty($_POST["edit"]) && isset($_POST["edit_submit"])) {
                     $id = $_POST["edit"];
                     $sql = 'SELECT * FROM tbtest WHERE id=:id';
                     $stmt = $pdo->prepare($sql);
                     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                     $stmt->execute();
                     $result = $stmt->fetch();
                     $stored_password = $result['word'];
                     if ($result && $_POST["editpass"] == $stored_password) {
                         echo $result['comment'];
                    }
                }
            ?>"
            placeholder="コメント"><br>
            <input type="hidden" name="input_edit" value="<?php if (!empty($_POST["edit"]) && isset($_POST["edit_submit"])) {
                 $id = $_POST["edit"];
                     $sql = 'SELECT * FROM tbtest WHERE id=:id';
                     $stmt = $pdo->prepare($sql);
                     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                     $stmt->execute();
                     $result = $stmt->fetch();
                     $stored_password = $result['word'];
                     if ($result && $_POST["editpass"] == $stored_password) {
                         echo $_POST["edit"];
                    }
            } ?>">
            <input type="text" name="pass" placeholder="パスワード"><br>
            <input type="submit" name="submit"><br><br>
            <input type="text" name="delete" placeholder="削除対象番号"><br>
            <input type="text" name="deletepass" placeholder="パスワード"><br>
            <input type="submit" name="delete_submit" value="削除"><br><br>
            <input type="text" name="edit" placeholder="編集対象番号"><br>
            <input type="text" name="editpass" placeholder="パスワード"><br>
            <input type="submit" name="edit_submit" value="編集">
        </form>
        
        
        <?php
        //表示
         $sql = 'SELECT * FROM tbtest';
         $stmt = $pdo->query($sql);
         $results = $stmt->fetchAll();
        foreach ($results as $row) {
             echo $row['id'] . ',';
             echo $row['name'] . ',';
             echo $row['comment'] . '<br>';
             echo "<hr>";
        }
        ?>
        
        </body>
</html>