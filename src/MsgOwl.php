<?php

namespace Hadhiya\MsgOwl;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MsgOwl
{
    protected string $restBaseUrl = 'https://rest.msgowl.com';

    protected string $otpBaseUrl = 'https://otp.msgowl.com';

    public function __construct(
        protected string $apiKey,
        protected string $senderId,
        protected ?string $otpKey = null,
        protected ?string $otpSecret = null
    ) {}

    /**
     * Build the authenticated HTTP client.
     */
    protected function client(string $mode = 'standard')
    {
        if ($mode === 'otp') {
            return Http::withHeaders([
                'Authorization' => "AccessKey {$this->otpKey}",
                'Accept' => 'application/json',
            ]);
        }

        return Http::withHeaders([
            'Authorization' => "AccessKey {$this->apiKey}",
            'Accept' => 'application/json',
        ]);
    }

    /**
     * Send a standard SMS message.
     * Endpoint: https://rest.msgowl.com/messages
     */
    public function send(array|MsgOwlMessage $params): ?Response
    {
        if ($params instanceof MsgOwlMessage) {
            if ($params->isDryRun) {
                Log::info('MsgOwl Dry Run:', $params->toArray());

                return null;
            }
            $params = $params->toArray();
        }

        $params['sender_id'] ??= $this->senderId;

        return $this->client('standard')->post("{$this->restBaseUrl}/messages", $params);
    }

    /**
     * Send an OTP.
     * Endpoint: https://otp.msgowl.com/send
     */
    public function sendOtp(string $phoneNumber, array $options = []): Response
    {
        $payload = array_merge([
            'phone_number' => $phoneNumber,
        ], $options);

        return $this->client('otp')->post("{$this->otpBaseUrl}/send", $payload);
    }

    /**
     * Resend an OTP.
     * Endpoint: https://otp.msgowl.com/resend
     */
    public function resendOtp(string $phoneNumber, int $otpId): Response
    {
        return $this->client('otp')->post("{$this->otpBaseUrl}/resend", [
            'phone_number' => $phoneNumber,
            'id' => $otpId,
        ]);
    }

    /**
     * Verify an OTP.
     * Endpoint: https://otp.msgowl.com/verify
     */
    public function verifyOtp(string $phoneNumber, string $code): Response
    {
        return $this->client('otp')->post("{$this->otpBaseUrl}/verify", [
            'phone_number' => $phoneNumber,
            'code' => $code,
        ]);
    }

    /**
     * Check current wallet balance.
     * Endpoint: https://rest.msgowl.com/balance
     */
    public function getBalance(): Response
    {
        return $this->client('standard')->get("{$this->restBaseUrl}/balance");
    }
}
