@extends('layouts.app')

@section('content')
    <h1>フラッシュカードを作成</h1>

    <form action="{{ route('flashcards.store') }}" method="POST">
        @csrf
        <div>
            <label for="english">英単語または英文:</label>
            <textarea id="english" name="english" rows="3" required></textarea>
        </div>
        <div>
            <label for="japanese">日本語訳:</label>
            <textarea id="japanese" name="japanese" rows="3" required></textarea>
        </div>
        <button type="submit">追加</button>
    </form>
@endsection
