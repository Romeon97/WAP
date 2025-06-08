<?php $__env->startSection('content'); ?>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Weer API ophalen met PHP
$apiKey = "adbb63e85c604bba9ac124006251803";
$city = "Groningen";
$apiUrl = "https://api.weatherapi.com/v1/current.json?key=$apiKey&q=$city";

// API-call met `file_get_contents`
$response = file_get_contents($apiUrl);

// Controleer of de API correct antwoordt
if ($response === FALSE) {
    die("<h3 style='color: red;'>❌ API-fout: Kan geen weerdata ophalen!</h3>");
}

// JSON-data decoderen
$weatherData = json_decode($response, true);

// Extract weerinformatie
$temp = $weatherData['current']['temp_c'] ?? "N/A";
$realFeel = $weatherData['current']['feelslike_c'] ?? "N/A";
$windSpeed = $weatherData['current']['wind_kph'] ?? "N/A";
$windGusts = $weatherData['current']['gust_kph'] ?? "N/A";
$description = $weatherData['current']['condition']['text'] ?? "N/A";
$iconUrl = "https:" . ($weatherData['current']['condition']['icon'] ?? "");
$airQuality = "Fair"; // Mock data

// Loginstatus ophalen
$user = $_SESSION['user'] ?? "Niet ingelogd";

// Datum en tijd ophalen
$formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
$currentDate = $formatter->format(time());

$currentTime = date("H:i");
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weer App</title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
    <link rel="icon" type="image/png" href="<?php echo asset('images/LogoIWANEW.png'); ?>">

</head>
<body>

<header>


</header>

<!-- 🔹 Real-time Updates Bar -->
<section class="hero">
    <h1>Always stay updated with the latest weather</h1>
    <p>Get real-time weather updates, anywhere in the world</p>
</section>

<main>
    <section class="weather-container">
        <div class="weather-card">
            <div class="weather-header">
                <h3>TODAY'S WEATHER</h3>
                <span><?php echo strtoupper(date("D, M d")); ?></span>
            </div>
            <div class="weather-details">
                <p>☀️ Plenty of sunshine <strong>Hi:</strong> 11°C</p>
                <p>🌙 Tonight: Clear and cold <strong>Lo:</strong> -5°C</p>
            </div>
        </div>

        <div class="weather-card">
            <div class="weather-header">
                <h3>CURRENT WEATHER</h3>
            </div>
            <div class="weather-content">
                <div class="weather-main">
                    <?php if ($iconUrl): ?>
                        <img src="<?php echo $iconUrl; ?>" alt="Weather Icon">
                    <?php endif; ?>
                    <h1><?php echo $temp; ?>°C</h1>
                    <p>Feels like <span><?php echo $realFeel; ?>°C</span></p>
                    <p><strong><?php echo $description; ?></strong></p>
                </div>
                <div class="weather-info">
                    <p><strong>🌡 Shade™</strong> <span><?php echo $realFeel - 2; ?>°C</span></p>
                    <p><strong>💨 Wind:</strong> E <span><?php echo $windSpeed; ?> km/h</span></p>
                    <p><strong>💨 Wind Gusts:</strong> <span><?php echo $windGusts; ?> km/h</span></p>
                    <p><strong>🌿 Air Quality:</strong> <span><?php echo $airQuality; ?></span></p>
                </div>
            </div>
        </div>

        <div class="weather-card">
            <h3>LOOKING AHEAD</h3>
            <p>Becoming noticeably warmer in the upcoming weeks</p>
        </div>

        <div class="weather-card">
            <h3>GRONINGEN WEATHER RADAR & MAPS</h3>

            <!-- OpenStreetMap Iframe -->
            <iframe
                width="100%"
                height="350"
                frameborder="0"
                style="border:0"
                src="https://www.openstreetmap.org/export/embed.html?bbox=6.555,53.200,6.615,53.220&layer=mapnik"
                allowfullscreen>
            </iframe>

            <p><a href="https://www.openstreetmap.org/#map=12/53.2192/6.5667" target="_blank">View Larger Map</a></p>
        </div>

    </section>
</main>

</body>
</html>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/index.blade.php ENDPATH**/ ?>