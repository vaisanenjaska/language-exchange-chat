<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">

.panel{


margin-right: 3px;
}

.button {
    background-color: #4CAF50;
    border: none;
    color: white;
	margin-right: 30%;
	margin-left: 30%;
    text-decoration: none;
    display: block;
    font-size: 16px;
    cursor: pointer;
	width:30%;
    height:40px;
	margin-top: 5px;

}
input[type=text]{
		width:100%;
		margin-top:5px;

	}


.chat_wrapper {
	width: 70%;
	height:472px;
	margin-right: auto;
	margin-left: auto;
	background: #3B5998;
	border: 1px solid #999999;
	padding: 10px;
	font: 14px 'lucida grande',tahoma,verdana,arial,sans-serif;
}
.chat_wrapper .message_box {
	background: #F7F7F7;
	height:350px;
		overflow: auto;
	padding: 10px 10px 20px 10px;
	border: 1px solid #999999;
}
.chat_wrapper  input{
	//padding: 2px 2px 2px 5px;
}
.system_msg{color: #BDBDBD;font-style: italic;}
.user_name{font-weight:bold;}
.user_message{color: #88B6E0;}

@media only screen and (max-width: 720px) {
    .chat_wrapper {
        width: 95%;
	height: 40%;
	}


	.button{ width:100%;
	margin-right:auto;
	margin-left:auto;
	height:40px;}






}

</style>
</head>
<body>
<?php
include("../database/config.php");
$target = $_POST["chatTarget"];
$username = $_POST["username"];
?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


<script language="javascript" type="text/javascript">
$(document).ready(function(){

	var wsUri = "ws://http://language-exchange-cafe-chat.herokuapp.com:9000/server.php";
	websocket = new WebSocket(wsUri);

	websocket.onopen = function(ev) {}

	$('#send-btn').click(function(){
		var mymessage = $('#message').val();
		var myname = "<?php echo $username ?>";
		var target = <?php echo $target ?>;

		if(mymessage == ""){
			alert("Enter Some message Please!");
			return;
		}

		var objDiv = document.getElementById("message_box");
		objDiv.scrollTop = objDiv.scrollHeight;

		var msg = {
		message: mymessage,
		name: myname,
		target: target
		};

		websocket.send(JSON.stringify(msg));
	});

	websocket.onmessage = function(ev) {
		var msg = JSON.parse(ev.data);
		var type = msg.type;
		var umsg = msg.message;
		var uname = msg.name;

		if(type == 'usermsg' && umsg != null)
		{
			$('#message_box').append("<div><span class=\"user_name\">"+uname+"</span> : <span class=\"user_message\">"+umsg+"</span></div>");
		}
		if(type == 'system')
		{
			$('#message_box').append("<div class=\"system_msg\">"+umsg+"</div>");
		}

		$('#message').val('');

		var objDiv = document.getElementById("message_box");
		objDiv.scrollTop = objDiv.scrollHeight;
	};

	websocket.onerror	= function(ev){$('#message_box').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");};
	websocket.onclose 	= function(ev){$('#message_box').append("<div class=\"system_msg\">Connection Closed</div>");};
});




</script>
<div class="chat_wrapper">
<div class="message_box" id="message_box">
	<?php
		$user_name = mysqli_escape_string($db,$username);
		$target_user = mysqli_escape_string($db,$target);
		$id_sql = "Select user_id from User where name = '$user_name'";
		$userquery = mysqli_query($db, $id_sql);
		$row = mysqli_fetch_array($userquery, MYSQLI_ASSOC);
		$user = $row['user_id'];
		$user_id = mysqli_escape_string($db, $user);
		$sql = "Select user_id, text, target_user from Message where user_id = $user_id and target_user = $target_user or user_id = $target_user and target_user = $user_id order by date";
		$result = mysqli_query($db,$sql);
		while ($message_row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$id = mysqli_escape_string($db,$message_row['user_id']);
			$name_sql = "Select name from User where user_id = '$id'";
			$userquery = mysqli_query($db, $name_sql);
			$user_row = mysqli_fetch_array($userquery, MYSQLI_ASSOC);
			$user = $user_row['name'];
			echo "<div><span class=\"user_name\">".$user."</span> : <span class=\"user_message\">".$message_row['text']."</span></div>";
		}
	?>
</div>
<div class="panel">
<input type="text" name="message" id="message" placeholder="Message" maxlength="80"
onkeydown = "if (event.keyCode == 13)document.getElementById('send-btn').click()"  />





</div>

<button id="send-btn" class=button>Send</button>

</div>

</body>
</html>
