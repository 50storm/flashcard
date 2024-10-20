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

        .flashcard-back {
            display: none; /* 裏面は非表示 */
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
         <!-- TODO foreachをやめて、forにして。表と裏を１つのdive要素に埋める。
          
         -->
        <!-- 表と裏のコンテンツを一つのdivにまとめて表示 -->
        @for ($i = 0; $i < count($flashcard->contents); $i += 2)
            <div class="flashcard-container" data-back-content="{{ $flashcard->contents[$i+1]->content ?? '裏のカードがありません' }}">
                <!-- 表面の表示 -->
                <span class="flashcard-front">
                    {{ $flashcard->contents[$i]->content }} <!-- 表の内容 -->
                </span>
            </div>
        @endfor


        <!-- ボタンで他のページに戻る -->
        <div class="text-center mt-4">
            <a href="{{ route('flashcards.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 全てのフラッシュカードにクリックイベントを追加
            document.querySelectorAll('.flashcard-container').forEach(function(card) {
                let isFront = true; // 表か裏かの状態を管理
                const front = card.querySelector('.flashcard-front');
                const backContent = card.getAttribute('data-back-content'); // 裏面の内容をdata属性から取得

                // カードをクリックしたら表と裏を切り替える
                card.addEventListener('click', function() {
                    if (isFront) {
                        front.innerText = backContent;  // 裏の内容を表示
                    } else {
                        front.innerText = front.getAttribute('data-original-front') || front.innerText;  // 元の表の内容を表示
                    }
                    isFront = !isFront;  // 表裏の状態を切り替え
                });

                // 表面の内容を保存 (初期状態)
                front.setAttribute('data-original-front', front.innerText);
            });
        });
    </script>

@endsection
