<?php

namespace App\Http\Controllers;

use App\Models\Inventories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

        // return view('welcome', compact('totalInventories', 'totalExpired', 'almostExpired'));
        return redirect('/admin');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
