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

        .flashcard-side {
            display: none;
        }

        .flashcard-side.front {
            display: block;
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

        @foreach ($flashcard->flashcardContents as $flashcardContent)
            <div class="flashcard-container" data-flashcard-id="{{ $flashcardContent->id }}">
                <!-- 表面を表示 -->
                <div class="flashcard-side front">
                    {{ $flashcardContent->content->content }}
                </div>
                <!-- 裏面（クリックで表示） -->
                <div class="flashcard-side back">
                    {{ $flashcardContent->side_type == 0 ? 'Front Side' : 'Back Side' }}: {{ $flashcardContent->content->content }}
                </div>
            </div>
        @endforeach

        <!-- ボタンで他のページに戻る -->
        <div class="text-center mt-4">
            <a href="{{ route('flashcards.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 全てのフラッシュカードにクリックイベントを追加
            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                card.addEventListener('click', function() {
                    // 表裏の表示を切り替える
                    const front = card.querySelector('.flashcard-side.front');
                    const back = card.querySelector('.flashcard-side.back');
                    if (front.style.display === 'block') {
                        front.style.display = 'none';
                        back.style.display = 'block';
                    } else {
                        front.style.display = 'block';
                        back.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
