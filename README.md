![PHP Composer build](https://github.com/verizxn/VZTelegramBot/actions/workflows/php.yml/badge.svg)

# VZTelegramBot
Lightweight Telegram webhook bot written in PHP.

## Installation
Require this project with [composer](https://getcomposer.org/).
```json
{
    ...
    "repositories": [
        {
            "type": "vcs",
            "url":  "https://github.com/verizxn/VZTelegramBot"
        }
    ],
    "require": {
        "verizxn/vztelegrambot": "dev-main"
    }
}
```
Then run in your terminal: `composer update`.

## Usage
Check `tests/webhook.php` for the webhook example code.
Check `tests/cli.php` for the command line example code.
Check Telegram documentation [here](https://core.telegram.org/bots/api).
