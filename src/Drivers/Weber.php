<?php

namespace App\Packages\SMSDriver;

use GuzzleHttp\Client;
use Clickertech\Sms\Contracts\Driver;
use Illuminate\Support\Facades\Log;

class Weber extends Driver
{
    # You will have to make 2 methods.
    /**
     * 1. __constructor($settings) # {Mandatory} This settings is your Config Params that you've set.
     * 2. send() # (Mandatory) This is the main message that will be sent.
     *
     * Example Given below:
     */

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var mixed
     */
    protected $client;

    /**
     * Your Driver Config.
     *
     * @var array $settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        # Initialize any Client that you want.
        $this->client = new Client(); # Guzzle Client for example.
    }

    /**
     * @return object Ex.: (object) ['status' => true, 'data' => 'Client Response Data'];
     */
    public function send()
    {
        // $this->recipients; # Array of Recipients.
        // Log::info('SMS Settings >> ' . print_r($this->settings, true));
        $response = $this->client->request('GET', $this->settings['url'], [
            "query" => [
                'apikey' => $this->settings['apikey'],
                'senderid' => $this->settings['senderid'],
                'templateid' => $this->templateid,
                'number' => implode(',', $this->recipients),
                'message' => $this->body,
            ],
        ]);
        $data = $this->getResponseData($response);
        // \Log::info('SMS Response Phrase ' . $response->getReasonPhrase()); // gives  	SMS ResponseOK
        // \Log::info('SMS Response Status Code ' . $response->getStatusCode()); // gives  	SMS ResponseOK
        Log::info('SMS Body ' . $this->body); // gives  SMS ResponseS.155549
        Log::info('SMS Response Body ' . $response->getBody()); // gives  SMS ResponseS.155549
        return (object)array_merge($data, ["status" => true]);
    }

    protected function getResponseData($response)
    {
        if ($response->getStatusCode() != 200) {
            Log::info('SMS Response' . $response->getBody()); // gives  SMS ResponseS.155549
            return ["status" => false, "message" => "Request Error. " . $response->getReasonPhrase()];
        }

        $data = json_decode((string)$response->getBody(), true);

        if ($data["status"] != "success") {
            return ["status" => false, "message" => "Something went wrong.", "data" => $data];
        }

        return $data;
    }
}
