[![Packagist](https://img.shields.io/packagist/v/prefixaut/kamui.svg?style=flat-square)](https://packagist.org/packages/prefixaut/kamui)
[![GitHub release](https://img.shields.io/github/release/prefixaut/kamui.svg?style=flat-square)](https://github.com/prefixaut/Kamui/releases)
[![Travis](https://img.shields.io/travis/prefixaut/kamui.svg?style=flat-square)](https://travis-ci.org/prefixaut/Kamui)
[![license](https://img.shields.io/github/license/prefixaut/kamui.svg?style=flat-square)](https://github.com/prefixaut/Kamui/blob/master/LICENSE)
# Kamui
> A PHP-Wrapper for the Twitch API (Kraken)

Kamui is a complete Wrapper for the Twitch API which allows you to easily use it.
Currently it's supporting the most up to date version of the API (v5).

## Install
The best way to install Kamui is using [Composer](https://getcomposer.org):
```
composer require prefixaut/kamui 
```

## Usage
Using the API is really easy as it orientates a lot on the structure of the Original API.
Example usage:

```php
use Kamui\API;
$api = new API($my_twitch_token);
$api->users->follows('prefixaut'); // Will give you a List of all Channels I follow
```

Every function simply returns the original content of the Resource as Objects.
If any error occurs, it'll simply return false to prevent accidental breaks.
You can also use all Objects however you like them within the API like so:

```php
$cool_dude = $api->users->get('prefixaut');
$api->users->follows($cool_dude);
$api->users->follows($cool_dude->_id);
```

The `follows`-Function would return the exact same thing since it's still the same user.
This allows you to easily drop in whatever you want and focus on more important stuff.

## Testing
Kamui is using [PHPUnit-Tests](https://phpunit.de).
The best way to run them is to install it along with Composer.
When it's setup, run `phpunit` on the root of the Project.

## [Twitch-Emotes](https://www.twitchemotes.com/)
This Project is using the API from Twitch-Emotes to allow you an easier usage of them.
Example use of them is:

```php
$api->feed->reactToPost($user, $post, 'Kappa');
```

## License
This Project is licensed under the MIT-License.
Read the LICENSE-File inside this Project for more information.

> Sidenote: The Projects name comes from 'Akkorokamui'.
Google it up, too lazy to explain stuff
