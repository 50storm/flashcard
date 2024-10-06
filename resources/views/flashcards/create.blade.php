@extends('layouts.app')

@section('content')
    <h1>フラッシュカードを作成</h1>

    <form action="{{ route('flashcards.store') }}" method="POST">
        @csrf
        <div>
            <label for="english">英単語または英文:</label>
            <input type="text" id="english" name="english" required>
        </div>
        <div>
            <label for="japanese">日本語訳:</label>
            <input type="text" id="japanese" name="japanese" required>
        </div>
        <button type="submit">追加</button>
    </form>
@endsection
