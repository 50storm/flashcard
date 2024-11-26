<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2>お知らせ一覧</h2>
                    @if($notices->isEmpty())
                        <p>現在、お知らせはありません。</p>
                    @else
                        <ul>
                            @foreach($notices as $notice)
                                <li>
                                    <h2>{{ $notice->title }}</h2>
                                    <p>{{ $notice->content }}</p>
                                    <p>開始日: {{ $notice->start_date->format('Y-m-d') }}</p>
                                    @if($notice->end_date)
                                        <p>終了日: {{ $notice->end_date->format('Y-m-d') }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
