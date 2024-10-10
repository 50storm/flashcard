@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">フラッシュカード一覧</h1>

        <!-- 新しいフラッシュカードを追加するフォーム -->
        <div class="mb-4">
            <form action="{{ route('flashcards.store') }}" method="POST" class="d-flex justify-content-center">
                @csrf
                <input type="text" name="japanese" class="form-control me-2" placeholder="日本語" required>
                <input type="text" name="english" class="form-control me-2" placeholder="英語" required>
                <button type="submit" class="btn btn-success">追加</button>
            </form>
        </div>

        <!-- 音声ON/OFF切り替えスイッチ -->
        <div class="form-check form-switch text-center mb-4">
            <input class="form-check-input" type="checkbox" id="voiceToggle" checked>
            <label class="form-check-label" for="voiceToggle">音声読み上げ</label>
        </div>

        <!-- フラッシュカードを表示するための領域 -->
        <div id="flashcard-list" class="list-group">
            @foreach ($flashcards as $flashcard)
                <div class="list-group-item flashcard">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="flashcard-text" data-japanese="{{ $flashcard->japanese }}" data-english="{{ $flashcard->english }}">
                            {{ $flashcard->japanese }}
                        </span>
                        <div>
                            <a href="{{ route('flashcards.edit', $flashcard->id) }}" class="btn btn-sm btn-primary">編集</a>
                            <form action="{{ route('flashcards.destroy', $flashcard->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('このフラッシュカードを削除しますか？')">削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isVoiceEnabled = true; // 音声のON/OFF状態

            // 音声ON/OFF切り替えのチェックボックスを設定
            const voiceToggle = document.getElementById('voiceToggle');
            voiceToggle.addEventListener('change', function() {
                isVoiceEnabled = this.checked;
            });

            // フラッシュカードの日本語と英語を切り替える
            document.querySelectorAll('.flashcard-text').forEach(element => {
                element.addEventListener('click', function() {
                    const japanese = this.getAttribute('data-japanese');
                    const english = this.getAttribute('data-english');

                    if (this.innerText === japanese) {
                        this.innerText = english;
                        if (isVoiceEnabled) {
                            speakText(english, 'en-US'); // 英語を読み上げ
                        }
                    } else {
                        this.innerText = japanese;
                        if (isVoiceEnabled) {
                            speakText(japanese, 'ja-JP'); // 日本語を読み上げ
                        }
                    }
                });
            });
        });

        // テキストを読み上げる関数
        function speakText(text, lang = 'en-US') {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance();
                utterance.text = text;
                utterance.lang = lang;

                // 音声が利用可能な場合は音声を再生
                const voices = window.speechSynthesis.getVoices();
                if (voices.length === 0) {
                    window.speechSynthesis.onvoiceschanged = () => {
                        window.speechSynthesis.speak(utterance);
                    };
                } else {
                    window.speechSynthesis.speak(utterance);
                }
            } else {
                alert('このブラウザは音声合成APIをサポートしていません。');
            }
        }
    </script>
@endsection
