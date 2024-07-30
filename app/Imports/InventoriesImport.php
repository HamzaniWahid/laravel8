<?php

namespace App\Imports;

use App\Models\Inventories;
use App\Models\Kategories;
use App\Models\Merek;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventoriesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $merekId = self::getMerekId($row['merek']);
        $kategoriId = self::getKategoriId($row['kategori']);
        $hargaJual = self::convertHarga($row['harga_jual']);
        $hargaBeli = self::convertHarga($row['harga_beli']);

        return new Inventories([
            'nama' => $row['nama'],
            'merek_id' => $merekId,
            'jumlah' => $row['jumlah'],
            'kategori_id' => $kategoriId,
            'expired' => $row['tanggal_expired'],
            'hargaJual' => $hargaJual,
            'hargaBeli' => $hargaBeli,
        ]);
    }

    public static function getMerekId(string $merek)
    {
        $merekRecord = Merek::where('nama', $merek)->first();
        return $merekRecord ? $merekRecord->id : null;
    }

    public static function getKategoriId(string $kategori)
    {
        $kategoriRecord = Kategories::where('nama', $kategori)->first();
        return $kategoriRecord ? $kategoriRecord->id : null;
    }

    public static function convertHarga(string $harga)
    {
        $str = str_replace(['.00'], '', $harga);    
        $hargaTanpaFormat = preg_replace('/[^0-9]/', '', $str);

        return intval($hargaTanpaFormat);
    }

}
