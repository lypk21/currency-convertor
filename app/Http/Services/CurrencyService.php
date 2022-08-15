<?php


namespace App\Http\Services;


use App\Models\Currency;
use App\Utils\ApilayerCurl;

class CurrencyService
{
    private $apilayerCurl;
    public function __construct(ApilayerCurl $apilayerCurl)
    {
        $this->apilayerCurl = $apilayerCurl;
    }

    public function checkExistedRecord($source, $destination, $date) {
        try {
            return Currency::where('source', $source)->where('destination', $destination)->where('data', $date)->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function saveCurrenciesForTimeFrame($arrRes) {
       try {
           $quotes = data_get($arrRes, "quotes", []);
           $source = data_get($arrRes, "source");
           foreach ($quotes as $date => $items) {
               foreach ($items as $key => $item) {
                   //save if record not exist, otherwise ignore
                   $currency = Currency::firstOrNew([
                       'source' => $source,
                       'destination' => str_replace($source, "", $key),
                       'date' => $date
                   ]);
                 if(!$currency->exists) {
                     $currency->rate = $item;
                     $currency->save();
                 }
               }
           }
       } catch (\Exception $e) {
           throw $e;
       }
    }

    public function getCurrencyTimeFrame($source, $currencies, $start_date, $end_date) {
        try {
            $path = "currency_data/timeframe?start_date=".$start_date."&end_date=".$end_date."&source=".$source."&currencies=".$currencies;
            $jsonRes = $this->apilayerCurl->curl_get_request($path);
            $arrRes = json_decode($jsonRes, true);
            if(data_get($arrRes, "error")) {
                throw new \Exception(data_get($arrRes, "error.info"));
            }
            return $arrRes;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getLiveRate($source, $currencies) {
        try {
            $path = "currency_data/live?source=".$source."&currencies=".$currencies;
            $jsonRes = $this->apilayerCurl->curl_get_request($path);
            $arrRes = json_decode($jsonRes, true);
            if(data_get($arrRes, "error")) {
                throw new \Exception(data_get($arrRes, "error.info"));
            }
            $quotes = data_get($arrRes, "quotes", []);
            $data = [];
            foreach ($quotes as $key => $rate) {
                $data[] = [
                    'source' => $source,
                    'destination' => str_replace($source, "", $key),
                    'rate' => $rate,
                    'quote_time' => date('Y-m-d H:i:s',data_get($arrRes, "timestamp") )
                ];
            }
            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getOneYearStatis($source, $destination) {
        $start_date = date("Y-m-d", strtotime("-365 day"));
        //last monthly date of 365 day ago
        $end_date = date("Y-m-t", strtotime($start_date));
        $data = [];
        do {
            $avg_rate = Currency::where('source',$source)->where('destination', $destination)
                ->whereBetween('date', [$start_date, $end_date])
                ->avg('rate');

            $data[] = [
                'rate' => $avg_rate,
                'title' => date('m', strtotime($start_date))
            ];

            $start_date = date('Y-m-d', strtotime($end_date.' +1 day'));
            $end_date = date("Y-m-t", strtotime($start_date));
        } while($start_date <= date('Y-m-t'));
        return $data;
    }

    public function getSixMonthStatis($source, $destination) {
        $start_date = date("Y-m-d", strtotime("-6 months"));
        //last date of week
        $end_date = date("Y-m-d", strtotime("next saturday", strtotime($start_date)));
        $data = [];
        do {
            $avg_rate = Currency::where('source',$source)->where('destination', $destination)
                ->whereBetween('date', [$start_date, $end_date])
                ->avg('rate');

            $data[] = [
                'rate' => $avg_rate,
                'title' => $start_date
            ];

            $start_date = date('Y-m-d', strtotime($end_date.' +1 day'));
            $end_date = date("Y-m-d", strtotime("next saturday", strtotime($start_date)));
        } while($start_date <= date('Y-m-t'));
        return $data;
    }

    public function getOneMonthStatis($source, $destination) {
        $start_date = date("Y-m-d", strtotime("-1 months"));
        $data = [];
        do {
            $currency = Currency::where('source',$source)->where('destination', $destination)
                ->where('date', $start_date)
                ->first();

            $data[] = [
                'rate' => data_get($currency,"rate"),
                'title' => $start_date
            ];
            $start_date = date('Y-m-d', strtotime($start_date.' +1 day'));
        } while($start_date <= date('Y-m-t'));
        return $data;
    }
}
