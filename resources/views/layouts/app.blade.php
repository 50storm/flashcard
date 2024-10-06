<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フラッシュカード</title>
     <!-- Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vue 3 CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <style>
        .flashcard-item {
            margin-bottom: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .btn-secondary {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div id="app" class="container mt-5">
        @yield('content')
    </div>
</body>
</html>
