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

        .menu-icon, .user-icon {
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
        @for ($i = 0; $i < count($contents); $i += 2)
            @php
                // Left joinをすると遅くなるらしいのでviewで頑張ってみた。
                $frontContent = $contents[$i];
                // 裏のカードがない場合は、null
                $backContent = isset($contents[$i+1]) ? $contents[$i+1] : null;
            @endphp
            <div class="flashcard-container" 
                data-front-content="{{ e($frontContent->content) }}" 
                data-front-language_code="{{ $frontContent->language->language_code }}"
                data-back-content="{{ e($backContent ? $backContent->content : '裏のカードがありません') }}"
                data-back-language_code="{{ $backContent ? $backContent->language->language_code : '' }}"
                >
                <!-- 表面の表示 -->
                <span class="flashcard-front">
                    {{ $frontContent->content }} <!-- 表の内容 -->
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
                const frontLangCode = card.getAttribute('data-front-language_code');
                const backLangCode = card.getAttribute('data-back-language_code');
                
                const frontSpan = card.querySelector('.flashcard-front');

                card.addEventListener('click', function() {
                    // After speaking, the content should be changed
                    if (isFront) {
                        if (isVoiceEnabled) {
                            speakText(frontContent, frontLangCode, selectedRate, function() {
                                frontSpan.innerText = backContent; // Show back content
                                isFront = false; // Set state to back
                            });
                        } else {
                            frontSpan.innerText = backContent; // Show back content
                            isFront = false; // Set state to back
                        }
                    } else {
                        if (isVoiceEnabled) {
                            speakText(backContent, backLangCode, selectedRate, function() {
                                frontSpan.innerText = frontContent; // Show front content
                                isFront = true; // Set state to front
                            });
                        } else {
                            frontSpan.innerText = frontContent; // Show front content
                            isFront = true; // Set state to front
                        }
                    }
                });
            });

            // Log available voices to the console for debugging
            window.speechSynthesis.onvoiceschanged = function() {
                const voices = window.speechSynthesis.getVoices();
                console.log('Available voices:', voices);
            };
        });

        // Function to speak text with a callback after speech ends
        function speakText(text, lang = 'en-US', rate = 1.0, onEndCallback) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = lang;
                utterance.rate = rate;

                // Set onend event handler
                if (typeof onEndCallback === 'function') {
                    utterance.onend = onEndCallback;
                }

                function speak() {
                    const voices = window.speechSynthesis.getVoices();
                    let selectedVoice = voices.find(voice => voice.lang === lang);

                    // If exact match not found, try to find a voice that starts with the base language code
                    if (!selectedVoice) {
                        selectedVoice = voices.find(voice => voice.lang.startsWith(lang));
                    }

                    // If still not found, use default voice
                    utterance.voice = selectedVoice || null;

                    window.speechSynthesis.speak(utterance);
                }

                if (window.speechSynthesis.getVoices().length === 0) {
                    window.speechSynthesis.addEventListener('voiceschanged', speak);
                } else {
                    speak();
                }
            } else {
                alert('このブラウザは音声合成APIをサポートしていません。');
                // If speech synthesis not supported, call the callback immediately
                if (typeof onEndCallback === 'function') {
                    onEndCallback();
                }
            }
        }
    </script>
@endsection
