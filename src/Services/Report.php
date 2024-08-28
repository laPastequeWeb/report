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
        if (!$this->validateData($data)) {
            return;  // Stop execution if validation fails
        }

        $data['timestamp'] = now()->toDateTimeString();

        try {

            $this->client->postAsync($this->url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->password,
                ],
                'json' => $data,
            ])->then(
                function ($response) {
                    // Success
                },
                function (RequestException $e) {
                    Log::error('Report failed: ' . $e->getMessage());
                }
            );
        } catch (\Exception $e) {
            Log::error('Report failed: ' . $e->getMessage());
        }
    }
}
