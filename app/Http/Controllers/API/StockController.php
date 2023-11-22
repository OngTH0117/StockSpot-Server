<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StockDataImport; 

class StockController extends Controller
{
    public function stocks()
{
    try {
        $data = Excel::toArray(new StockDataImport, public_path('StockData2.xlsx'));
        
        if (count($data) > 0) {
            // extract the first and only array of processed data
            $header = $data[0][0]; //1st row is header
            $processedData = [];

            foreach ($data[0] as $row) {
                if ($row !== $header) {
                    $stock = [];
                    foreach ($header as $index => $column) {
                        $stock[$column] = $row[$index];
                    }
                    $processedData[] = $stock;
                }
            }
            
            return response()->json($processedData);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }
    } catch (\Exception $e) {
        
        \Log::error('Exception during data processing: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while processing data'], 500);
    }
}
public function addToWatchlist(Request $request)
{
    $user = $request->user();
    $stockIdentifier = $request->input('stockIdentifier');
    $userId = $request->input('userId');

    
    if ($user->id !== $userId) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // save stock into watchlist

    return response()->json(['message' => 'Stock added to watchlist']);
}

public function getStockByIdentifier(Request $request, $identifier)
{
    try {
     

        $data = Excel::toArray(new StockDataImport, public_path('StockData2.xlsx'));

        if (count($data) > 0) {
            // extract the first and only array of processed data
            $header = $data[0][0]; //1st row is header

            // search for the stock by identifier
            foreach ($data[0] as $row) {
                if ($row !== $header && isset($row[array_search('Identifier (RIC)', $header)]) &&
                    $row[array_search('Identifier (RIC)', $header)] === $identifier) {
                    // return its details
                    $stock = [];
                    foreach ($header as $index => $column) {
                        $stock[$column] = $row[$index];
                    }

                    return response()->json(['data' => $stock], 200);
                }
            }

            // stock not found
            return response()->json(['message' => 'Stock not found'], 404);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }
    } catch (\Exception $e) {
        \Log::error('Exception during data processing: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while processing data'], 500);
    }
}


}