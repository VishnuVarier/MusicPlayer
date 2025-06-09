<!DOCTYPE html>
<html>
<?php
    session_start();
?>
<head>
    <title>MUSIC PLAYER</title>
    <link rel="stylesheet" href="HomePage.css?v=<?= time(); ?>">
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
            background: radial-gradient(circle, #191825, #1f1e2d, #191825);
            text-align: center;
            justify-content: center;
            align-items: center;
            color: #ffffff;

        }

        h1 {
            margin-top: 7%;
            font-size: 400%;
            color: var(--prim);
            text-align: center;
            font-family: extra;
            text-shadow: 0 0 10px #e284ffa1;
        }

        #chooseSong {
            display: inline-block;
            padding: 32px;
            width: 30%;
            background-color: #865dff27;
            border-radius: 30px;
        }

        select {
            text-align: center;
            background-color: var(--sec);
            color: #ffffff;
            width: 90%;
            font-size: 16px;
            margin: 16px 0;
            padding: 16px;
            border: 2px solid var(--prim);
            border-radius: 10px;
            transition: 0.2s linear;
            cursor: pointer;
        }

        select:hover {
            box-shadow: 0 0 10px var(--accent);
        }

        audio {
            background-color: var(--sec);
            color: #ffffff;
            width: 40%;
            margin: 1%;
            padding: 10px;
            /* border: 2px solid var(--prim); */
            border-radius: 50px;
            box-shadow: inset 0 0 10px rgb(87, 20, 116);
            box-shadow: 0 0 10px var(--sec);
            transition: 1s ease-in;
        }

        input[type="submit"] {
            font-family: accentFont;
            width: 25%;
            margin-top: 5%;
            padding: 1%;
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
            transform: scale(1.05);
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
    $conn = new mysqli("localhost", "root", "", "music_player_app");

    $audioData = '';
    $trackName = '';
    $artist = '';
    $result = $conn->query("SELECT * FROM tracks");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("SELECT track, trackName, artist FROM tracks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($audioData, $trackName, $artist);
            $stmt->fetch();
        } else {
            $audioData = '';
            $trackName = 'Song not found';
            $artist = '';
        }
    }
    ?>
</head>

<body>
    <div>
        <div id="username">
            👤 
            <?php
            if (isset($_SESSION['userName'])) {
                echo $_SESSION['userName'];
            }
            ?>
        </div>
        <button id="logoutButton" onclick="window.location.replace('index.php')">Log Out</button>
    </div>
    <h1>MUSIC PLAYER</h1>
    <br>
    <div id="chooseSong">
        <h2>Select a song</h2>
        <form method="post">
            <select name="id">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= (isset($_POST['id']) && $_POST['id'] == $row['id']) ? 'selected' : '' ?>>
                        <?php echo ($row['trackName']) . " - " . ($row['artist']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="submit" value="Play!">
        </form>
    </div>

    <br><br><br>

    <div>
        <audio id="audio_player" controls autoplay controlsList="nodownload noplaybackrate">
            <source src="data:audio/mpeg;base64,<?php echo base64_encode($audioData) ?>" type="audio/mpeg">
        </audio>
    </div>
</body>

</html>