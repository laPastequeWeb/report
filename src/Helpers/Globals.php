<?php
namespace Storhn\Reporter\Helpers;

use Storhn\Reporter\Services\Report;

if (!function_exists('lpwreport')) {
    function lpwreport($data): void
    {
        (new Report())->send($data);
    }
}
/*
Use it like this :
report([
    'client' => 'string', // optional
    'project' => 'string',
    'level' => 'int',
    'message' => 'string',
    'file' => 'string', // optional
    'line' => 'int', // optional
]);
*/