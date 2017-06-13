<?php
/**
 * @date    2017-06-13
 * @file    Client.php
 * @author  Patrick Mac Gregor <macgregor.porta@gmail.com>
 */

namespace Macghriogair\Coin;

use Coinbase\Wallet\Client as CbClient;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Enum\Param;

class Client
{
    protected $client;

    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function transactions($account)
    {
        return $this->client()->getAccountTransactions($account, [
            Param::FETCH_ALL => true,
        ]);
    }

    public function accounts()
    {
        return $this->client()->getAccounts();
    }

    public function time()
    {
        return $this->client()->getTime();
    }

    public function prices($coin = Coin::BTC, $fiat = Fiat::EUR)
    {
        $target = "{$coin}-{$fiat}";
        return [
            'buy' => $this->client()->getBuyPrice($target),
            'sell' => $this->client()->getSellPrice($target),
            'spot' => $this->client()->getSpotPrice($target),
        ];
    }

    protected function client()
    {
        if (! $this->client) {
            try {
                $this->client = CbClient::create($this->config());
            } catch (\Exception $e) {
                die("Could not initialize Client: {$e->getMessage()}");
            }
        }
        return $this->client;
    }

    protected function config()
    {
        return Configuration::apiKey($this->apiKey, $this->apiSecret);
    }
}
