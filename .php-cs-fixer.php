<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/config')
    ->in(__DIR__ . '/database')
    ->in(__DIR__ . '/resources')
    ->in(__DIR__ . '/routes')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true, // PSR-12 標準に従う
        'array_syntax' => ['syntax' => 'short'], // 配列を短縮形に統一
        'single_quote' => true, // 文字列はシングルクォート
        'no_unused_imports' => true, // 使っていない use 文を削除
        'binary_operator_spaces' => [ // 演算子の前後にスペースを追加
            'default' => 'single_space',
        ],
        'blank_line_after_namespace' => true, // namespace の後に空行を追加
        'blank_line_after_opening_tag' => true, // PHP タグの後に空行を追加
        'blank_line_before_statement' => ['statements' => ['return']], // return 前に空行を追加
        'braces' => ['position_after_functions_and_oop_constructs' => 'next'], // ブレースを次の行に
        'concat_space' => ['spacing' => 'one'], // 文字列結合時にスペースを1つ追加
        'function_declaration' => ['closure_function_spacing' => 'none'], // 関数の括弧の間にスペースを入れない
        'indentation_type' => true, // インデントをタブかスペースに統一（trueだとスペース）
        'line_ending' => true, // 改行コードを統一（trueだとUnix LF）
        'lowercase_keywords' => true, // ifやelseなどのキーワードを小文字に統一
        'no_trailing_whitespace' => true, // 行末の余分な空白を削除
        'no_trailing_whitespace_in_comment' => true, // コメント内の行末の余分な空白も削除
        'no_whitespace_in_blank_line' => true, // 空行に空白がないようにする
        'phpdoc_scalar' => true, // phpdocの型定義を scalar (int, bool, stringなど) に統一
        'phpdoc_summary' => false, // phpdoc の summary を1行にしない（コメントが長い場合用）
        'phpdoc_to_comment' => false, // phpdoc を普通のコメントに変換しない（API用コメントなどで重要）
        'trailing_comma_in_multiline' => ['elements' => ['arrays']], // 配列の末尾にカンマを追加（複数行の場合）
        'whitespace_after_comma_in_array' => true, // 配列のカンマ後にスペースを追加
    ])
    ->setFinder($finder);
