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
            <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#menuContent" aria-expanded="false" aria-controls="menuContent">
                <span>&#9776;</span>
            </button>
            <h1>ビジネス英会話</h1>
            <div class="user-icon">
                <span class="material-icons">person</span>
            </div>
        </div>

        <!-- メニューコンテンツ -->
        <div id="menuContent" class="collapse">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addCardModal">Add New Card</a>
                </li>
                <li class="list-group-item"><a href="#">Export CSV</a></li>
                <li class="list-group-item"><a href="#">Export Excel</a></li>
                <li class="list-group-item"><a href="#">Export HTML</a></li>
            </ul>
        </div>

        <!-- フラッシュカードのリスト -->
         <!-- TODO 削除と編集ボタンの追加 -->
        <div class="flashcards-list">
            @foreach ($flashcard->pairs as $pair)
                <div class="flashcard-container" 
                    data-front-content="{{ $pair->frontContent->content ?? 'N/A' }}" 
                    data-front-language_code="{{ $pair->frontContent->language->language_code ?? '' }}"
                    data-back-content="{{ $pair->backContent->content ?? 'N/A' }}"
                    data-back-language_code="{{ $pair->backContent->language->language_code ?? '' }}"
                    style="background-color: pink;"
                >
                    <span class="flashcard-front">
                        {{ $pair->frontContent->content }} 
                    </span>
                    <!-- Edit and Delete Buttons -->
                    <div class="mt-2">
                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $pair->id }}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $pair->id }}">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('flashcards.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <!-- 新しいカードを追加するモーダル -->
    <div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <form id="addCardForm" method="POST" action="{{ action([App\Http\Controllers\FlashcardPairApiController::class, 'createPair'], ['flashcardId' => $flashcard->id]) }}">            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="addCardModalLabel">Add New Card</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="frontContent" class="form-label">Front Side Content</label>
                <textarea class="form-control" id="frontContent" name="frontContent" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="backContent" class="form-label">Back Side Content</label>
                <textarea class="form-control" id="backContent" name="backContent" rows="3" required></textarea>
              </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isVoiceEnabled = true;
            const selectedRate = 1.0;

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

            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                attachFlashcardEvent(card);
            });

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

            const addCardForm = document.getElementById('addCardForm');
            const addCardModal = new bootstrap.Modal(document.getElementById('addCardModal'));
            const flashcardsContainer = document.querySelector('.flashcards-list');

            addCardForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const frontContent = document.getElementById('frontContent').value.trim();
                const backContent = document.getElementById('backContent').value.trim();
                const frontLanguage = document.getElementById('frontLanguage').value.trim();
                const backLanguage = document.getElementById('backLanguage').value.trim();

                if (!frontContent || !backContent || !frontLanguage || !backLanguage) {
                    alert('全てのフィールドを入力してください。');
                    return;
                }

                // Construct the correct payload
                const payload = {
                    pair: [
                        {
                            frontContent: frontContent,
                            language_code: frontLanguage
                        },
                        {
                            BackContent: backContent,
                            language_code: backLanguage
                        }
                    ]
                };

                debugger;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const apiUrl = addCardForm.getAttribute('action');

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
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            const errorMessage = errorData.errors && errorData.errors.contents ? errorData.errors.contents.join(', ') : 'エラーが発生しました。';
                            throw new Error(errorMessage);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.message && data.flashcard_pair) {
                        addNewFlashcardToDOM(data.flashcard_pair);
                        addCardForm.reset();
                        addCardModal.hide();
                        alert('新しいカードが追加されました。');
                    } else {
                        throw new Error('Invalid response from the server.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(`カードの追加に失敗しました。: ${error.message}`);
                });
            });

            function addNewFlashcardToDOM(data) {
                const frontContent = data.front_content;
                const backContent = data.back_content;

                if (!frontContent || !backContent) {
                    console.error('フロントまたはバックの内容が不足しています。');
                    alert('カードの追加に失敗しました。データが不完全です。');
                    return;
                }

                const flashcardDiv = document.createElement('div');
                flashcardDiv.classList.add('flashcard-container');
                flashcardDiv.setAttribute('data-front-content', frontContent.content);
                flashcardDiv.setAttribute('data-front-language_code', frontContent.language_code);
                flashcardDiv.setAttribute('data-back-content', backContent.content);
                flashcardDiv.setAttribute('data-back-language_code', backContent.language_code);

                flashcardDiv.innerHTML = `
                    <span class="flashcard-front">
                        ${frontContent.content}
                    </span>
                `;

                flashcardsContainer.appendChild(flashcardDiv);
                attachFlashcardEvent(flashcardDiv);
            }
            // Handle Delete Button Click
            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const pairId = this.getAttribute('data-id');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    debugger;

                    if (confirm('Are you sure you want to delete this flashcard?')) {
                        fetch(`/api/flashcards/pairs/${pairId}/delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Failed to delete flashcard.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            alert('Flashcard deleted successfully.');
                            // Remove the flashcard from the DOM
                            this.closest('.flashcard-container').remove();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert(`Failed to delete flashcard: ${error.message}`);
                        });
                    }
                });
            });

            // Handle Edit Button Click
            document.querySelectorAll('.edit-btn').forEach(function(button) {
            //  TODO  新規登録と同じようにモーダルを立ち上げて、更新
            });
        });
    </script>
@endsection
