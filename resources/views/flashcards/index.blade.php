@extends('layouts.app')

<!-- Custom CSS Section -->
@section('styles')
    <style>
        .flashcard {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- ハンバーガーメニューのトリガー部分 -->
        <div class="d-flex">
            <!-- mt-2はデスクトップのときのみ -->
            <div class="mt-lg-2">
                <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#menuContent" aria-expanded="false" aria-controls="menuContent">
                    <span>&#9776;</span>
                </button>
            </div>
            <div class="">
                <h1>フラッシュカード一覧</h1>
            </div>
        </div>

        <!-- ハンバーガーメニューで開く部分 -->
        <div id="menuContent" class="collapse">
            <!-- 新しいフラッシュカードを追加するフォーム -->
            <div class="mb-4">
                <form action="{{ route('flashcards.store') }}" method="POST" class="d-flex flex-column">
                    @csrf
                    <!-- 日本語のtextarea -->
                    <textarea id="japaneseTextarea" name="japanese" class="form-control mb-2 japanese-textarea" placeholder="日本語" required rows="7"></textarea>

                    <!-- 英語のtextarea -->
                    <textarea id="englishTextarea" name="english" spellcheck="true" class="form-control mb-2 english-textarea" placeholder="英語" required rows="7"></textarea>

                    <button id="submitButton" type="submit" class="btn"><span class="material-icons">edit</span></button>
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
        </div>

        <!-- フラッシュカードを表示する領域 -->
        <div id="flashcard-list" class="list-group">
            @if ($flashcards->isEmpty())
                <p>No flashcards available.</p>
            @else
                <div class="row">
                    @foreach ($flashcards as $flashcard)
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <!-- このフラッシュカードを練習するボタンを追加 -->
                                    <a href="{{ route('flashcards.practice', $flashcard->id) }}" class="btn btn-primary mb-2">このフラッシュカードを練習する</a>
                                    @empty($flashcard->name)
                                         <h5 class="card-title">No name</h5>
                                    @else
                                        <h5 class="card-title">{{ $flashcard->name }}</h5>
                                    @endempty
                                    <!-- <p class="card-text"><strong>User ID:</strong> {{ $flashcard->user_id }}</p> -->

                                    <h6 class="text-decoration-underline">cards</h6>
                                    <ul>
                                        @foreach ($flashcard->pairs as $pair)
                                            <li>
                                                <strong>Front:</strong> {{ $pair->frontContent->content ?? 'N/A' }}<br>
                                                <strong>Back:</strong> {{ $pair->backContent->content ?? 'N/A' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p><strong>Created At:</strong> {{ $flashcard->created_at }}</p>
                                    <p><strong>Updated At:</strong> {{ $flashcard->updated_at }}</p>

                                    <div class="d-flex flex-column" style="white-space: nowrap;">
                                        <a href="{{ route('flashcards.edit', $flashcard->id) }}" class="btn">
                                            <span class="material-icons">edit</span>                        
                                        </a>
                                        <form action="{{ route('flashcards.destroy', $flashcard->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" onclick="return confirm('このフラッシュカードを削除しますか？')">
                                                <span class="material-icons">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() { 
            let isVoiceEnabled = true; // 音声のON/OFF状態
            let selectedRate = 1.0; // 音声速度の初期値

            // イベントリスナーをテキストエリアに追加
            document.getElementById('japaneseTextarea').addEventListener('input', checkTextareaInput);
            document.getElementById('englishTextarea').addEventListener('input', checkTextareaInput);

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

        // テキストエリアの入力がある場合にボタンにクラスを追加する関数
        function checkTextareaInput() {
            var japaneseTextarea = document.getElementById('japaneseTextarea').value.trim();
            var englishTextarea = document.getElementById('englishTextarea').value.trim();
            var submitButton = document.getElementById('submitButton');

            // 日本語と英語のtextarea両方に入力がある場合
            if (japaneseTextarea && englishTextarea) {
                submitButton.classList.add('btn-primary');
                submitButton.disabled = false;  // ボタンを有効化
            } else {
                submitButton.classList.remove('btn-primary');
                submitButton.disabled = true;  // ボタンを無効化
            }
        }
    </script>
@endsection
