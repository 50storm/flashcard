@extends('layouts.app')

@section('content')
    <h1 class="mb-4">フラッシュカードを編集</h1>

    <form action="{{ route('flashcards.update', $flashcard->id) }}" method="POST" class="w-50 mx-auto">
        @csrf
        @method('PUT') <!-- HTTP PUT メソッドを指定 -->

        <div class="form-group">
            <label for="english">英単語または英文:</label>
            <textarea id="english" name="english" rows="3" class="form-control" required>{{ old('english', $flashcard->english) }}</textarea>
        </div>
        <div class="form-group">
            <label for="japanese">日本語訳:</label>
            <textarea id="japanese" name="japanese" rows="3" class="form-control" required>{{ old('japanese', $flashcard->japanese) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">更新</button>
    </form>
@endsection
