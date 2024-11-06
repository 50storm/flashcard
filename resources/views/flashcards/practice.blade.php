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
            position: relative;
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

        /* Edit and Delete Buttons */
        .action-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .action-buttons .btn {
            margin-left: 5px;
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

            @if(!empty($flashcard->name))
               <h1>{{$flashcard->name}}</h1>
            @endif

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
                <li class="list-group-item"><a href="{{ url('/flashcards/' . $flashcard->id . '/export') }}">Export CSV</a></li>
                <li class="list-group-item"><a href="#">Export Excel</a></li>
                <li class="list-group-item"><a href="#">Export HTML</a></li>
            </ul>
        </div>

        <!-- フラッシュカードのリスト -->
        <div class="flashcards-list">
            <!-- フラッシュカードの内容を表示 -->
            @foreach ($flashcard->pairs as $pair)
                <div class="flashcard-container" 
                    data-pair-id="{{ $pair->id }}"
                    data-front-content="{{ $pair->frontContent->content ?? 'N/A' }}" 
                    data-front-language_code="{{ $pair->frontContent->language->language_code ?? '' }}"
                    data-back-content="{{ $pair->backContent->content ?? 'N/A' }}"
                    data-back-language_code="{{ $pair->backContent->language->language_code ?? '' }}"
                    style="background-color: pink;">
                    
                    <span class="flashcard-front">
                        {{ $pair->frontContent->content }} 
                    </span>

                    <!-- Edit and Delete Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $pair->id }}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $pair->id }}">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

        @empty($flashcard->name)
        <div class="text-center mt-4">
                <h1>No Card</h1>
        </div>
        @endempty


        <!-- 一覧に戻るボタン -->
        <div class="text-center mt-4">
            <a href="{{ route('flashcards.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <!-- 新しいカードを追加するモーダル -->
    <div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <form id="addCardForm" method="POST" action="{{ action([App\Http\Controllers\FlashcardPairApiController::class, 'createPair'], ['flashcardId' => $flashcard->id]) }}">
                @csrf
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

    <!-- あなたのカスタムスクリプト -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addCardForm = document.getElementById('addCardForm');
            const addCardModalElement = document.getElementById('addCardModal');
            const addCardModal = new bootstrap.Modal(addCardModalElement);
            const flashcardsContainer = document.querySelector('.flashcards-list');

            let isEditMode = false;
            let editPairId = null;

            // Function to attach flashcard events
            function attachFlashcardEvent(card) {
                let isFront = true;
                
                card.addEventListener('click', function() {
                    const frontContent = card.getAttribute('data-front-content');
                    const backContent = card.getAttribute('data-back-content');
                    const frontLangCode = card.getAttribute('data-front-language_code') || 'en-US';
                    const backLangCode = card.getAttribute('data-back-language_code') || 'ja';
                    const frontSpan = card.querySelector('.flashcard-front');

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

            // Initialize existing flashcards
            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                attachFlashcardEvent(card);
            });

            const isVoiceEnabled = true;
            const selectedRate = 1.0;

            // Text-to-Speech Function
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

            // Handle Delete Button Click
            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent triggering the flashcard flip

                    const pairId = this.getAttribute('data-id');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    if (confirm('このフラッシュカードを削除してもよろしいですか？')) {
                        fetch(`/api/flashcards/pairs/${pairId}/delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(errorData => {
                                    const errorMessage = errorData.errors ? errorData.errors.join(', ') : 'エラーが発生しました。';
                                    throw new Error(errorMessage);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            alert(data.message || 'フラッシュカードが削除されました。');
                            // Remove the flashcard from the DOM
                            this.closest('.flashcard-container').remove();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert(`フラッシュカードの削除に失敗しました。: ${error.message}`);
                        });
                    }
                });
            });

            // Handle Edit Button Click
            document.querySelectorAll('.edit-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent triggering the flashcard flip

                    const pairId = this.getAttribute('data-id');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Fetch the existing flashcard data
                    fetch(`/api/flashcards/pairs/${pairId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('フラッシュカードの詳細情報を取得できませんでした。');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Populate the modal form fields with existing data
                            document.getElementById('frontContent').value = data.front_content.content;
                            document.getElementById('frontLanguage').value = data.front_content.language_code;
                            document.getElementById('backContent').value = data.back_content.content;
                            document.getElementById('backLanguage').value = data.back_content.language_code;

                            // Update the form action to the update endpoint
                            addCardForm.setAttribute('action', `/api/flashcards/pairs/${pairId}/update`);

                            // Change the modal title and submit button text
                            document.getElementById('addCardModalLabel').innerText = 'Edit Card';
                            addCardForm.querySelector('.btn-primary').innerText = 'Save Changes';

                            // Set edit mode flag
                            isEditMode = true;
                            editPairId = pairId;

                            // Show the modal
                            addCardModal.show();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('フラッシュカードの詳細情報の取得に失敗しました。');
                        });
                });
            });

            // Handle Form Submission for Create and Update
            addCardForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const frontContent = document.getElementById('frontContent').value.trim();
                const backContent = document.getElementById('backContent').value.trim();
                const frontLanguage = document.getElementById('frontLanguage').value.trim();
                const backLanguage = document.getElementById('backLanguage').value.trim();
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const apiUrl = addCardForm.getAttribute('action');
                const isUpdate = isEditMode; // Determine if it's an update

                // Simple validation
                if (!frontContent || !backContent || !frontLanguage || !backLanguage) {
                    alert('全てのフィールドを入力してください。');
                    return;
                }

                // Construct the payload
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

                // Disable the submit button to prevent multiple submissions
                const submitButton = addCardForm.querySelector('.btn-primary');
                submitButton.disabled = true;

                // Send the AJAX request
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
                    submitButton.disabled = false;
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            const errorMessage = errorData.errors && errorData.errors.pair ? errorData.errors.pair.join(', ') : 'エラーが発生しました。';
                            throw new Error(errorMessage);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.message && data.flashcard_pair) {
                        if (isUpdate) {
                            // Update existing flashcard in the DOM
                            const updatedPair = data.flashcard_pair;
                            const existingFlashcard = document.querySelector(`.flashcard-container[data-pair-id="${updatedPair.id}"]`);
                            if (existingFlashcard) {
                                existingFlashcard.setAttribute('data-front-content', updatedPair.front_content.content);
                                existingFlashcard.setAttribute('data-front-language_code', updatedPair.front_content.language_code);
                                existingFlashcard.setAttribute('data-back-content', updatedPair.back_content.content);
                                existingFlashcard.setAttribute('data-back-language_code', updatedPair.back_content.language_code);
                                
                                existingFlashcard.querySelector('.flashcard-front').innerText = updatedPair.front_content.content;
                            }
                            alert('フラッシュカードが更新されました。');
                        } else {
                            // Add new flashcard to the DOM
                            addNewFlashcardToDOM(data.flashcard_pair);
                            alert('新しいフラッシュカードが追加されました。');
                        }

                        // Reset the form and modal
                        addCardForm.reset();
                        addCardModal.hide();
                        // Reset form action and modal title
                        addCardForm.setAttribute('action', `{{ action([App\Http\Controllers\FlashcardPairApiController::class, 'createPair'], ['flashcardId' => $flashcard->id]) }}`);
                        document.getElementById('addCardModalLabel').innerText = 'Add New Card';
                        addCardForm.querySelector('.btn-primary').innerText = 'Add Card';
                        // Reset edit mode
                        isEditMode = false;
                        editPairId = null;
                    } else {
                        throw new Error('Invalid response from the server.');
                    }
                })
                .catch(error => {
                    submitButton.disabled = false;
                    console.error('Error:', error);
                    alert(`操作に失敗しました。: ${error.message}`);
                });
            });

            // Function to add a new flashcard to the DOM
            function addNewFlashcardToDOM(data) {
                const frontContent = data.front_content;
                const backContent = data.back_content;

                if (!frontContent || !backContent) {
                    console.error('フロントまたはバックの内容が不足しています。');
                    alert('フラッシュカードの追加に失敗しました。データが不完全です。');
                    return;
                }

                const flashcardDiv = document.createElement('div');
                flashcardDiv.classList.add('flashcard-container');
                flashcardDiv.setAttribute('data-pair-id', data.id);
                flashcardDiv.setAttribute('data-front-content', frontContent.content);
                flashcardDiv.setAttribute('data-front-language_code', frontContent.language_code);
                flashcardDiv.setAttribute('data-back-content', backContent.content);
                flashcardDiv.setAttribute('data-back-language_code', backContent.language_code);
                flashcardDiv.style.backgroundColor = 'pink';

                flashcardDiv.innerHTML = `
                    <span class="flashcard-front">
                        ${frontContent.content}
                    </span>
                    <div class="action-buttons">
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${data.id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">Delete</button>
                    </div>
                `;

                // Append the new flashcard to the container
                flashcardsContainer.appendChild(flashcardDiv);

                // Attach event listeners to the new buttons
                attachFlashcardEvent(flashcardDiv);

                // Attach Edit and Delete button event listeners
                const newEditBtn = flashcardDiv.querySelector('.edit-btn');
                const newDeleteBtn = flashcardDiv.querySelector('.delete-btn');

                newEditBtn.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent triggering the flashcard flip
                    // Reuse the Edit Button handler
                    // (You can refactor this into a separate function if needed)
                    const pairId = this.getAttribute('data-id');

                    // Fetch the existing flashcard data
                    fetch(`/api/flashcards/pairs/${pairId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('フラッシュカードの詳細情報を取得できませんでした。');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Populate the modal form fields with existing data
                            document.getElementById('frontContent').value = data.front_content.content;
                            document.getElementById('frontLanguage').value = data.front_content.language_code;
                            document.getElementById('backContent').value = data.back_content.content;
                            document.getElementById('backLanguage').value = data.back_content.language_code;

                            // Update the form action to the update endpoint
                            addCardForm.setAttribute('action', `/api/flashcards/pairs/${pairId}/update`);

                            // Change the modal title and submit button text
                            document.getElementById('addCardModalLabel').innerText = 'Edit Card';
                            addCardForm.querySelector('.btn-primary').innerText = 'Save Changes';

                            // Set edit mode flag
                            isEditMode = true;
                            editPairId = pairId;

                            // Show the modal
                            addCardModal.show();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('フラッシュカードの詳細情報の取得に失敗しました。');
                        });
                });

                newDeleteBtn.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent triggering the flashcard flip

                    const pairId = this.getAttribute('data-id');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    if (confirm('このフラッシュカードを削除してもよろしいですか？')) {
                        fetch(`/api/flashcards/pairs/${pairId}/delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(errorData => {
                                    const errorMessage = errorData.errors ? errorData.errors.join(', ') : 'エラーが発生しました。';
                                    throw new Error(errorMessage);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            alert(data.message || 'フラッシュカードが削除されました。');
                            // Remove the flashcard from the DOM
                            this.closest('.flashcard-container').remove();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert(`フラッシュカードの削除に失敗しました。: ${error.message}`);
                        });
                    }
                });
            }
        });
    </script>
@endsection
