<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お知らせ一覧</title>
</head>
<body>
    <h1>お知らせ一覧</h1>

    @if($notices->isEmpty())
        <p>現在、お知らせはありません。</p>
    @else
        <ul>
            @foreach($notices as $notice)
                <li>
                    <h2>{{ $notice->title }}</h2>
                    <p>{{ $notice->content }}</p>
                    <p>開始日: {{ $notice->start_date->format('Y-m-d') }}</p>
                    @if($notice->end_date)
                        <p>終了日: {{ $notice->end_date->format('Y-m-d') }}</p>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>
