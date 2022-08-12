<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\LiveRateRequest;
use App\Http\Requests\TimeRangeRequest;
use App\Http\Services\CurrencyService;
use App\Utils\Constants;

class CurrencyController extends ApiController
{
    private $currencyService;

    function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function getAvailableCurrencies() {
        return $this->successResponse(Constants::SELECTED_CURRENCIES);
    }

    public function getCurrenciesLiveRate(LiveRateRequest $request) {
        try {
            return $this->successResponse(
                $this->currencyService->getLiveRate($request->source, $request->currencies)
            );
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }


    public function getPeriodStatis(TimeRangeRequest $request) {
        try {
            switch (strtolower($request->period)) {
                case "oneyear":
                    return $this->successResponse(
                        $this->currencyService->getOneYearStatis($request->source, $request->destination)
                    );
                    break;
                case "halfyear":
                    return $this->successResponse(
                        $this->currencyService->getSixMonthStatis($request->source, $request->destination)
                    );
                    break;
                case "onemonth":
                    return $this->successResponse(
                        $this->currencyService->getOneMonthStatis($request->source, $request->destination)
                    );
                    break;
                default:
                    return $this->errorResponse("period illegal");
            }
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
