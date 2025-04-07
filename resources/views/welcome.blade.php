<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Raufasertapete - Gildenbank</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            display: flex;
            align-items: center;
            justify-content: center; /* Ensures everything inside is centered */
            gap: 10px; /* Adds space between the icon and text */
            width: 100%;
            font-size: 1.1rem;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s, transform 0.2s;
            color: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: scale(1.05);
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
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }
        .btn-blue { background-color: #3498db; color: white; }
        .btn-green { background-color: #2ecc71; color: white; }
        .btn-orange { background-color: #e67e22; color: white; }
        .btn-red { background-color:rgb(230, 34, 34); color: white; }
        .bn-logo {
            width: 24px !important;
            height: 24px;
            object-fit: contain;
        }
        .bn-spinner {
            width: 80px;  /* Adjust size if needed */
            height: 80px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .modal-load {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            color: #000;
        }

        .modal-content-load {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body x-data="themeHandler()" :style="{ backgroundImage: selectedBackground ? `url('${selectedBackground}')` : '' }">
    <div class="container">
        <img src="/storage/logo-test.png" width="80px" height="80px" alt="Logo">
        <h1>Raufasertapete Gildenbank</h1>
        <div class="btn-container">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary" :class="selectedButtonTheme">Dashboard</a>
                @else
                    <!-- Wrap the login button inside the Alpine component -->
                    <div x-data="loadingHandler()">
                        <!-- Loading Modal -->
                        <template x-if="loading">
                            <div class="modal-load">
                                <div class="modal-content-load">
                                    <h2>Battle.net Anmeldung</h2>
                                    <p>Bitte warten... Sie werden weitergeleitet.</p>
                                    <img src="https://static-00.iconduck.com/assets.00/battlenet-icon-2047x2048-fymgd2pk.png" class="bn-spinner" alt="Loading...">
                                    </div>
                            </div>
                        </template>

                        <!-- Battle.net Login Button -->
                        <a href="#"
                        class="btn btn-primary"
                        :class="selectedButtonTheme"
                        @click="showLoadingModal()">
                            <img src="https://static-00.iconduck.com/assets.00/battlenet-icon-2047x2048-fymgd2pk.png" class="bn-logo">
                            <span>Anmelden mit Battle.net</span>
                        </a>
                    </div>

                    <a href="{{ route('login') }}" class="btn btn-primary" :class="selectedButtonTheme">Anmelden</a>
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
                <img src="/storage/landscape.webp" onclick="setWallpaper('/storage/landscape.webp')" alt="Shadowlands" :class="{ 'image-selected': selectedBackground === 'landscape1.jpg' }" @click="setTheme('landscape1.jpg', 'btn-green')">
                <img src="/storage/landscape2.webp" onclick="setWallpaper('/storage/landscape2.webp')" alt="Undermine" :class="{ 'image-selected': selectedBackground === 'landscape1.jpg' }" @click="setTheme('landscape1.jpg', 'btn-orange')">
                <img src="/storage/landscape3.webp" onclick="setWallpaper('/storage/landscape3.webp')" alt="Orgrimmar" :class="{ 'image-selected': selectedBackground === 'landscape1.jpg' }" @click="setTheme('landscape1.jpg', 'btn-red')">
                <img src="/storage/landscape4.webp" onclick="setWallpaper('/storage/landscape4.webp')" alt="Stormwind" :class="{ 'image-selected': selectedBackground === 'landscape1.jpg' }" @click="setTheme('landscape1.jpg', 'btn-blue')">
            </div>
            <button class="btn btn-secondary" onclick="resetWallpaper()">Standard zurücksetzen</button>
        </div>
    </div>

    <div class="footer" style="background: rgba(0, 0, 0, 0.7);padding: 5px;">
        &copy; {{ date('Y') }} - Raufasertapete. All rights reserved. Developed by Xyssa & Jaluna.
    </div>


    <!--<div x-data="{ show: localStorage.getItem('announcementSeen') ? false : true }">
        <template x-if="show">
            <div class="modal">
                <div class="modal-content">
                    <h2>Hinweis</h2>
                    <p>Die Registierung und Anmeldung in dieser App dienen ausschließlich Demozwecken. Wir arbeiten an einer Authentifizierung via Battle.Net Konto.</p>
                    <button @click="show = false; localStorage.setItem('announcementSeen', 'true')">Ja ja passt schon, sei still!</button>
                </div>
            </div>
        </template>
    </div>-->

    <div x-data="loadingHandler()">
    <!-- Loading Modal -->
    <template x-if="loading">
        <div class="modal-load">
            <div class="modal-content-load">
                <h2>Battle.net Anmeldung</h2>
                <p>Bitte warten... Die Anmeldung läuft!</p>
                <div class="spinner"></div>
            </div>
        </div>
    </template>


<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('loadingHandler', () => ({
            loading: false,
            showLoadingModal() {
                this.loading = true; // Show loading modal

                // Redirect after showing the modal
                setTimeout(() => {
                    window.location.href = "{{ url('/auth/battlenet/redirect') }}";
                }, 500);
            }
        }));
    });
</script>



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

        function themeHandler() {
            return {
                selectedBackground: localStorage.getItem('selectedBackground') || '',
                selectedButtonTheme: localStorage.getItem('selectedButtonTheme') || 'btn-blue',

                setTheme(background, buttonTheme) {
                    this.selectedBackground = background;
                    this.selectedButtonTheme = buttonTheme;

                    localStorage.setItem('selectedBackground', background);
                    localStorage.setItem('selectedButtonTheme', buttonTheme);
                }
            }
        }

    </script>
</body>
</html>
