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
                        listItem.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="flashcard-text">${flashcard.japanese}</span>
                                <div>
                                    <button class="btn btn-sm btn-primary edit-button" data-id="${flashcard.id}">編集</button>
                                    <button class="btn btn-sm btn-danger delete-button" data-id="${flashcard.id}">削除</button>
                                </div>
                            </div>
                        `;

                        // クリックイベントで日本語と英語を切り替える
                        listItem.querySelector('.flashcard-text').addEventListener('click', function() {
                            const flashcardText = this;
                            if (flashcardText.innerText === flashcard.japanese) {
                                flashcardText.innerText = flashcard.english;
                                if (isVoiceEnabled) {
                                    speakText(flashcard.english, 'en-US'); // 英語を読み上げ
                                }
                            } else {
                                flashcardText.innerText = flashcard.japanese;
                                if (isVoiceEnabled) {
                                    speakText(flashcard.japanese, 'ja-JP'); // 日本語を読み上げ
                                }
                            }
                        });

                        // 編集ボタンのクリックイベント
                        listItem.querySelector('.edit-button').addEventListener('click', function() {
                            const flashcardId = this.getAttribute('data-id');
                            window.location.href = `/flashcards/${flashcardId}/edit`; // 編集画面に遷移
                        });

                        // 削除ボタンのクリックイベント
                        listItem.querySelector('.delete-button').addEventListener('click', function() {
                            const flashcardId = this.getAttribute('data-id');
                            if (confirm('このフラッシュカードを削除しますか？')) {
                                fetch(`api/flashcards/${flashcardId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => {
                                    if (response.ok) {
                                        alert('削除しました。');
                                        listItem.remove(); // フラッシュカードをリストから削除
                                    } else {
                                        alert('削除に失敗しました。');
                                    }
                                })
                                .catch(error => console.error('Error deleting flashcard:', error));
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
