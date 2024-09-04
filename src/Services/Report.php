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
    protected $client_name;
    protected $project;
    protected $password;
    protected $client_url;
    protected $dashboard_url;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 1.0,
        ]);

        $this->client_name = config('reporter.client');
        $this->project = config('reporter.project');
        $this->client_url = config('reporter.client_url');
        $this->dashboard_url = config('reporter.dashboard_url');
        $this->password = config('reporter.password');

    }

    public function send($data)
    {
        $data['date'] = now()->toDateTimeString();
        $data['client'] = $this->client_name;
        $data['project'] = $this->project;
        $data['client_url'] = $this->client_url;

        if (!$this->validateData($data)) {
            return;  // Stop execution if validation fails
        }

        try {
            Log::error('Trying to send data to ' . $this->dashboard_url . '/api/logs');
            
            $promise = $this->client->postAsync($this->dashboard_url . '/api/logs', [
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
