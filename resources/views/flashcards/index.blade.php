@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">フラッシュカード一覧</h1>

        <!-- 成功メッセージの表示 -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- 新しいフラッシュカードを追加するボタン -->
        <div class="text-right mb-3">
            <a href="{{ route('flashcards.create') }}" class="btn btn-success">新しいフラッシュカードを追加</a>
        </div>

        <!-- フラッシュカードのリスト -->
        <ul class="list-group">
            @foreach($flashcards as $flashcard)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $flashcard->english }} - {{ $flashcard->japanese }}</span>

                    <!-- 音声読み上げボタン -->
                    <button class="btn btn-sm btn-secondary" onclick="speakText({{ json_encode($flashcard->english) }}, 'en-US')">英語を読み上げ</button>
                    <button class="btn btn-sm btn-secondary" onclick="speakText({{ json_encode($flashcard->japanese) }}, 'ja-JP')">日本語を読み上げ</button>

                    <!-- 編集・削除ボタン -->
                    <div>
                        <a href="{{ route('flashcards.edit', $flashcard->id) }}" class="btn btn-sm btn-primary">編集</a>

                        <!-- 削除ボタン -->
                        <form action="{{ route('flashcards.destroy', $flashcard->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('本当に削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">削除</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- 音声読み上げのスクリプト -->
    <script>
        function speakText(text, lang = 'en-US') {
            // Web Speech API がサポートされているか確認
            if ('speechSynthesis' in window) {
                const speech = new SpeechSynthesisUtterance();
                speech.text = text;
                speech.lang = lang;

                // 音声合成が準備できるまで待機してから読み上げる
                const voices = window.speechSynthesis.getVoices();
                if (voices.length === 0) {
                    // 音声リストの読み込み完了を待機してから実行
                    window.speechSynthesis.onvoiceschanged = () => {
                        window.speechSynthesis.speak(speech);
                    };
                } else {
                    // 音声リストがすでに準備完了ならそのまま実行
                    window.speechSynthesis.speak(speech);
                }
            } else {
                alert('このブラウザは音声合成APIをサポートしていません。');
            }
        }
    </script>
@endsection
