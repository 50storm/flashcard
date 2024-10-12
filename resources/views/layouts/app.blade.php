<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フラッシュカード</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
