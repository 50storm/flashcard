@extends('layouts.app')

@section('head')
    <!-- CSRFトークンのメタタグをheadセクションに追加 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

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

        /* フラッシュカードのリストコンテナ */
        .flashcards-list {
            margin-top: 20px;
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
            <!-- Menu Items -->
            <ul class="list-group">
                <!-- "Add New Card"をクリックするとモーダルが開く -->
                <li class="list-group-item">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addCardModal">Add New Card</a>
                </li>
                <li class="list-group-item"><a href="#">Export CSV</a></li>
                <li class="list-group-item"><a href="#">Export Excel</a></li>
                <li class="list-group-item"><a href="#">Export HTML</a></li>
            </ul>
        </div>

        <!-- フラッシュカードのリスト -->
        <div class="flashcards-list">
            <!-- フラッシュカードの内容を表示 -->
            @foreach ($flashcard->pairs as $pair)
                <div class="flashcard-container" 
                    data-front-content="{{ $pair->frontContent->content ?? 'N/A' }}" 
                    data-front-language_code="{{ $pair->frontContent->language->language_code ?? '' }}"
                    data-back-content="{{ $pair->backContent->content ?? 'N/A' }}"
                    data-back-language_code="{{ $pair->frontContent->language->language_code ?? '' }}"
                >
                    <!-- 表面の表示 -->
                    <span class="flashcard-front">
                        {{ $pair->frontContent->content }} 
                    </span>
                </div>
            @endforeach
        </div>

        <!-- 一覧に戻るボタン -->
        <div class="text-center mt-4">
            <a href="{{ route('flashcards.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <!-- 新しいカードを追加するモーダル -->
    <div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <!-- TODO ajaxで -->
        <form id="addCardForm" method="POST" action="{{ route('api.contents.storeFrontAndBackContents', $flashcard->id) }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="addCardModalLabel">Add New Card</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Front Side Input -->
              <div class="mb-3">
                <label for="frontContent" class="form-label">Front Side Content</label>
                <textarea class="form-control" id="frontContent" name="frontContent" rows="3" required></textarea>
              </div>
              <!-- Back Side Input -->
              <div class="mb-3">
                <label for="backContent" class="form-label">Back Side Content</label>
                <textarea class="form-control" id="backContent" name="backContent" rows="3" required></textarea>
              </div>
             <!-- Front Language Code (Dropdown) -->
             <div class="mb-3">
                <label for="frontLanguage" class="form-label">Front Side Language</label>
                <select class="form-select" id="frontLanguage" name="frontContent[language_code]" required>
                    <option value="" disabled selected>Select Language</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->language_code }}" {{ old('frontContent.language_code') == $language->language_code ? 'selected' : '' }}>
                                {{ $language->language }} ({{ $language->language_code }})
                            </option>
                        @endforeach
                </select>
            </div>
            <!-- Back Language Code (Dropdown) -->
            <div class="mb-3">
                <label for="backLanguage" class="form-label">Back Side Language</label>
                <select class="form-select" id="backLanguage" name="backContent[language_code]" required>
                    <option value="" disabled selected>Select Language</option>
                    @foreach($languages as $language)
                        <option value="{{ $language->language_code }}" {{ old('backContent.language_code') == $language->language_code ? 'selected' : '' }}>
                            {{ $language->language }} ({{ $language->language_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add Card</button>
            </div>
          </form>
        </div>
      </div>
    </div>


    <!-- あなたのカスタムスクリプト -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            debugger;
            let isVoiceEnabled = true;
            let selectedRate = 1.0;

            // フラッシュカードの機能
            function attachFlashcardEvent(card) {
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
            }

            // 既存のフラッシュカードにイベントを設定
            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                attachFlashcardEvent(card);
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

            // ------ 追加するコード ------
            // "Add New Card"フォームの送信をAJAXで処理
            //  新しいフラッシュカードをDOMに追加する関数

            // フォーム、モーダル、コンテナの取得
            const addCardForm = document.getElementById('addCardForm');
            const addCardModal = new bootstrap.Modal(document.getElementById('addCardModal'));
            const flashcardsContainer = document.querySelector('.flashcards-list'); // フラッシュカードが含まれるコンテナを指定

            addCardForm.addEventListener('submit', function(event) {
                event.preventDefault(); // デフォルトのフォーム送信を防ぐ

                // フォームデータの取得
                const frontContent = document.getElementById('frontContent').value.trim();
                const backContent = document.getElementById('backContent').value.trim();
                const frontLanguage = document.getElementById('frontLanguage').value.trim();
                const backLanguage = document.getElementById('backLanguage').value.trim();

                // バリデーション（簡易）
                if (!frontContent || !backContent || !frontLanguage || !backLanguage) {
                    alert('全てのフィールドを入力してください。');
                    return;
                }

                // ペイロードの作成
                const payload = {
                    contents: [
                        {
                            content: frontContent,
                            language_code: frontLanguage,
                            side_type: 0
                        },
                        {
                            content: backContent,
                            language_code: backLanguage,
                            side_type: 1
                        }
                    ]
                };

                // CSRFトークンの取得（headセクションに追加したmetaタグから）
                debugger;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // APIエンドポイントのURL（動的に取得）
                const apiUrl = addCardForm.getAttribute('action');

                // AJAXリクエストの送信
                fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => {
                    debugger;
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            // バリデーションエラーがある場合
                            const errorMessage = errorData.errors && errorData.errors.contents ? errorData.errors.contents.join(', ') : 'エラーが発生しました。';
                            throw new Error(errorMessage);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    debugger;
                    // 成功時の処理
                    // 新しいフラッシュカードをDOMに追加
                    addNewFlashcardToDOM(data);

                    // フォームのリセット
                    addCardForm.reset();

                    // モーダルを閉じる
                    addCardModal.hide();

                    alert('新しいカードが追加されました。');
                })
                .catch(error => {
                    // エラーハンドリング
                    console.error('Error:', error);
                    alert(`カードの追加に失敗しました。: ${error.message}`);
                });
            });

            // 新しいフラッシュカードをDOMに追加する関数
            function addNewFlashcardToDOM(data) {
                // デバッグ用にログを出力
                console.log('Received data:', data);

                // data.contents がフロントとバックの内容を含むと仮定
                const contents = data.contents;

                const frontContent = contents.find(c => c.side_type === 0);
                const backContent = contents.find(c => c.side_type === 1);

                // 必要なデータが揃っているか確認
                if (!frontContent || !backContent) {
                    console.error('フロントまたはバックの内容が不足しています。');
                    alert('カードの追加に失敗しました。データが不完全です。');
                    return;
                }

                // 新しいフラッシュカードのHTMLを作成
                const flashcardDiv = document.createElement('div');
                flashcardDiv.classList.add('flashcard-container');
                flashcardDiv.setAttribute('data-front-content', frontContent.content);
                flashcardDiv.setAttribute('data-front-language_code', frontContent.language_code);
                flashcardDiv.setAttribute('data-back-content', backContent.content);
                flashcardDiv.setAttribute('data-back-language_code', backContent.language_code);

                // 表面の内容を設定
                flashcardDiv.innerHTML = `
                    <span class="flashcard-front">
                        ${frontContent.content}
                    </span>
                `;

                // フラッシュカードを既存のカードの最後に追加
                flashcardsContainer.appendChild(flashcardDiv);

                // 新しいカードにイベントを設定
                attachFlashcardEvent(flashcardDiv);
            }
        });
    </script>
@endsection
