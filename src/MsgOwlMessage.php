<?php

namespace Hadhiya\MsgOwl;

class MsgOwlMessage
{
    /**
     * Whether the message should be logged instead of sent.
     */
    public bool $isDryRun = false;
    
    public function __construct(
        public string $body = '',
        public ?string $senderId = null,
        public ?string $recipients = null,
    ) {}

    /**
     * Set the message content.
     */
    public static function create(string $body = ''): self
    {
        return new self($body);
    }

    /**
     * Mark this message as a dry run to prevent actual API calls.
     */
    public function dryRun(bool $isDryRun = true): self
    {
        $this->isDryRun = $isDryRun;

        return $this;
    }

    /**
     * Set the message body.
     */
    public function body(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set the sender ID (overrides the default in config).
     */
    public function sender(string $senderId): self
    {
        $this->senderId = $senderId;

        return $this;
    }

    /**
     * Set the recipient (overrides the notifiable route).
     */
    public function to(string $recipients): self
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Convert the message to an array for the API.
     */
    public function toArray(): array
    {
        return array_filter([
            'body' => $this->body,
            'sender_id' => $this->senderId,
            'recipients' => $this->recipients,
        ]);
    }
}