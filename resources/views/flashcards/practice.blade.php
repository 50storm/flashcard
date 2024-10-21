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
            display: none;
        }

        .header-icon {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
        }

        .menu-icon, .user-icon {
            font-size: 30px;
            cursor: pointer;
        }

        /* メニューのスタイル */
        #menuContent {
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- ヘッダー部分 -->
        <div class="header-icon">
            <!-- メニューボタン -->
            <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#menuContent" aria-expanded="false" aria-controls="menuContent">
                <span>&#9776;</span>
            </button>

            <h1>ビジネス英会話</h1>

            <!-- ユーザーアイコン -->
            <div class="user-icon">
                <span class="material-icons">person</span>
            </div>
        </div>

        <!-- メニューコンテンツ -->
        <div id="menuContent" class="collapse">
            <!-- メニュー項目 -->
            <ul class="list-group">
                <!-- 英語にして -->
                <li class="list-group-item"><a href="#">新しいカード追加</a></li>
                <li class="list-group-item"><a href="#">CSV出力</a></li>
                <li class="list-group-item"><a href="#">Excel出力</a></li>
                <li class="list-group-item"><a href="#">HTML出力</a></li>
            </ul>
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

        <!-- 一覧に戻るボタン -->
        <div class="text-center mt-4">
            <a href="{{ route('flashcards.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </div>
    </div>

    <!-- あなたのカスタムスクリプト -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isVoiceEnabled = true;
            let selectedRate = 1.0;

            // フラッシュカードの機能
            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                let isFront = true;
                const frontContent = card.getAttribute('data-front-content');
                const backContent = card.getAttribute('data-back-content');
                const frontLangCode = card.getAttribute('data-front-language_code') || 'en-US';
                const backLangCode = card.getAttribute('data-back-language_code') || 'ja';
                const frontSpan = card.querySelector('.flashcard-front');

                card.addEventListener('click', function() {
                    if (isFront) {
                        if (isVoiceEnabled) {
                            speakText(frontContent, frontLangCode, selectedRate, function() {
                                frontSpan.innerText = backContent;
                                isFront = false;
                            });
                        } else {
                            frontSpan.innerText = backContent;
                            isFront = false;
                        }
                    } else {
                        if (isVoiceEnabled) {
                            speakText(backContent, backLangCode, selectedRate, function() {
                                frontSpan.innerText = frontContent;
                                isFront = true;
                            });
                        } else {
                            frontSpan.innerText = frontContent;
                            isFront = true;
                        }
                    }
                });
            });

            // テキストを読み上げる関数
            function speakText(text, lang = 'en-US', rate = 1.0, onEndCallback) {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = lang;
                    utterance.rate = rate;

                    if (typeof onEndCallback === 'function') {
                        utterance.onend = onEndCallback;
                    }

                    function speak() {
                        const voices = window.speechSynthesis.getVoices();
                        let selectedVoice = voices.find(voice => voice.lang === lang);

                        if (!selectedVoice) {
                            selectedVoice = voices.find(voice => voice.lang.startsWith(lang.split('-')[0]));
                        }

                        if (!selectedVoice) {
                            console.warn(`No voice found for language code: ${lang}`);
                            selectedVoice = voices[0];
                        }

                        utterance.voice = selectedVoice;
                        window.speechSynthesis.speak(utterance);
                    }

                    if (window.speechSynthesis.getVoices().length === 0) {
                        window.speechSynthesis.addEventListener('voiceschanged', speak);
                    } else {
                        speak();
                    }
                } else {
                    alert('このブラウザは音声合成APIをサポートしていません。');
                    if (typeof onEndCallback === 'function') {
                        onEndCallback();
                    }
                }
            }
        });
    </script>
@endsection
