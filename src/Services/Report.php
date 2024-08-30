<?php
namespace Storhn\Reporter\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Storhn\Reporter\Helpers\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Report
{
    use Validator;
    
    protected $client;
    protected $url;
    protected $password;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 1.0,
        ]);

        $this->url = config('reporter.url');
        $this->password = config('reporter.password');

    }

    public function send($data)
    {
        $data['date'] = now()->toDateTimeString();

        if (!$this->validateData($data)) {
            return;  // Stop execution if validation fails
        }

        try {
            Log::error('Trying to send data to ' . $this->url . '/api/logs');
            
            $promise = $this->client->postAsync($this->url . '/api/logs', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->password,
                ],
                'json' => $data,
            ]);

            $promise->then(
                function ($response) {
                    Log::error('Report presumably sent. Status code: ' . $response->getStatusCode());
                    Log::error('Response body: ' . $response->getBody()->getContents());
                },
                function (RequestException $e) {
                    Log::error('Report failed: ' . $e->getMessage());
                    Log::error('Request details: ' . $e->getRequest()->getBody());
                }
            )->wait(); // Wait for the promise to complete

        } catch (\Exception $e) {
            Log::error('Report failed: ' . $e->getMessage());
        }
    }
}
