<!-- signup.php -->
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 	<script> 		$(function() { 			$("#date_of_birth").datepicker(); 		}); 	</script>
	<title>Signup</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
		<!--Stylesheet-->
		<style media="screen">
			*,
			*:before,
			*:after{
				padding: 0;
				margin: 0;
				box-sizing: border-box;
			}
			body{
				background-color: #080710;
			}
			.background{
				width: 430px;
				height: 520px;
				position: absolute;
				transform: translate(-50%,-50%);
				left: 50%;
				top: 50%;
			}
			.background .shape{
				height: 200px;
				width: 200px;
				position: absolute;
				border-radius: 50%;
			}
			.shape:first-child{
				background: linear-gradient(
					#1845ad,
					#23a2f6
				);
				left: -80px;
				top: -80px;
			}
			.shape:last-child{
				background: linear-gradient(
					to right,
					#ff512f,
					#f09819
				);
				right: -30px;
				bottom: -80px;
			}
			form{
				height: 520px;
				width: 800px;
				background-color: rgba(255,255,255,0.13);
				position: absolute;
				transform: translate(-50%,-50%);
				top: 50%;
				left: 50%;
				border-radius: 10px;
				backdrop-filter: blur(10px);
				border: 2px solid rgba(255,255,255,0.1);
				box-shadow: 0 0 40px rgba(8,7,16,0.6);
				padding: 50px 35px;
			}
			form *{
				font-family: 'Poppins',sans-serif;
				color: #ffffff;
				letter-spacing: 0.5px;
				outline: none;
				border: none;
			}
			form h3{
				font-size: 32px;
				font-weight: 500;
				line-height: 42px;
				text-align: center;
			}

			label{
				display: block;
				margin-top: 30px;
				font-size: 16px;
				font-weight: 500;
			}
			input{
				display: block;
				height: 50px;
				width: 100%;
				background-color: rgba(255,255,255,0.07);
				border-radius: 3px;
				padding: 0 10px;
				margin-top: 8px;
				font-size: 14px;
				font-weight: 300;
			}
			::placeholder{
				color: #e5e5e5;
			}
			button{
				margin-top: 50px;
				width: 100%;
				background-color: #ffffff;
				color: #080710;
				padding: 15px 0;
				font-size: 18px;
				font-weight: 600;
				border-radius: 5px;
				cursor: pointer;
			}
			.social{
			margin-top: 30px;
			display: flex;
			}
			.social div{
			background: red;
			width: 150px;
			border-radius: 3px;
			padding: 5px 10px 10px 5px;
			background-color: rgba(255,255,255,0.27);
			color: #eaf0fb;
			text-align: center;
			}
			.social div:hover{
			background-color: rgba(255,255,255,0.47);
			}
			.social .fb{
			margin-left: 25px;
			}
			.social i{
			margin-right: 4px;
			}
			.grid {
				display: grid;
				grid-template-columns: repeat(3, 1fr);
				grid-template-rows: repeat(4, auto);
				gap: 10px;
			}
			.gender {
				position: relative;
				display: inline-block;
				width: 200px;
			}
			.gender select {
				width: 100%;
				padding: 10px;
				font-size: 16px;
				border: 1px solid #ccc;
				border-radius: 4px;
				appearance: none;
				-webkit-appearance: none;
				-moz-appearance: none;
				background-color: grey;
				background-image: url('dropdown-arrow.png');
				background-repeat: no-repeat;
				background-position: right center;
			}
		</style>
	</head>
<body>
	<div class="container">
		<div class="background">
				<div class="shape"></div>
				<div class="shape"></div>
			</div>
		<form method="POST" action="signup_action.php">
			<h3>Signup</h3>
			<div class="grid">
				<div class="firstName">
					<label for="first_name">First Name:</label>
					<input type="text" name="first_name" required>
				</div>
				<div class="lastName">
					<label for="last_name">Last Name:</label>
					<input type="text" name="last_name" required>
				</div>
				<div class="email">
					<label for="email">Email:</label>
					<input type="email" name="email" required>
				</div>
				<div class="phone">
					<label for="phone_number">Phone Number:</label>
					<input id="phone" type="text" name="phone_number" required>
				</div>
				<div class="address">
					<label for="address">Address:</label>
					<input type="text" name="address" required>
				</div>
				<div class="bday">
					<label for="date_of_birth">Date of Birth:</label>
					<div class="calendar-container">
						<input type="text" name="date_of_birth" id="date_of_birth" required>
						<!-- <span class="calendar-icon">&#128197;</span> -->
					</div>
				</div>
				<div class="pass">
					<label for="password">Password:</label>
					<input id="password" type="password" name="password" required>
				</div>
				<div class="confirm">
					<label for="confirm_password">Confirm Password:</label>
					<input id="confirmpassword" type="password" name="confirm_password" required>
				</div>
				<div class="gender">
					<label for="gender">Gender:</label>
					<select name="gender" required>
						<option value="male">Male</option>
						<option value="female">Female</option>
						<option value="not_specified">Not Specified</option>
					</select>
				</div>
			</div>
			<button>Sign up</button>
			<!-- <input type="submit" value="Signup"> -->
		</form>
	</div>
	
</body>
</html>
