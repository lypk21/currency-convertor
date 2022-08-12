<?php

namespace App\Http\Controllers;

use App\Http\Services\CurrencyService;
use App\Utils\Constants;
use Illuminate\Support\Facades\Log;

class CronCurrencyController extends Controller
{
    private $currencyService;

    function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function retrieveCurrenciesFromApilayer() {
        try {
            $selectedCurrencies = Constants::SELECTED_CURRENCIES;
            $start_date = date("Y-m-d", strtotime("-365 day"));
            $end_date = date("Y-m-d");
            foreach ($selectedCurrencies as $source) {
                   $destination = implode(",", array_diff($selectedCurrencies, array($source)));
                   $res = $this->currencyService->getCurrencyTimeFrame($source, $destination, $start_date, $end_date);
                   $this->currencyService->saveCurrenciesForTimeFrame($res);
            }
        } catch (\Exception $exception) {
            Log::channel('api')->info($exception->getMessage());
        }
    }


}
