<?php 

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersCsvExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * データの収集
     */
    public function collection()
    {
        return User::select('id', 'name', 'email', 'created_at')->get();
    }

    /**
     * CSVヘッダーの設定
     */
    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Profile' , 'Created At'];
    }

    /**
     * エクスポートするデータのフォーマット設定
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            // ユーザーのプロフィールデータを取得
            optional($user->profile)->bio ?? 'N/A',
            $user->created_at->format('Y-m-d'),
        ];
    }
}
