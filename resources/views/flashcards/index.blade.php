@extends('layouts.app')
<!-- Custom CSS Section -->
@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center display-6 display-md-4">フラッシュカード一覧</h1>

        <!-- 新しいフラッシュカードを追加するフォーム -->
        <div class="mb-4">
            <form action="{{ route('flashcards.store') }}" method="POST" class="d-flex align-items-center">
                @csrf
                <input type="text" name="japanese" class="form-control me-2" placeholder="日本語" required style="max-width: 40%;">
                <input type="text" name="english" class="form-control me-2" placeholder="英語" required style="max-width: 40%;">
                <button type="submit" class="btn btn-primary" style="width: 20%;">追加</button>
            </form>
        </div>

        <!-- 音声ON/OFF切り替えスイッチ -->
        <div class="text-center mb-4">
            <input class="form-check-input" type="checkbox" id="voiceToggle" checked>
            <label class="form-check-label" for="voiceToggle">音声読み上げ</label>
        </div>

        <!-- 音声速度を調整するスライダー -->
        <div class="text-center mb-4">
            <label for="voiceRate" class="form-label">音声速度: <span id="rateValue">1.0</span></label>
            <input type="range" id="voiceRate" class="form-range" min="0.5" max="2.0" step="0.1" value="1.0" style="width: 50%; margin: auto;">
        </div>

        <!-- フラッシュカードを表示するための領域 -->
        <div id="flashcard-list" class="list-group">
        @foreach ($flashcards as $flashcard)
            <div class="list-group-item flashcard">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- テキスト部分 -->
                    <span class="flashcard-text" style="flex-grow: 1;" data-japanese="{{ $flashcard->japanese }}" data-english="{{ $flashcard->english }}">
                        {{ $flashcard->japanese }}
                    </span>
                    <!-- ボタンを縦並びにするためにflex-columnを追加 -->
                    <div class="d-flex flex-column" style="white-space: nowrap;">
                        <a href="{{ route('flashcards.edit', $flashcard->id) }}" class="btn btn-sm btn-primary mb-2">編集</a>
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
            let selectedRate = 1.0; // 音声速度の初期値

            // 音声ON/OFF切り替えのチェックボックスを設定
            const voiceToggle = document.getElementById('voiceToggle');
            voiceToggle.addEventListener('change', function() {
                isVoiceEnabled = this.checked;
            });

            // 音声速度の選択
            const voiceRate = document.getElementById('voiceRate');
            const rateValueDisplay = document.getElementById('rateValue');
            voiceRate.addEventListener('input', function() {
                selectedRate = parseFloat(this.value);
                rateValueDisplay.textContent = selectedRate.toFixed(1); // 速度表示を更新
            });

            // フラッシュカードの日本語と英語を切り替える
            document.querySelectorAll('.flashcard-text').forEach(element => {
                element.addEventListener('click', function() {
                    const japanese = this.getAttribute('data-japanese');
                    const english = this.getAttribute('data-english');

                    if (this.innerText === japanese) {
                        this.innerText = english;
                        if (isVoiceEnabled) {
                            speakText(english, 'en-US', selectedRate); // 英語を読み上げ
                        }
                    } else {
                        this.innerText = japanese;
                        if (isVoiceEnabled) {
                            speakText(japanese, 'ja-JP', selectedRate); // 日本語を読み上げ
                        }
                    }
                });
            });
        });

        // テキストを読み上げる関数
        function speakText(text, lang = 'en-US', rate = 1.0) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance();
                utterance.text = text; // 読み上げるテキスト
                utterance.lang = lang; // 言語
                utterance.rate = rate; // 音声速度を設定

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
