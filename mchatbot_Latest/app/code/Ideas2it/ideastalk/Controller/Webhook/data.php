<?php
    // $servername = "104.236.0.26";
    // $username = "root";
    // $password = "BoStOnTeApArTy";
    // $database ="magentobot"  ;
    $servername = "localhost";
    $username = "Arif1234";
    $password = "Arif@1234";
    $database ="mchatbot"  ;
    $conn = mysqli_connect($servername, $username, $password);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        $db_selected = mysqli_select_db($conn, $database);
        if (!$db_selected) {
            echo "error in selecting database";
        } else {
               $sql1 = "SELECT * FROM `bot_user_queries`";
                $sql ="SELECT GROUP_CONCAT(user_id ORDER BY  id ASC SEPARATOR '#-#') userId,GROUP_CONCAT(CONCAT('User : ', T.user_answer, '<br>Bot : ', T.bot_reply) ORDER BY  id ASC SEPARATOR '<br>') chat, GROUP_CONCAT(DATE_FORMAT(T.created_date,'%d-%m-%Y %H:%i %p') ORDER BY  id ASC SEPARATOR '#-#') date
                FROM (SELECT *
                FROM 	bot_user_queries ORDER BY id DESC) AS T 
                GROUP BY T.session_id DESC";
            // print_r($sql); die();
            $result = mysqli_query($conn, $sql);
            // print_r($result); die();
            if ($result->num_rows > 0) {
               
             } 
        }
    }
?>