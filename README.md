# Hadhiya MsgOwl SMS & OTP for Laravel

## A modern, fluent Laravel wrapper for the MsgOwl API. This package simplifies sending SMS and managing OTP (One-Time Password) verification for your applications, following standard PSR-4 conventions.

## Features

- **Fluent Message Builder**: Chainable methods for clean, readable code.
- **Dual Domain Support**: Seamlessly switches between standard SMS (`rest.msgowl.com`) and OTP (`otp.msgowl.com`) endpoints.
- **Laravel Notifications**: Integrated support for sending SMS via Laravel's built-in notification system.
- **Dry Run Mode**: Safely test your logic by logging messages locally instead of sending real (paid) SMS.
- **OTP Lifecycle**: Dedicated methods for Sending, Resending, and Verifying OTP codes using secure Key/Secret authentication.

---

## Installation

You can install the package via composer:

```bash
composer require hadhiya/msgowl

```

Publish the configuration file to your `config` directory:

```bash
php artisan vendor:publish --tag="msgowl-config"

```

---

## Configuration

Add your MsgOwl credentials to your `.env` file. These can be obtained from your [MsgOwl Dashboard](https://www.google.com/search?q=https://msgowl.com/dashboard).

```env
# Standard SMS Credentials
MSGOWL_API_KEY=your_access_key
MSGOWL_SENDER_ID=HADHIYA

# Separate credentials for OTP services
MSGOWL_OTP_KEY=your_otp_key
MSGOWL_OTP_SECRET=your_otp_secret

```

---

## Usage

### 1. Sending Standard SMS

You can send messages using the Facade with the fluent builder or a simple array.

```php
use Hadhiya\MsgOwl\Facades\MsgOwl;
use Hadhiya\MsgOwl\MsgOwlMessage;

// Using the Fluent Builder (Recommended)
MsgOwl::send(
    MsgOwlMessage::create("Your Hadhiya points have been updated!")
        ->to('9601234567')
        ->sender('HADHIYA')
);

// Quick send using an array
MsgOwl::send([
    'recipients' => '9601234567',
    'body' => 'Welcome to Hadhiya!',
]);

```

### 2. OTP Verification

Ideal for identity verification during NIC or Passport registration flows.

```php
// Send an OTP (defaults to 6 digits if not specified)
$response = MsgOwl::sendOtp('9601234567');

// Verify a code provided by the user
$status = MsgOwl::verifyOtp('9601234567', '123456');

if ($status->json('status')) {
    // Identity confirmed!
}

```

### 3. Laravel Notifications

Return a `MsgOwlMessage` from your `toMsgOwl` method to send SMS notifications to users.

```php
namespace App\Notifications;

use Hadhiya\MsgOwl\Channels\MsgOwlChannel;
use Hadhiya\MsgOwl\MsgOwlMessage;
use Illuminate\Notifications\Notification;

class TransferAlertNotification extends Notification
{
    public function via($notifiable)
    {
        return [MsgOwlChannel::class];
    }

    public function toMsgOwl($notifiable)
    {
        return MsgOwlMessage::create("You have received 100 HRF!")
            ->sender('HADHIYA');
    }
}

```

### 4. Development & Testing (Dry Run)

Use `dryRun()` to write the message data to your `laravel.log` file instead of making a live API request.

```php
MsgOwl::send(
    MsgOwlMessage::create("Testing Hadhiya integration...")
        ->to('9601234567')
        ->dryRun()
);

```

---

## Credits

- [Shinan Mohamed](https://www.google.com/search?q=https://github.com/shinanmhd)
- [All Contributors](https://www.google.com/search?q=../../contributors)

## License

The MIT License (MIT). Please see [License File](https://www.google.com/search?q=LICENSE.md) for more information.
