<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>フラッシュカードアプリ</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        /* Tailwind styles for simplicity */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f9fafb;
        }
        .header-title {
            font-size: 3rem;
            font-weight: 700;
            color: #FF2D20;
        }
        .subtitle {
            font-size: 1.25rem;
            color: #555;
        }
        .btn-primary {
            background-color: #FF2D20;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #e02416;
        }
        .features {
            display: flex;
            justify-content: space-around;
            margin-top: 4rem;
        }
        .feature-item {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0px 14px 34px 0px rgba(0, 0, 0, 0.08);
            width: 30%;
            text-align: center;
        }
        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .feature-description {
            font-size: 1rem;
            color: #666;
        }
        footer {
            margin-top: 4rem;
            text-align: center;
            padding: 2rem 0;
            color: #777;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Landing Page Section -->
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="text-center">
            <h1 class="header-title">フラッシュカードアプリ</h1>
            <p class="subtitle mt-4">学習を簡単に！語彙力を鍛える最適な方法</p>


        </div>

        <div>
            <form action="/export-users-csv" method="GET" >
                @csrf
                <!-- <input type="file" name="file"> -->
                <button type="submit">Export Users</button>
            </form>
        </div>
        <!-- Features Section -->      
        <div class="features mt-12">
            <div class="feature-item">
                <h2 class="feature-title">シンプルな操作</h2>
                <p class="feature-description">シンプルなインターフェースでフラッシュカードを素早く作成し、学習を楽に管理できます。</p>
            </div>
            <div class="feature-item">
                <h2 class="feature-title">カスタマイズ可能</h2>
                <p class="feature-description">自分の学習スタイルに合わせてフラッシュカードをカスタマイズしましょう。</p>
            </div>
            <div class="feature-item">
                <h2 class="feature-title">どこでも学習</h2>
                <p class="feature-description">どんなデバイスでもアクセスでき、いつでもどこでも学習が可能です。</p>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; 2024 フラッシュカードアプリ. All rights reserved.
    </footer>
</body>
</html>
