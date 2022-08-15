<?php


namespace App\Utils;


class Constants
{
    const SELECTED_CURRENCIES = [
        'USD',
        'AUD',
        'EUR',
        'CAD',
        'HKD',
        'JPY'
    ];

    const HTTP_CODE_OK = 200;
    const HTTP_CODE_ERROR = 421;
    const HTTP_CODE_CREATE = 201;
    const HTTP_CODE_BAD_REQUEST = 400;
    const HTTP_CODE_UNAUTHORIZED = 401;
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_NOT_FORBIDDEN = 403;

}
