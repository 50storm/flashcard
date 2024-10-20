@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Practice Flashcard</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Flashcard ID: {{ $flashcard->id }}</h5>
                <p class="card-text"><strong>User ID:</strong> {{ $flashcard->user_id }}</p>

                <h6>Flashcard Contents:</h6>
                <ul>
                    @foreach ($flashcard->flashcardContents as $flashcardContent)
                        <li>
                            <!-- flashcard_contentに対応するcontentを表示 -->
                            {{ $flashcardContent->content->content }} 
                            (Side: {{ $flashcardContent->side_type == 0 ? 'Front' : 'Back' }})
                        </li>
                    @endforeach
                </ul>

                <p><strong>Created At:</strong> {{ $flashcard->created_at }}</p>
                <p><strong>Updated At:</strong> {{ $flashcard->updated_at }}</p>
            </div>
        </div>
    </div>
@endsection
