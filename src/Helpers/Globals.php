<?php
namespace Storhn\Reporter\Helpers;

use Storhn\Reporter\Services\Report;

if (!function_exists('_report')) {
    function _report($data): void
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