@extends('layouts.app')

@section('content')
    <h1>Flashcards</h1>

    <a href="{{ route('flashcards.create') }}">新しいフラッシュカードを追加</a>

    <ul>
        @foreach($flashcards as $flashcard)
            <li>{{ $flashcard->english }} - {{ $flashcard->japanese }}</li>
        @endforeach
    </ul>
@endsection
