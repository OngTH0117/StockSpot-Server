<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StockDataImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $data = [];

        foreach ($collection as $row) {
            try {
                $data[] = [
                'Identifier' => $row['Identifier (RIC)'],
                'CompanyName' => $row['Company Name'],
                'Country' => $row['Country of Incorporation'],
                'P/E' => $row['P/E'],
                'P/S' => $row['P/S ratio'],
                'ROE' => $row['ROE'],
                'Assets' => $row['Total Assets'],
                'Income' => $row['Operating Income'],
                'DTE' => $row['Debt to Equity'],
                'CR' => $row['Current Ratio'],
                'FCF' => $row['Free Cash Flow'],
                'Dividend' => $row['Dividend Yield'],
                'ProfitMargin' => $row['Gross Profit Margin'],
                'Price' => $row['Price Close'],
                'Price2' => $row['Price Close(3Y)'],
                'ROA' => $row['ROA'],
                'Sector' => $row['GICS Sector Name'],
                
            ];
        }catch (\Exception $e) {
            
            \Log::error('Exception during data processing: ' . $e->getMessage());
        }
    
        return $data;
    }
}
}