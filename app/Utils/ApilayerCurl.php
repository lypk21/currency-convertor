<?php


namespace App\Utils;


use Illuminate\Support\Facades\Log;

class ApilayerCurl
{
    const BASE_URL = "https://api.apilayer.com/";

    public  function curl_get_request($path) {
       /* curl --request GET 'https://api.apilayer.com/currency_data/convert?base=USD&symbols=EUR,GBP,JPY&amount=5&date=2018-01-01' \
        --header 'apikey: YOUR API KEY'*/

        $url = self::BASE_URL.$path;
        $CURLOPT_HTTPHEADER = [
            "Content-Type: text/plain",
            "apikey: ".config('app.currency_api_key')
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url );
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT,        0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER);
        $content  = curl_exec($curl);
        if(!$content) Log::channel('api')->info("api request for ".$url." fail on ".date("Y-m-d H:i:s"));
        curl_close($curl);
        return $content;
    }
}
