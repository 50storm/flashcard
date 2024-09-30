<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>パスワードリセットのご案内</title>
    <style>
        /* 必要に応じてスタイルを追加 */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <p>こんにちは、{{ $user->name }}さん！</p>

    <p>あなたのパスワードリセットリクエストを受け付けました。以下のリンクをクリックしてパスワードをリセットしてください。</p>

    <p>
        <a href="{{ $resetUrl }}" class="button">パスワードをリセット</a>
    </p>

    <p>このリクエストに心当たりがない場合は、このメールを無視してください。</p>

    <p>よろしくお願いします。<br>
    {{ config('app.name') }}</p>
</body>
</html>
