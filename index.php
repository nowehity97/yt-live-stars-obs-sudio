<?php
require 'config.php';

function getYouTubeStats($videoId) {
    global $API_KEY;

    $youtubeUrl = "https://www.googleapis.com/youtube/v3/videos?part=snippet,liveStreamingDetails,statistics&id=$videoId&key=$API_KEY";
    $youtubeResponse = file_get_contents($youtubeUrl);
    $youtubeData = json_decode($youtubeResponse, true);

    $dislikeUrl = "https://returnyoutubedislikeapi.com/Votes?videoId=$videoId";
    $dislikeResponse = file_get_contents($dislikeUrl);
    $dislikeData = json_decode($dislikeResponse, true);

    // Upewnij się, że istnieją dane z YouTube API
    if (isset($youtubeData['items'][0])) {
        // Pobierz licznik dislike jeśli istnieje
        $dislikeCount = isset($dislikeData['dislikes']) ? $dislikeData['dislikes'] : null;
        $youtubeData['items'][0]['statistics']['dislikeCount'] = $dislikeCount;
    }

    return $youtubeData;
}


// --- Sprawdź czy to zapytanie AJAX ---
if (isset($_GET['ajax']) && $_GET['ajax'] == 1 && isset($_GET['videoId'])) {
    header('Content-Type: application/json');
    echo json_encode(getYouTubeStats($_GET['videoId']));
    exit;
}
$selectedColor = $_GET['color'] ?? 'white';

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <title>LiveCount</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: black;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            display: inline-block;
            min-width: 300px;
        }
        .color-boxes {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        .box {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            cursor: pointer;
        }
        .box-white { background: white; }
        .box-black { background: black; border: 1px solid white; }
        .box-blue { background: blue; }
        .box-green { background: green; }
        .box-red { background: red; }
        .box-lightblue { background: lightblue; }
        .box-gray { background: gray; }
        .stats-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 40px;
            font-weight: bold;
            margin-top: 15px;
            color: <?php echo $selectedColor; ?>;
        }
        input, button {
            font-size: 20px;
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #ff0000;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="color-boxes">
        <div class="box box-white" onclick="changeColor('white')"></div>
        <div class="box box-black" onclick="changeColor('black')"></div>
        <div class="box box-blue" onclick="changeColor('blue')"></div>
        <div class="box box-green" onclick="changeColor('green')"></div>
        <div class="box box-red" onclick="changeColor('red')"></div>
        <div class="box box-lightblue" onclick="changeColor('lightblue')"></div>
        <div class="box box-gray" onclick="changeColor('gray')"></div>
    </div>
    <div class="container">
        <h1>LiveCount</h1>
        <input type="text" id="videoInput" placeholder="Enter ID or live link">
        <button onclick="setVideoId()">Show statistics</button>
        <div id="stats" style="display:none;">
            <h2 id="liveTitle"></h2>
            <div class="stats-container">
                <div><i class="fa fa-thumbs-up"></i>  <span id="likes">-</span></div>
                <div><i class="fa fa-eye"></i>  <span id="viewers">-</span></div>
                <div><i class="fa fa-thumbs-down"></i> <span id="dislikes">-</span></div>


            </div>
        </div>
    </div>

    <script>
        function extractVideoId(url) {
            const match = url.match(/(?:youtube\.com\/.*v=|youtu\.be\/)([\w-]+)/);
            return match ? match[1] : url;
        }

        function setVideoId() {
            const videoId = extractVideoId(document.getElementById('videoInput').value);
            const currentUrl = new URL(window.location.href);
            const color = currentUrl.searchParams.get('color') || 'white';
            if (videoId) {
                window.history.pushState({}, '', `index.php?videoId=${videoId}&color=${color}`);
                fetchStats(videoId);
                setInterval(() => fetchStats(videoId), 10000);
            }
        }

        function fetchStats(videoId) {
    fetch(`index.php?videoId=${videoId}&ajax=1`)
        .then(response => response.json())
        .then(data => {
            if (!data.items || data.items.length === 0) throw new Error('Brak danych');
            const details = data.items[0].liveStreamingDetails;
            const stats = data.items[0].statistics;
            document.getElementById('liveTitle').textContent = data.items[0].snippet.title;

            const viewers = details?.concurrentViewers ? parseInt(details.concurrentViewers).toLocaleString('pl-PL') : 'Brak';
            const likes = stats?.likeCount ? parseInt(stats.likeCount).toLocaleString('pl-PL') : 'Brak';
            const dislikes = stats?.dislikeCount ? parseInt(stats.dislikeCount).toLocaleString('pl-PL') : 'Brak';

            document.getElementById('viewers').textContent = viewers;
            document.getElementById('likes').textContent = likes;
            document.getElementById('dislikes').textContent = dislikes;

            document.getElementById('stats').style.display = 'block';
        })
        .catch(error => console.error('Błąd:', error));
}


        function changeColor(color) {
            const currentUrl = new URL(window.location.href);
            const videoId = currentUrl.searchParams.get('videoId') || '';
            window.history.pushState({}, '', `index.php?videoId=${videoId}&color=${color}`);
            document.querySelector('.stats-container').style.color = color;
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const videoId = urlParams.get('videoId');
            const color = urlParams.get('color') || 'white';
            document.querySelector('.stats-container').style.color = color;
            if (videoId) {
                fetchStats(videoId);
                setInterval(() => fetchStats(videoId), 15000);
            }
        };
    </script>
</body>
</html>

