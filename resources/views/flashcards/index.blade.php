@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">フラッシュカード一覧</h1>

        <!-- 新しいフラッシュカードを追加するボタン -->
        <div class="text-right mb-3">
            <a href="{{ route('flashcards.create') }}" class="btn btn-success">新しいフラッシュカードを追加</a>
        </div>

        <!-- フラッシュカードのリスト -->
        <ul class="list-group">
            @foreach($flashcards as $flashcard)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $flashcard->english }} - {{ $flashcard->japanese }}</span>

                    <!-- 編集リンク -->
                    <a href="{{ route('flashcards.edit', $flashcard->id) }}" class="btn btn-sm btn-primary">編集</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
