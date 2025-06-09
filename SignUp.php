<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="LoginPage.css?v=<?= time(); ?>">
    <style>
        @font-face {
            font-family: "accentFont";
            src: url("./fonts/NovaSquare-Regular.ttf");
        }
        @font-face{
            font-family: "body";
            src: url("./fonts/LexendZetta-VariableFont_wght.ttf");
        }
        @font-face{
            font-family: "extra";
            src: url("./fonts/Monoton-Regular.ttf");
        }
        

        :root {
            --dark: #191825;
            --prim: #E384FF;
            --sec: #865DFF;
            --accent: #FFA3FD;
        }

        body {
            background: radial-gradient(circle, #191825, #1f1e2d, #191825);
            display: flex;
            justify-content: center;
            align-items: center;

        }

        h1 {
            font-family: body;
            font-size: 45px;
            text-align: center;
            color: var(--prim);
            margin: 5%;
            text-shadow: 0 0 5px #e284ffa1;
        }

        #login {
            justify-content: center;
            text-align: center;
            padding: 2%;
            width: 30%;
            background-color: #865dff3b;
            border-radius: 30px;
        }

        p {
            font-family: body;
            font-weight: bold;
            color: #de5285;
            padding: 10px;
        }

        input[type="text"],input[type="password"] {
            font-family: accentFont;
            width: 90%;
            font-size: 18px;
            margin: 16px 0;
            padding: 16px;
            border: 2px solid var(--prim);
            border-radius: 10px;
        }

        input::placeholder {
            font-style: italic;
            font-size: 10px;
            font-family: body;
            color: var(--accent);
            opacity: 0.8;
        }

        input[type="submit"] {
            font-family: accentFont;
            margin-top: 5%;
            width: 30%;
            padding: 16px;
            font-size: 24px;
            background-color: var(--sec);
            color: #ffffff;
            border: 1px solid var(--sec);
            border-radius: 20px;
            transition: 0.2s linear;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: var(--dark);
            color: var(--prim);
            box-shadow: 0 0 10px var(--accent);
        }

        #logoutButton {
            position: absolute;
            top: 5%;
            right: 5%;
            width: 10%;
            font-size: 24px;
            font-family: accentFont;
            font-weight: 600;
            padding: 5px;
            background-color: var(--sec);
            color: #ffffff;
            border: 1px solid var(--sec);
            border-radius: 20px;
            transition: 0.2s linear;
            cursor: pointer;
        }

        #logoutButton:hover {
            background-color: var(--dark);
            color: var(--prim);
            box-shadow: 0 0 10px var(--accent);
            transform: scale(1.05);
        }
    </style>
    <?php
        $errorStatus = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $conn = new mysqli("localhost", "root", "", "music_player_app");

            $uname = $_POST["uname"];
            $pwd = $_POST["pwd"];
            $hashed_pwd = password_hash($pwd,PASSWORD_BCRYPT);
            $repwd = $_POST["repwd"];

            if(preg_match("/[A-Za-z0-9]{3,}/",$uname)){
                if($pwd === $repwd){

                    $stmt = $conn->prepare( "SELECT username FROM users WHERE username=?");
                    $stmt->bind_param("s",$uname);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows === 0) {
                        $stmt = $conn->prepare( "INSERT INTO users(username,password_hash) VALUES (?,?)");
                        $stmt->bind_param("ss",$uname,$hashed_pwd);
                        $stmt->execute();
                        
                        $stmt = $conn->prepare( "SELECT id FROM users WHERE username=?");
                        $stmt->bind_param("s",$uname);
                        $stmt->execute();
                        $stmt->bind_result($user_id);
                        $stmt->fetch();

                        session_start();
                        $_SESSION['userID'] = $user_id;
                        $_SESSION['userName'] = $uname;
                        header("Location: HomePage.php");
                    }else{
                        $errorStatus .= "Username already taken!!!";
                    }
                }else{
                    $errorStatus .= "Passwords do not match!";
                }
            }else{
                $errorStatus .= "Usernames must be at least 3 characters long and contain only letters and digits.";
            }            
        }
    ?>
</head>

<body>
    <div>
        <button id="logoutButton" onclick="window.location.replace('index.php')">Sign-in Instead</button>
    </div>
    <div id="login">
        <h1>Create Account</h1><br>
        <form action="SignUp.php" method="post">
            <input type="text" name="uname" placeholder="Enter Username" required><br>
            <input type="password" name="pwd" placeholder="Enter Password" required><br>
            <input type="password" name="repwd" placeholder="Confirm Password" required>
            <?php if ($errorStatus): ?>
                <p><?php echo $errorStatus; ?></p>
            <?php endif; ?>
            <input type="submit" value="Register">
        </form>
    </div>
</body>

</html>