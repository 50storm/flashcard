@extends('layouts.app')

@section('styles')
    <style>
        .flashcard-container {
            border: 1px solid #000;
            padding: 20px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 1.2em;
            line-height: 1.5;
            cursor: pointer;
            background-color: #fff;
            transition: background-color 0.3s ease;
        }

        .flashcard-container:hover {
            background-color: #f0f0f0;
        }

        .flashcard-back {
            display: none; /* 裏面は非表示 */
        }

        .header-icon {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
        }

        .menu-icon, .user-icon {
            font-size: 30px;
        }

        .menu-icon {
            cursor: pointer;
        }

        .user-icon {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="header-icon">
            <!-- ハンバーガーメニューアイコン -->
            <div class="menu-icon">
                &#9776; ビジネス英会話
            </div>

            <!-- ユーザーアイコン -->
            <div class="user-icon">
                <span class="material-icons">person</span>
            </div>
        </div>

        <!-- フラッシュカードの内容を表示 -->
        @for ($i = 0; $i < count($flashcard->contents); $i += 2)
            <div class="flashcard-container" 
                 data-front-content="{{ e($flashcard->contents[$i]->content) }}" 
                 data-back-content="{{ e($flashcard->contents[$i+1]->content ?? '裏のカードがありません') }}">
                <!-- 表面の表示 -->
                <span class="flashcard-front">
                    {{ $flashcard->contents[$i]->content }} <!-- 表の内容 -->
                </span>
            </div>
        @endfor

        <!-- ボタンで他のページに戻る -->
        <div class="text-center mt-4">
            <a href="{{ route('flashcards.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isVoiceEnabled = true; // 音声のON/OFF状態
            let selectedRate = 1.0; // 音声速度の初期値

            // フラッシュカードの表と裏を切り替え、音声を再生
            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                let isFront = true; // 表か裏かの状態を管理
                const frontContent = card.getAttribute('data-front-content');
                const backContent = card.getAttribute('data-back-content');
                const frontSpan = card.querySelector('.flashcard-front');

                card.addEventListener('click', function() {
                    if (isFront) {
                        frontSpan.innerText = backContent; // 裏の内容を表示
                        if (isVoiceEnabled) {
                            speakText(backContent, 'ja-JP', selectedRate); // 裏面を読み上げ（日本語）
                        }
                    } else {
                        frontSpan.innerText = frontContent; // 表の内容を表示
                        if (isVoiceEnabled) {
                            speakText(frontContent, 'en-US', selectedRate); // 表面を読み上げ（英語）
                        }
                    }
                    isFront = !isFront; // 表裏の状態を切り替え
                });
            });
        });

        // テキストを読み上げる関数
        function speakText(text, lang = 'en-US', rate = 1.0) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = lang;
                utterance.rate = rate;

                // 音声が利用可能な場合は音声を再生
                function speak() {
                    const voices = window.speechSynthesis.getVoices();
                    utterance.voice = voices.find(voice => voice.lang === lang) || null;
                    window.speechSynthesis.speak(utterance);
                }

                if (window.speechSynthesis.getVoices().length === 0) {
                    window.speechSynthesis.addEventListener('voiceschanged', speak);
                } else {
                    speak();
                }
            } else {
                alert('このブラウザは音声合成APIをサポートしていません。');
            }
        }
    </script>
@endsection
