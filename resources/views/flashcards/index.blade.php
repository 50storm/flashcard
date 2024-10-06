@extends('layouts.app')

@section('content')
    <div>
        <h1 class="mb-4 text-center">フラッシュカード一覧</h1>

        <!-- 新しいフラッシュカードを追加するボタン -->
        <div class="text-right mb-3">
            <a href="{{ route('flashcards.create') }}" class="btn btn-success">新しいフラッシュカードを追加</a>
        </div>

        <!-- フラッシュカードのリスト -->
        <ul class="list-group">
            <li v-for="flashcard in flashcards" :key="flashcard.id" class="list-group-item d-flex justify-content-between align-items-center">
            <span class="fixed-wrap">@{{ flashcard.english }} - @{{ flashcard.japanese }}</span>
                <!-- 音声読み上げボタン -->
                <div class="btn-group">
                    <button class="btn btn-sm btn-secondary" @click="speakText(flashcard.english, 'en-US')">英語を読み上げ</button>
                    <button class="btn btn-sm btn-secondary" @click="speakText(flashcard.japanese, 'ja-JP')">日本語を読み上げ</button>
                </div>

                <!-- 編集・削除ボタン -->
                <div class="btn-group">
                    <a :href="`{{ route('flashcards.edit', '') }}/${flashcard.id}`" class="btn btn-sm btn-primary">編集</a>
                    <form :action="`{{ route('flashcards.destroy', '') }}/${flashcard.id}`" method="POST" class="d-inline-block" onsubmit="return confirm('本当に削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">削除</button>
                    </form>
                </div>
            </li>
        </ul>
    </div>

    <!-- Vue.jsのスクリプト -->
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    flashcards: @json($flashcards)  // LaravelからのデータをVueに渡す
                }
            },
            methods: {
                speakText(text, lang = 'en-US') {
                    if ('speechSynthesis' in window) {
                        const speech = new SpeechSynthesisUtterance();
                        speech.text = text;
                        speech.lang = lang;

                        const voices = window.speechSynthesis.getVoices();
                        if (voices.length === 0) {
                            window.speechSynthesis.onvoiceschanged = () => {
                                window.speechSynthesis.speak(speech);
                            };
                        } else {
                            window.speechSynthesis.speak(speech);
                        }
                    } else {
                        alert('このブラウザは音声合成APIをサポートしていません。');
                    }
                }
            }
        });
        app.mount('#app');
    </script>
    <style>
    .button-group {
        display: flex;
        gap: 10px;
    }

    .btn-group {
        display: inline-flex;
        gap: 10px;
    }

    .flashcard-item {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .fixed-wrap {
        white-space: normal; /* 折り返しを許可 */
        word-wrap: break-word; /* 長い単語を折り返し */
        width: 60%; /* 要素幅を固定（必要なら指定） */
    }
    </style>
@endsection
