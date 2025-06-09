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
            font-family: body;
        }

        body {
            color: var(--prim);
            background: radial-gradient(circle, #191825, #1f1e2d, #191825);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }

        h1 {
            font-family: extra;
            font-size: 400%;
            text-align: center;
            color: var(--prim);
            margin: 5%;
            text-shadow: 0 0 10px #e284ffa1;
        }

        #login {
            justify-content: center;
            text-align: center;
            padding: 2%;
            width: 30%;
            font-size: 200%;
            background-color: #865dff3b;
            border-radius: 30px;
        }

        #err {
            font-weight: bold;
            font-size: 50%;
            color: rgb(186, 32, 32);
            padding: 1px;
        }

        #reg{
            font-weight: bold;
            color: var(--prim);
            padding: 10px;
            font-size: 50%;
        }

        a{
            font-weight: bold;
            font-family: accentFont;
            font-size: 120%;
            color: var(--prim);
            transition: 0.2s ease-in-out;
        }
        a:hover{
            color:rgb(140, 83, 158);
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
            font-size: 10px;
            font-family: "body";
            font-style: italic;
            color: var(--accent);
            opacity: 0.8;
        }

        input[type="submit"] {
            font-family: accentFont;
            margin-top: 5%;
            width: 25%;
            padding: 10px;
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
    </style>
    <?php
        $errorStatus = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $conn = new mysqli("localhost", "root", "", "music_player_app");

            $uname = $_POST["uname"];
            $pwd = $_POST["pwd"];

            $stmt = $conn->prepare("SELECT id,password_hash FROM users WHERE username = ?");
            $stmt->bind_param("s", $uname);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($user_id,$pwd_from_db);
                $stmt->fetch();
                
                if (password_verify($pwd,$pwd_from_db)){
                    if ($uname === "admin") {
                        header("Location: AddMusic.php");
                    } else {
                        session_start();
                        $_SESSION['userID'] = $user_id;
                        $_SESSION['userName'] = $uname;
                        header("Location: HomePage.php");
                    }
                } else {
                    $errorStatus .= "Incorrect Password<br>";
                }
            } else {
                $errorStatus .= "User Not Found";
            }
        }
    ?>
</head>

<body>
    <h1> MUSIC PLAYER</h1>
    <div id="login">
        <form method="post">
            <input type="text" name="uname" placeholder="Enter Username" required><br>
            <input type="password" name="pwd" placeholder="Enter Password" required>
                <?php if ($errorStatus): ?>
                <p><?php echo $errorStatus; ?></p>
            <?php endif; ?>
            <p id='reg'>
                New to this? <a href="SignUp.php">Create Account</a>
            </p>
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>