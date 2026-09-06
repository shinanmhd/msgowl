<?php

use Hadhiya\MsgOwl\Channels\MsgOwlChannel;
use Hadhiya\MsgOwl\Facades\MsgOwl;
use Illuminate\Support\Facades\Http;

it('can mock a sent sms', function () {
    Http::fake([
        'rest.msgowl.com/*' => Http::response(['status' => 'success'], 200),
    ]);

    $response = MsgOwl::send([
        'recipients' => '9601234567',
        'body' => 'Test Message',
    ]);

    expect($response->json('status'))->toBe('success');
});

it('resolves the Laravel notification channel', function () {
    expect(app()->bound(MsgOwlChannel::class))->toBeTrue();
    expect(app(MsgOwlChannel::class))->toBeInstanceOf(MsgOwlChannel::class);
});
