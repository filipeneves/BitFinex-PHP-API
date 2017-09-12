<?php

/**
 * Class BitFinex
 */
class BitFinex
{

    /**
     * @var string BitFinex API Secret
     */
    protected $apiSecret;

    /**
     * @var string BitFinex API Key
     */
    protected $apiKey;

    /**
     * BitFinex default url
     */
    private const BITFINEX_API_URL = 'https://api.bitfinex.com';

    /**
     * Version for the BitFinex API. In case of doubt, leave it at v1.
     */
    private const BITFINEX_API_VERSION = 'v1';

    /**
     * Constants to use on the cURL call
     */
    private const PUBLIC_REQUEST = 'public';
    private const PRIVATE_REQUEST = 'private';

    /**
     * BitFinex constructor.
     * @param $apiKey string
     * @param $apiSecret string
     */
    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Get Book
     *
     * @param string $symbol
     * @param array $data
     * @return mixed
     */
    public function getBook($symbol = 'BTCUSD', $data = [])
    {
        $url = $this->generateURL('book', $symbol);
        return $this->call(self::PUBLIC_REQUEST, $url, $data);
    }

    /**
     * Get Lendbook - gets the margin funding book.
     *
     * @param string $currency
     * @param array $data
     * @return object
     */
    public function getLendbook($currency = 'USD', $data = [])
    {
        $url = $this->generateURL('lendbook', $currency);
        return $this->call(self::PUBLIC_REQUEST, $url, $data);
    }

    /**
     * Get lends - List of the most recent funding data for a given currency.
     *
     * @param string $currency
     * @param array $data
     * @return object
     */
    public function getLends($currency = 'USD', $data = [])
    {
        $url = $this->generateURL('lends', $currency);
        return $this->call(self::PUBLIC_REQUEST, $url, $data);
    }

    /**
     * Get stats - misc stats about the givem symbol
     *
     * @param string $symbol
     * @return object
     */
    public function getStats($symbol = 'BTCUSD')
    {
        $url = $this->generateURL('stats', $symbol);
        return $this->call(self::PUBLIC_REQUEST, $url, []);
    }

    /**
     * Get all BitFinex trades
     *
     * @param string $symbol
     * @param array $data
     * @return mixed
     */
    public function getTrades($symbol = 'BTCUSD', $data = [])
    {
        $url = $this->generateURL('trades', $symbol);
        return $this->call(self::PUBLIC_REQUEST, $url, $data);
    }

    /**
     * Get list of symbols
     *
     * @return object
     */
    public function getSymbols()
    {
        $url = $this->generateURL('symbols');
        return $this->call(self::PUBLIC_REQUEST, $url, []);
    }

    /**
     * Get list of symbols with IDs, public
     *
     * @return mixed
     */
    public function getSymbolsDetails()
    {
        $url = $this->generateURL('symbols_details');
        return $this->call(self::PUBLIC_REQUEST, $url, []);
    }

    /**
     * Gets information on most recent trades from the last 24 hours
     *
     * @param string $symbol
     * @return object
     */
    public function getTicker($symbol = 'BTCUSD')
    {
        $url = $this->generateURL('pubticket', $symbol);
        return $this->call(self::PUBLIC_REQUEST, $url, []);
    }

    /**
     * Gets account information
     *
     * @return mixed
     */
    public function getMyAccountInfo()
    {

        $url = $this->generateURL('account_infos');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url)
        ];

        return $this->call(self::PRIVATE_REQUEST, $url, $data);

    }

    /**
     * Get all trades
     *
     * @param string $symbol Name of the symbols
     * @param int $limit_trades Optional. Limits the number of trades returned. Default 50.
     * @param null $timestamp Optional. Trades made before this timestamp won't be returned.
     * @param null $until Optional. Trades made after this timestamp won't be returned.
     * @param int $reverse Optional. Revert listing of trades (oldest -> newest). Default is newest -> oldest
     * @return mixed
     */
    public function getMyTrades($symbol = 'BTCUSD', $limit_trades, $timestamp = null, $until = null, $reverse = 0)
    {

        $url = $this->generateURL('mytrades');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url),
            'symbol' => $symbol,
            'limit_trades' => $limit_trades,
            'reverse' => $reverse
        ];

        if ($timestamp) $data['timestamp'] = $timestamp;
        if ($until) $data['until'] = $until;

        return $this->call(self::PRIVATE_REQUEST, $url, $data);

    }

    /**
     * Get trade history
     *
     * @param $currency
     * @param $wallet
     * @param int $limit
     * @param null $since
     * @param null $until
     * @return object
     */
    public function getMyHistory($currency, $wallet, $limit = 500, $since = null, $until = null)
    {

        $url = $this->generateURL('history');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url),
            'currency' => $currency,
            'wallet' => $wallet,
            'limit' => $limit
        ];

        if ($since) $data['since'] = $since;
        if ($until) $data['until'] = $until;

        return $this->call(self::PRIVATE_REQUEST, $url, $data);

    }

    /**
     * Get trade history movements
     *
     * @param string $currency
     * @param string $method
     * @param int $limit
     * @param null $since
     * @param null $until
     * @return object
     */
    public function getMyHistoryMovements($currency = 'USD', $method = 'bitcoin', $limit = 50, $since = null, $until = null)
    {

        $url = $this->generateURL('history', 'movements');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url),
            'currency' => $currency,
            'method' => $method,
            'limit' => $limit
        ];

        if ($since) $data['since'] = $since;
        if ($until) $data['until'] = $until;

        return $this->call(self::PRIVATE_REQUEST, $url, $data);

    }

    /**
     * Get summary
     *
     * @return mixed
     */
    public function getMySummary()
    {

        $url = $this->generateURL('summary');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url)
        ];

        return $this->call(self::PRIVATE_REQUEST, $url, $data);

    }

    /**
     * Get balances
     *
     * @return mixed
     */
    public function getMyBalances()
    {

        $url = $this->generateURL('balances');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url)
        ];

        return $this->call(self::PRIVATE_REQUEST, $url, $data);

    }

    /**
     * Returns deposit address to make a deposit
     *
     * @param $method
     * @param $walletName
     * @param int $renew
     * @return object
     */
    public function makeDeposit($method, $walletName, $renew = 0)
    {
        $url = $this->generateURL('deposit', 'new');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url),
            'method' => $method,
            'wallet_name' => $walletName,
            'renew' => $renew
        ];

        return $this->call(self::PRIVATE_REQUEST, $url, $data);
    }

    /**
     * Submit new order
     *
     * @param $symbol string Symbol, see list /symbols
     * @param $amount float How much you want to buy and sell
     * @param $price float Price to buy and/or sell.
     * @param $exchange string e.g. "bitfinex"
     * @param $side string "buy" or "sell"
     * @param $type string market, limit, stop, trailing-stop, fill-or-kill, exchange market, exchange limit, exchange shop, exchange trailing-stop, exchange fill-or-kill
     * @param bool $isHidden true if the order should be hidden
     * @param bool $isPostOnly true if the order should be post only.
     * @param bool $ocoOrder Additional STOP OCO order.
     * @param null $buyPriceOco If OCO is true, this represents the value of the OCO to stop
     * @return object
     */
    public function makeOrder($symbol, $amount, $price, $exchange, $side, $type, $isHidden = false, $isPostOnly = false, $ocoOrder = false, $buyPriceOco = null)
    {
        $url = $this->generateURL('order', 'new');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url),
            'symbol' => $symbol,
            'amount' => $amount,
            'price' => $price,
            'exchange' => $exchange,
            'side' => $side,
            'type' => $type,
            'is_hidden' => $isHidden,
            'is_postonly' => $isPostOnly,
            'ocoorder' => $ocoOrder
        ];

        if ($ocoOrder) $data['buy_price_oco'] = $buyPriceOco;

        return $this->call(self::PRIVATE_REQUEST, $data, $url);
    }

    /**
     * Submit multiple orders
     *
     * @param $symbol string Symbol, see list /symbols
     * @param $amount float How much you want to buy and sell
     * @param $price float Price to buy and/or sell.
     * @param $exchange string e.g. "bitfinex"
     * @param $side string "buy" or "sell"
     * @param $type string market, limit, stop, trailing-stop, fill-or-kill
     * @return object
     */
    public function makeMultipleOrder($symbol, $amount, $price, $exchange, $side, $type)
    {
        $url = $this->generateURL('order', 'new/multi');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url),
            'symbol' => $symbol,
            'amount' => $amount,
            'price' => $price,
            'exchange' => $exchange,
            'side' => $side,
            'type' => $type
        ];

        return $this->call(self::PRIVATE_REQUEST, $data, $url);
    }

    /**
     * Cancel an order
     *
     * @param $orderId int
     * @return object
     */
    public function cancelOrder($orderId)
    {
        $url = $this->generateURL('order', 'cancel');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url),
            'order_id' => $orderId
        ];

        return $this->call(self::PRIVATE_REQUEST, $data, $url);
    }

    /**
     * Cancels multiple orders
     *
     * @param $orderIds
     * @return object
     */
    public function cancelMultipleOrders($orderIds)
    {
        $url = $this->generateURL('order', 'cancel/multi');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url),
            'order_id' => $orderIds
        ];

        return $this->call(self::PRIVATE_REQUEST, $data, $url);
    }

    /**
     * Cancels all orders
     *
     * @param $orderIds
     * @return object
     */
    public function cancelAllOrders($orderIds)
    {
        $url = $this->generateURL('order', 'cancel/all');

        $data = [
            'request' => str_replace(self::BITFINEX_API_URL, '', $url)
        ];

        return $this->call(self::PRIVATE_REQUEST, $data, $url);
    }

    /**
     * Generates the URL for the API Call
     *
     * @param $method string
     * @param null /array $params
     * @return string
     */
    private function generateURL($method, $params = null)
    {

        $parameters = '';

        if ($params !== null) {
            $parameters = '/';

            if (is_array($params)) {
                $parameters .= implode('/', $params);
            } else {
                $parameters .= $params;
            }
        }

        return self::BITFINEX_API_URL . '/' . self::BITFINEX_API_VERSION . "/$method$parameters";

    }

    /**
     * Prepare headers for authentication
     *
     * @param $data array
     * @return array
     */
    private function generateHeaders($data)
    {

        $data['nonce'] = number_format(round(microtime(true) * 100000), 0, '.', '');
        $payload = base64_encode(json_encode($data));
        $signature = hash_hmac('sha384', $payload, $this->apiSecret);

        return [
            "X-BFX-APIKEY: $this->apiKey",
            "X-BFX-PAYLOAD: $payload",
            "X-BFX-SIGNATURE: $signature"
        ];

    }

    /**
     * Outputs the result of the cURL call
     *
     * @param $result
     * @param bool $isError
     * @return mixed
     */
    public function output($result, $isError = false)
    {

        $out = json_decode($result, true);

        if ($isError) {
            $out['error'] = true;
        }

        return $out;

    }

    /**
     * Outputs the result of a CURL error
     *
     * @param $curl
     * @return bool
     */
    public function error($curl)
    {

        if ($errno = curl_errno($curl)) {
            echo "cURL error {$errno}: " . curl_strerror($errno);
            return false;
        }

        return true;

    }

    /**
     * Returns if there was an error with the call
     *
     * @param $curl
     * @return bool
     */
    public function isBitFinexError($curl)
    {

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        return ($httpCode !== 200 ? true : false);

    }

    /**
     * Executes the API call
     *
     * @param $url string
     * @param array $data
     * @return object/bool
     */
    private function call($type, $url, $data = false)
    {

        $curl = curl_init();

        if ($type == self::PRIVATE_REQUEST) {

            $headers = $this->generateHeaders($data);

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => ''
            ]);

            if (!$result = curl_exec($curl)) {
                return $this->error($curl);
            }

            return $this->output($result, $this->isBitFinexError($curl));

        } elseif ($type == self::PUBLIC_REQUEST) {

            $query = '';

            if (count($data)) {
                $query = '?' . http_build_query($data);
            }

            $url = $url . $query;

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true
            ]);

            if (!$result = curl_exec($curl)) {
                return $this->error($curl);
            }

            return $this->output($result, $this->isBitFinexError($curl));

        }

    }

}