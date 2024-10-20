@extends('layouts.app')

@section('styles')
    <style>
        .flashcard-container {
            border: 1px solid #000;
            padding: 20px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 1.2em;
            line-height: 1.5;
            cursor: pointer;
            background-color: #fff;
            transition: background-color 0.3s ease;
        }

        .flashcard-container:hover {
            background-color: #f0f0f0;
        }

        .header-icon {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
        }

        .menu-icon, .user-icon {
            font-size: 30px;
        }

        .menu-icon {
            cursor: pointer;
        }

        .user-icon {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="header-icon">
            <!-- ハンバーガーメニューアイコン -->
            <div class="menu-icon">
                &#9776; ビジネス英会話
            </div>

            <!-- ユーザーアイコン -->
            <div class="user-icon">
                <span class="material-icons">person</span>
            </div>
        </div>

        <!-- フラッシュカードの内容を表示 -->
        @foreach ($flashcard->contents as $content)
            @if($content->pivot->side_type == 0)
                <div class="flashcard-container" data-card-id="{{ $content->pivot->flashcard_id }}">
                    <!-- 表面の表示 -->
                    <span class="flashcard-front">
                        {{ $content->content }} <!-- 表の内容 -->
                    </span>
                </div>
            @else
                <div class="flashcard-container d-none" data-card-id="{{ $content->pivot->flashcard_id }}">
                    <!-- 裏面はデフォルトでは非表示 -->
                    <span class="flashcard-back">
                        {{ $content->content }} <!-- 裏の内容 -->
                    </span>
                </div>
            @endif
        @endforeach

        <!-- ボタンで他のページに戻る -->
        <div class="text-center mt-4">
            <a href="{{ route('flashcards.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // フラッシュカード全体にクリックイベントを追加
            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                card.addEventListener('click', function() {
                    const cardId = card.getAttribute('data-card-id');
                    const front = document.querySelector(`.flashcard-container[data-card-id="${cardId}"]:not(.d-none)`);
                    const back = document.querySelector(`.flashcard-container[data-card-id="${cardId}"].d-none`);

                    if (front && back) {
                        front.classList.add('d-none'); // 表を非表示
                        back.classList.remove('d-none'); // 裏を表示
                    } else if (back && front) {
                        back.classList.add('d-none'); // 裏を非表示
                        front.classList.remove('d-none'); // 表を表示
                    }
                });
            });
        });
    </script>
@endsection
