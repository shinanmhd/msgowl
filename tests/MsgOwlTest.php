<?php

use Illuminate\Support\Facades\Http;
use Hadhiya\MsgOwl\Facades\MsgOwl;

it('can mock a sent sms', function () {
    Http::fake([
        'api.msgowl.com/*' => Http::response(['status' => 'success'], 200),
    ]);

    $response = MsgOwl::send([
        'recipients' => '9601234567',
        'body' => 'Test Message',
    ]);

    expect($response->json('status'))->toBe('success');
});