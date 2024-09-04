<?php
namespace Storhn\Reporter\Services;

use GuzzleHttp\Client;
use Storhn\Reporter\Helpers\Validator;

class Report
{
    use Validator;
    
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 1.0]);
        $this->config = config('reporter');
    }

    public function send(array $data)
    {
        $data = array_merge($data, [
            'date' => now()->toDateTimeString(),
            'client' => $this->config['client'],
            'project' => $this->config['project'],
            'client_url' => $this->config['client_url'],
        ]);
        if (!$this->validateData($data)) {
            return; 
        }
        $this->client->post($this->config['dashboard_url'] . '/api/logs', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['password'],
            ],
            'json' => $data,
        ]);
    }
}