<!DOCTYPE html>
<html>

<head>
    <title>Upload Music Track</title>
    <link rel="stylesheet" href="AddMusic.css?v=<?= time(); ?>">
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: radial-gradient(circle, #191825, #1f1e2d, #191825);
            color: var(--accent);
        }

        h1 {
            color: var(--prim);
            font-size: 200%;
            text-shadow: 0 0 2px rgba(255, 255, 255, 0.5);
        }

        #upload {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 32px;
            width: 30%;
            background-color: #865dff3b;
            border-radius: 30px;
        }

        p {
            font-weight: bold;
            color: #de5285;
            padding: 10px;
        }

        label{
            font-size: 120%;
            font-style: italic;
            font-weight: 300;
        }

        input[type="text"],
        input[type="password"] {
            width: 90%;
            font-size: 18px;
            margin: 16px 0;
            padding: 16px;
            border: 2px solid var(--prim);
            border-radius: 10px;
        }
        input[type="file"]{
            position:relative;
            left: 16%;
        }

        input::placeholder {
            font-family: body;
            font-size: 10px;
            font-style: italic;
            color: var(--accent);
            opacity: 0.8;
        }

        input[type="submit"] {
            font-family: accentFont;
            width: 100%;
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

        #username {
            font-family: sans-serif;
            font-weight: 600;
            position: absolute;
            top: 5%;
            left: 5%;
            padding: 12px;
            font-size: 30px;
            text-transform: capitalize;
            border: 1px solid var(--sec);
            box-shadow: 0 0 10px rgb(87, 20, 116);
            border-radius: 20px;
            color: var(--sec);
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
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["musicFile"])) {
            $conn = new mysqli("localhost", "root", "", "music_player_app");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $file = $_FILES["musicFile"];
            $trackData = file_get_contents($file["tmp_name"]);
            $trackName = $conn->real_escape_string($_POST["trackName"]);
            $artist = $conn->real_escape_string($_POST["artist"]);

            $stmt = $conn->prepare("INSERT INTO tracks (trackName, artist, track) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $trackName, $artist, $trackData);
            $stmt->send_long_data(2, $trackData); // index 2 = 'track'

            if ($stmt->execute()) {
                $message = "Track uploaded successfully.";
            } else {
                $message = "Upload failed: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    ?>
</head>

<body>
    
    <div>
        <div id="username">
            👤 Admin            
        </div>
        <button id="logoutButton" onclick="window.location.replace('index.php')">Log Out</button>
    </div>
    <div id="upload">
        <h1>UPLOAD A TRACK</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="trackName" placeholder="Track Name"><br><br>
            <input type="text" name="artist" placeholder="Artist Name"><br><br>

            <label>Select Music File:</label><br><br>
            <input type="file" name="musicFile" accept="audio/*" required><br>
            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <br><input type="submit" value="Upload Track">
        </form>

    </div>
</body>
</html>