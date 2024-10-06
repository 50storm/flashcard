@extends('layouts.app')

@section('content')
    <h1 class="mb-4">フラッシュカードを作成</h1>

    <form action="{{ route('flashcards.store') }}" method="POST" class="w-50 mx-auto">
        @csrf
        <div class="form-group">
            <label for="english">英単語または英文:</label>
            <textarea id="english" name="english" rows="3" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="japanese">日本語訳:</label>
            <textarea id="japanese" name="japanese" rows="3" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">追加</button>
    </form>
@endsection
