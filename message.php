<?php
session_start();
include_once "assets/ps/db.php";

if (isset($_COOKIE["remember"])) {
    $_SESSION["user_id"] = $_COOKIE["remember"];
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script crossorigin="anonymous" src="https://kit.fontawesome.com/05ddc1d5ba.js"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" />
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/message.css">
	<title>OIU Community</title>
</head>

<body>
	<div class="container">
		<div class="wrapper">
			<header>
				<?php
				$userid = $_GET["user_id"];

				$sql = "SELECT * FROM users WHERE user_id = {$userid}";
				$result = $conn->query($sql);

				if($result->num_rows > 0) {
					$row = $result->fetch_assoc();
				} else {
				  header("location: index.php");
				}
				?>
				<a href="index.php" class="back-icon"><i class="fa-solid fa-arrow-right"></i></a>
				<div class="img" custom="<?php echo $row["img"]?>">
					<i class="fa-solid fa-user"></i>
				</div>
				<div class="details">
					<span>
						<?php echo $row["username"]?>
					</span>
					<p>
						<?php echo $row["status"]?>
					</p>
				</div>
			</header>
			<div class="chat-box">
				<div class="chat">
					<div class="wrapper"></div>
				</div>
			</div>
			<form class="typing-area">
			<input type="hidden" class="sender" name="sender" value="<?php echo $_SESSION["user_id"]?>" hidden>
			<input type="hidden" class="receiver" name="receiver" value="<?php echo $userid?>" hidden>
				<input type="text" name="message" class="input-field message" placeholder="مراسلة" autocomplete="off">
				<button><i class="fa-solid fa-paper-plane"></i></button>
			</form>
		</div>
	</div>
	<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
	<script src="assets/js/message.js"></script>
</body>

</html>