@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">フラッシュカード一覧</h1>

        <!-- 音声ON/OFF切り替えスイッチ -->
        <div class="form-check form-switch text-center mb-4">
            <input class="form-check-input" type="checkbox" id="voiceToggle" checked>
            <label class="form-check-label" for="voiceToggle">音声読み上げ</label>
        </div>

        <!-- フラッシュカードを表示するための領域 -->
        <div id="flashcard-list" class="list-group">
            <!-- ここにフラッシュカードが表示される -->
        </div>
    </div>

    <!-- Ajaxとクリックイベントの処理 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isVoiceEnabled = true; // 音声のON/OFF状態

            // 音声ON/OFF切り替えのチェックボックスを設定
            const voiceToggle = document.getElementById('voiceToggle');
            voiceToggle.addEventListener('change', function() {
                isVoiceEnabled = this.checked;
            });

            // フラッシュカードのデータをAjaxで取得
            fetch('{{ route("flashcards.api") }}')
                .then(response => response.json())
                .then(data => {
                    const flashcardList = document.getElementById('flashcard-list');
                    flashcardList.innerHTML = '';  // 初期化

                    // 取得したデータを元にフラッシュカードを作成
                    data.forEach(flashcard => {
                        // リストアイテムを作成
                        const listItem = document.createElement('div');
                        listItem.className = 'list-group-item flashcard';
                        listItem.innerText = flashcard.japanese;  // 最初は日本語を表示

                        // クリックイベントを追加
                        listItem.addEventListener('click', function() {
                            // 現在日本語が表示されている場合は英語を表示、英語なら日本語を表示
                            if (listItem.innerText === flashcard.japanese) {
                                listItem.innerText = flashcard.english;
                                if (isVoiceEnabled) {
                                    speakText(flashcard.english, 'en-US'); // 英語を読み上げ
                                }
                            } else {
                                listItem.innerText = flashcard.japanese;
                                if (isVoiceEnabled) {
                                    speakText(flashcard.japanese, 'ja-JP'); // 日本語を読み上げ
                                }
                            }
                        });

                        // リストにフラッシュカードを追加
                        flashcardList.appendChild(listItem);
                    });
                })
                .catch(error => console.error('Error fetching flashcards:', error));
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
s