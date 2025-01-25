<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Models\Document;
use TomatoPHP\FilamentDocs\Services\Contracts\DocsVar;

function toRoman($number) {
    $map = [
        'XII' => 12, 'XI' => 11, 'X' => 10,
        'IX' => 9, 'VIII' => 8, 'VII' => 7,
        'VI' => 6, 'V' => 5, 'IV' => 4,
        'III' => 3, 'II' => 2, 'I' => 1,
    ];
    return array_search($number, $map);
}

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        FilamentDocs::register([
            DocsVar::make('$MONTH_ROMAWI')
                ->label('Month (Romawi)')
                ->value(fn () => toRoman(Carbon::now()->subDays(10)->month)),
            DocsVar::make('$MONTH_NUMBER')
                ->label('Month (Number)')
                ->value(fn () => Carbon::now()->subDays(10)->month),
            DocsVar::make('$YEAR_NUMBER')
                ->label('Year (Number)')
                ->value(fn () => Carbon::now()->subDays(10)->year),
            DocsVar::make('$MONTH_NAME')
                ->label('Month (Indonesian)')
                ->value(fn () => Carbon::now()->subDays(10)->locale('id')->translatedFormat('F')),
            DocsVar::make('$D_NUMBER')
                ->label('Day (Number)')
                ->value(fn () => Carbon::now()->subDays(10)->day),
            DocsVar::make('$D_NAME')
                ->label('Day (Indonesian)')
                ->value(fn () => Carbon::now()->subDays(10)->locale('id')->translatedFormat('l')),
            DocsVar::make('$NUM')
                ->label('Number of Documents This Month (3 Digits)')
                ->value(function () {
                    $currentMonth = Carbon::now()->month;
                    $currentYear = Carbon::now()->year;
            
                    $count = Document::whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->count();
            
                    return str_pad($count, 3, '0', STR_PAD_LEFT);
                }),
        ]);
    }
}
