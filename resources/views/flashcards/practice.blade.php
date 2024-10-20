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
                <div class="flashcard-container">
                     <!-- 表面の表示 -->
                    <span class="flashcard-front">
                        {{ $content->content }} <!-- 表の内容 -->
                    </span>
                </div>
             @else
                <div class="flashcard-container d-none">
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
            // 全てのフラッシュカードにクリックイベントを追加
            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                let isFront = true; // 表示状態を管理
                const front = card.querySelector('.flashcard-front');
                const back = card.querySelector('.flashcard-back');

                // カードをクリックしたら表と裏を切り替える
                card.addEventListener('click', function() {
                    if (isFront) {
                        front.classList.add('d-none'); // 表を非表示（Bootstrapのd-noneを追加）
                        back.classList.remove('d-none'); // 裏を表示（d-noneを削除）
                    } else {
                        front.classList.remove('d-none'); // 表を表示（d-noneを削除）
                        back.classList.add('d-none'); // 裏を非表示（d-noneを追加）
                    }
                    isFront = !isFront; // 状態を反転
                });
            });
        });
    </script>
@endsection
