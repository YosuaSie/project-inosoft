<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelDataController extends Controller
{
    public function getDataFromExcel()
    {
        // Tentukan path file Excel yang akan dibaca
        $filePath = storage_path('app/public/sample.xlsx');

        // Buka file Excel menggunakan PhpSpreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        // Inisialisasi array untuk menyimpan data
        $products = [];

        //tes return
        // $products = [
        //     ['id' => 1, 'name' => 'Casing'],
        //     ['id' => 2, 'name' => 'Tubing'],
        // ];
        // $grades = [
        //     ['id' => 1, 'name' => 'Grade A', 'product_id' => 1],
        //     ['id' => 2, 'name' => 'Grade B', 'product_id' => 2],
        // ];
        // $sizes = [
        //     ['id' => 1, 'name' => '10', 'grade_id' => 1],
        //     ['id' => 2, 'name' => '5', 'grade_id' => 2],
        // ]; 
        // $connections = [
        //     ['id' => 1, 'name' => 'Threaded', 'size_id' => 1],
        //     ['id' => 2, 'name' => 'Welded', 'size_id' => 2],
        // ]; 

        // Looping melalui baris Excel untuk membaca data
        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue(); // Dapatkan nilai dari setiap sel
            }

            // Sesuaikan dengan kolom dan struktur data yang diinginkan
            if ($rowIndex > 1) { // Anggap baris pertama sebagai header
                $products[] = ['id' => $rowData[0], 'code' => $rowData[1], 'item_id' => $rowData[2], 'qty' => $rowData[3], 'qty_unit' => $rowData[4], 'country' => $rowData[5], 'item_code' => $rowData[6], 'desc' => $rowData[7], 'type' => $rowData[8], 'grade' => $rowData[9], 'connection' => $rowData[10], 'size' => $rowData[11]];
            }
        }

        // Kembalikan data sebagai JSON untuk digunakan di frontend
        return response()->json([
            'products' => $products,
        ]);
    }
}
