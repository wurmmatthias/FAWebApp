<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Raufasertapete - Gildenbank</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: no-repeat center center fixed;
            background-size: cover;
            font-family: 'Figtree', sans-serif;
            overflow: hidden;
        }
        .wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            color: white;
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container img {
            width: 80px;
            height: auto;
            margin-bottom: 1rem;
        }
        .container h1 {
            margin-bottom: 1rem;
        }
        .btn-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 0.5rem;
        }
        .btn {
            display: block;
            width: 100%;
            padding-top: 15px;
            padding-bottom:15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
        }
        .btn-primary {
            background:rgb(245, 155, 20);
            color: white;
        }
        .btn-primary:hover {
            background:rgb(240, 185, 4);
        }
        .btn-secondary {
            background: white;
            color: black;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .footer {
            margin-top: auto;
            font-size: 0.9rem;
            color: white;
            text-align: center;
            padding: 1rem;
            width: 100%;
            position: absolute;
            bottom: 10px;
        }
        .wallpaper-selector {
            margin-top: 1rem;
        }
        .wallpaper-preview {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .wallpaper-preview img {
            width: 60px;
            height: 40px;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .wallpaper-preview img.selected {
            border: 2px solid yellow;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/storage/logo-test.png" width="80px" height="80px" alt="Logo">
        <h1>Raufasertapete Gildenbank</h1>
        <div class="btn-container">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Anmelden</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-secondary">Registrieren</a>
                    @endif
                @endauth
            @endif
        </div>

        <!-- Wallpaper Selection -->
        <div class="wallpaper-selector">
            <p>Wähle ein Theme:</p>
            <div class="wallpaper-preview">
                <img src="/storage/landscape.webp" onclick="setWallpaper('/storage/landscape.webp')" alt="Shadowlands">
                <img src="/storage/landscape2.webp" onclick="setWallpaper('/storage/landscape2.webp')" alt="Undermine">
                <img src="/storage/landscape3.webp" onclick="setWallpaper('/storage/landscape3.webp')" alt="Orgrimmar">
                <img src="/storage/landscape4.webp" onclick="setWallpaper('/storage/landscape4.webp')" alt="Stormwind">
            </div>
            <button class="btn btn-secondary" onclick="resetWallpaper()">Standard zurücksetzen</button>
        </div>
    </div>

    <div class="footer" style="background: rgba(0, 0, 0, 0.7);padding: 5px;">
        &copy; {{ date('Y') }} - Raufasertapete. All rights reserved. Developed by Xyssa & Jaluna.
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Load saved wallpaper
            const savedWallpaper = localStorage.getItem("selectedWallpaper");
            if (savedWallpaper) {
                document.body.style.backgroundImage = `url('${savedWallpaper}')`;
                highlightSelectedWallpaper(savedWallpaper);
            } else {
                document.body.style.backgroundImage = "url('/storage/landscape.webp')";
            }
        });

        function setWallpaper(wallpaperUrl) {
            // Apply wallpaper
            document.body.style.backgroundImage = `url('${wallpaperUrl}')`;

            // Save selection in localStorage
            localStorage.setItem("selectedWallpaper", wallpaperUrl);

            // Highlight selected wallpaper
            highlightSelectedWallpaper(wallpaperUrl);
        }

        function resetWallpaper() {
            // Reset to default
            const defaultWallpaper = "/storage/landscape.webp";
            document.body.style.backgroundImage = `url('${defaultWallpaper}')`;
            localStorage.removeItem("selectedWallpaper");
            highlightSelectedWallpaper(defaultWallpaper);
        }

        function highlightSelectedWallpaper(selectedUrl) {
            document.querySelectorAll(".wallpaper-preview img").forEach(img => {
                if (img.src.includes(selectedUrl)) {
                    img.classList.add("selected");
                } else {
                    img.classList.remove("selected");
                }
            });
        }
    </script>
</body>
</html>
