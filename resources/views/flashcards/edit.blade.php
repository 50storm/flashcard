@extends('layouts.app')

@section('content')
    <form action="{{ route('flashcards.update', $flashcard->id) }}" method="POST" class="d-flex flex-column">
        @csrf
        @method('PUT') <!-- HTTP PUT メソッドを指定 -->

        <div class="form-group">
            <label for="english">英単語または英文:</label>
            <textarea id="englishTextarea" name="english" rows="3" class="form-control" required>{{ old('english', $flashcard->english) }}</textarea>
        </div>
        <div class="form-group">
            <label for="japanese">日本語訳:</label>
            <textarea id="japaneseTextarea" name="japanese" rows="3" class="form-control" required>{{ old('japanese', $flashcard->japanese) }}</textarea>
        </div>
        <button id="submitButton" type="submit" class="btn btn-block"><span class="material-icons">edit</span></button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() { 
             // イベントリスナーをテキストエリアに追加
            document.getElementById('japaneseTextarea').addEventListener('input', checkTextareaInput);
            document.getElementById('englishTextarea').addEventListener('input', checkTextareaInput);
            document.getElementById('englishTextarea').addEventListener('blur', checkTextareaInput);
            
        });

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

        // ウィンドウサイズに応じてtextareaの行数を変更する関数
        function resizeTextarea() {
            var japaneseTextarea = document.getElementById('japaneseTextarea');
            var englishTextarea = document.getElementById('englishTextarea');

            // デスクトップサイズ（幅992px以上）の場合の行数
            if (window.innerWidth >= 992) {
                japaneseTextarea.rows = 13; // デスクトップでは5行
                englishTextarea.rows = 13;
            } else {
                // モバイルサイズ（幅992px未満）の場合の行数
                japaneseTextarea.rows = 10; // モバイルでは3行
                englishTextarea.rows = 10;
            }
        }
        // ページが読み込まれたとき、またはウィンドウがリサイズされたときに行数を調整
        window.addEventListener('resize', resizeTextarea);
        window.addEventListener('load', resizeTextarea);
    </script>
@endsection
