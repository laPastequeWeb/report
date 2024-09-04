<?php
namespace Storhn\Reporter\Helpers;

use Storhn\Reporter\Services\Report;

if (!function_exists('_report')) {
    function _report(int $level, mixed $message): void
    {
        $caller = debug_backtrace()[0];
        (new Report())->send([
            'level' => $level,
            'message' => $message,
            'file' => $caller['file'],
            'line' => $caller['line']
        ]);
    }
}