<?php

namespace App\Filament\Widgets;

use App\Models\Inventories;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Hitung total inventori
        $totalInventories = Inventories::count();
        // Hitung total barang yang sudah expired
        $totalExpired = Inventories::where('expired', '<', Carbon::now())->count();

        // Hitung total barang yang akan mendekati expired (1 bulan sebelum expired)
        $oneMonthFromNow = Carbon::now()->addMonth();
        $almostExpired = Inventories::whereBetween('expired', [Carbon::now(), $oneMonthFromNow])->count();

        $totalInventoriesData = DB::table('inventories')
            ->select(DB::raw('count(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy(DB::raw('Month(created_at)'))
            ->pluck('total')
            ->toArray();

        // Deskripsi dinamis untuk barang yang sudah expired
        $expiredDescription = $totalExpired > 0 ? "$totalExpired barang sudah expired" : "Tidak ada barang yang expired";
        $expiredIcon = $totalExpired > 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-check-circle';
        $expiredColor = $totalExpired > 0 ? 'danger' : 'success';

        // Deskripsi dinamis untuk barang yang hampir expired
        $almostExpiredDescription = $almostExpired > 0 ? "$almostExpired barang akan expired dalam 30 hari" : "Tidak ada barang yang mendekati expired";
        $almostExpiredIcon = $almostExpired > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-check-circle';
        $almostExpiredColor = $almostExpired > 0 ? 'warning' : 'success';
        // Deskripsi dinamis untuk total inventori
        $totalInventoriesDescription = $totalInventories > 0 ? "Ada $totalInventories barang dalam inventori" : "Tidak ada barang dalam inventori";
        $totalInventoriesIcon = $totalInventories > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-check-circle';
        $totalInventoriesColor = $totalInventories > 0 ? 'success' : 'warning';

        return [
            Stat::make('Total Inventori', $totalInventories)
                ->description($totalInventoriesDescription)
                ->descriptionIcon($totalInventoriesIcon)
                ->color($totalInventoriesColor)
                ->chart($totalInventoriesData),

            Stat::make('Total Barang Expired', $totalExpired)
                ->description($expiredDescription)
                ->descriptionIcon($expiredIcon)
                ->color($expiredColor),

            Stat::make('Barang Hampir Expired', $almostExpired)
                ->description($almostExpiredDescription)
                ->descriptionIcon($almostExpiredIcon)
                ->color($almostExpiredColor),
        ];
    }
}
