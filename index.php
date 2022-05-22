<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "shoutbox";
$messages = "messages";

$connection = mysqli_connect($servername, $username, $password) or die("Connection failed: " . $connection->connect_error);

echo "<script>console.log('Connected successfully')</script>";

$create_database = "CREATE DATABASE IF NOT EXISTS $database";

if (mysqli_query($connection, $create_database)){
    echo "<script>console.log('" . $database . " database created')</script>";
} else {
    echo "<script>console.log('Error creating database: " . mysqli_error($connection) . "')</script>";
}

if(mysqli_select_db($connection, $database)){
    echo "<script>console.log('" . $database . " database selected')</script>";
} else{
    echo "<script>console.log('Database is not selected: " . mysqli_error($connection) . "')</script>";
}

$create_table = "CREATE TABLE IF NOT EXISTS $messages (
    id INT(12) AUTO_INCREMENT,
    username VARCHAR(225) NOT NULL,
    shout TEXT NOT NULL,
    sendTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)";

if(mysqli_query($connection, $create_table)){
    echo "<script>console.log('" . $messages . " table created')</script>";
} else {
    echo "<script>console.log('Table is not created: " . mysqli_error($connection) . "')</script>";
}

if(isset($_POST['username']) && isset($_POST['message'])){
    $username = $_POST['username'];
    $message = $_POST['message'];

    $send_message = "INSERT INTO $messages (`username`, `shout`) VALUES ('$username', '$message')";

    mysqli_query($connection, $send_message);
}

$sql_shoutouts = "SELECT `username`, `shout`, `sendTime` FROM `messages` ORDER BY `sendTime` DESC LIMIT 5";

$result = mysqli_query($connection, $sql_shoutouts);

$shoutouts = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>ShoutBox</title>
</head>
<body>
    <div class="container">
        <h1 class="header_title">
            <span class="headers">S</span>
            <span class="headers">H</span>
            <span class="headers">O</span>
            <span class="headers">U</span>
            <span class="headers">T</span>
            <span class="headers">B</span>
            <span class="headers">O</span>
            <span class="headers">X</span>
        </h1>

        <div class="shouts" id="shouts">
            <?php foreach($shoutouts as $item){?>
                <div class="card">
                    <?php
                        $date_time = $item['sendTime'];
                        $timestamp = strtotime($date_time);
                    ?>
                    <div class="datetime"><?php echo date("m/d/Y", $timestamp)?> - <?php echo date("h:i a", $timestamp)?></div>
                    <div class="username"><?php echo $item['username']?></div>
                    <div class="message"><?php echo $item['shout']?></div>
                </div>
            <?php }?>
        </div>

        <div class="send">
            <form action="index.php" method="post">
                <input type="text" name="username" id="username" placeholder="Username..." required>
                <br>
                <textarea name="message" id="message" cols="30" rows="2" placeholder="Shoutout..." required></textarea>
                <br>
                <button type="submit" onclick="saveUserName()"><h3>SUBMIT</h3></button>
            </form>
        </div>
    </div>

    <script>
        const shouts = document.querySelector("#shouts");

        shouts.scrollTop = shouts.scrollHeight;

        const saveUserName = () =>{
            const username = document.querySelector("#username");

            localStorage.setItem("shoutbox_userName", username.value);
        }

        if(localStorage.getItem('shoutbox_userName') !== null){
            const username = document.querySelector("#username");

            username.value = localStorage.getItem('shoutbox_userName');
        }
    </script>
</body>
</html>
