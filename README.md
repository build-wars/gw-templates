# build-wars/gw-templates

A [Guild Wars](https://www.guildwars.com) build template encoder/decoder

[![PHP Version Support][php-badge]][php]
[![Packagist version][packagist-badge]][packagist]
[![NPM version][npm-badge]][npm]
[![License][license-badge]][license]
[![Continuous Integration][gh-action-badge]][gh-action]
[![CodeCov][coverage-badge]][coverage]
[![Packagist downloads][downloads-badge]][downloads]

[php-badge]: https://img.shields.io/packagist/php-v/buildwars/gw-templates?logo=php&color=8892BF&logoColor=ccc
[php]: https://www.php.net/supported-versions.php
[packagist-badge]: https://img.shields.io/packagist/v/buildwars/gw-templates.svg?logo=packagist&logoColor=ccc
[packagist]: https://packagist.org/packages/buildwars/gw-templates
[npm-badge]: https://img.shields.io/npm/v/@buildwars/gw-templates?logo=npm&logoColor=ccc
[npm]: https://www.npmjs.com/package/@buildwars/gw-templates
[license-badge]: https://img.shields.io/github/license/build-wars/gw-templates.svg
[license]: https://github.com/build-wars/gw-templates/blob/main/LICENSE
[gh-action-badge]: https://img.shields.io/github/actions/workflow/status/build-wars/gw-templates/ci.yml?branch=main&logo=github&logoColor=ccc
[gh-action]: https://github.com/build-wars/gw-templates/actions/workflows/ci.yml?query=branch%3Amain
[coverage-badge]: https://img.shields.io/codecov/c/github/build-wars/gw-templates.svg?logo=codecov&logoColor=ccc
[coverage]: https://codecov.io/github/build-wars/gw-templates
[downloads-badge]: https://img.shields.io/packagist/dt/buildwars/gw-templates.svg?logo=packagist&logoColor=ccc
[downloads]: https://packagist.org/packages/buildwars/gw-templates/stats

# Overview

## Features

Encodes and decodes Guild Wars [skill](https://wiki.guildwars.com/wiki/Skill_template_format)
and [equipment](https://wiki.guildwars.com/wiki/Equipment_template_format)
templates, as well as [paw·ned²](https://memorial.redeemer.biz/pawned2/) team builds.


## Requirements

- PHP 8.1+
  - `ext-sodium`

alternatively:

- Javascript
  - node.js >= 24
  - a web browser

# Documentation

## PHP: Installation with [composer](https://getcomposer.org)

### Terminal
```
composer require buildwars/gw-templates
```

### composer.json
```json
{
	"require": {
		"php": "^8.1",
		"buildwars/gw-templates": "^1.1"
	}
}
```

Note: check the [releases](https://github.com/buildwars/gw-templates/releases) for valid versions.

## JS: Installation with [npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

### Terminal
```
npm install @buildwars/gw-templates
```

### package.json
```json
{
	"dependencies": {
		"@buildwars/gw-templates": "^1.1"
	}
}
```

### Direct include

You can also directly include the library in your HTML:
```html
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<!-- ... -->
</head>
<body>
	<!-- include the script at the bottom of the html body -->
	<script type="module">
		// import the script
		import * as templates from 'https://build-wars.github.io/gw-templates/js/gw-templates-es6.js';
		// alternative via unpkg
		// import * as templates from 'https://unpkg.com/@buildwars/gw-templates@1.1.0/dist/gw-templates-es6.js';

		// do stuff
	</script>
</body>
</html>
```

Please note that the include from GitHub pages represents the development version, which is built on each push to the main branch. Use NPM or unpkg instead for stable versions.


## Usage

### Decode Skill Templates

**PHP**
```php
$skills = (new SkillTemplate)->decode('OwFj0xfzITOMMMHMie4O0kxZ6PA');
// static convenience methods:
$skills = SkillTemplate::fromTemplate('OwFj0xfzITOMMMHMie4O0kxZ6PA');
$skills = SkillTemplate::fromChatCode('[My Cool Build;OwFj0xfzITOMMMHMie4O0kxZ6PA]');
```

**JavaScript :coffee:**
```js
let skills = new SkillTemplate().decode('OwFj0xfzITOMMMHMie4O0kxZ6PA');
// static convenience methods:
skills = SkillTemplate.fromTemplate('OwFj0xfzITOMMMHMie4O0kxZ6PA');
skills = SkillTemplate.fromChatCode('[My Cool Build;OwFj0xfzITOMMMHMie4O0kxZ6PA]');
```

#### Return

A call to the `decode()` method returns an array (PHP) or object (JS) similar to the following:
```
Array
(
    [code] => OwFj0xfzITOMMMHMie4O0kxZ6PA
    [prof_pri] => 7
    [prof_sec] => 1
    [attributes] => Array
        (
            [29] => 12
            [31] => 3
            [35] => 12
        )
    [skills] => Array
        (
            [0] => 782
            [1] => 780
            [2] => 775
            [3] => 1954
            [4] => 952
            [5] => 2356
            [6] => 1649
            [7] => 1018
        )
)
```


### Encode Skill Templates

**PHP**
```php
$code = (new SkillTemplate)->encode(
	prof_pri:   7,
	prof_sec:   1,
	attributes: [29 => 12, 31 => 3, 35 => 12],
	skills:     [782, 780, 775, 1954, 952, 2356, 1649, 1018],
);
// -> base64 skill template
```

**JavaScript :coffee:**
```js
let code = new SkillTemplate().encode(
	7,
	1,
	{'29': 12, '31': 3, '35': 12},
	[782, 780, 775, 1954, 952, 2356, 1649, 1018],
);
// -> base64 skill template
```

Please note that the base64 template codes might not necessarily match between decode/encode.


### Decode Equipment Templates

**PHP**
```php
$equipment = (new EquipmentTemplate)->decode('PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF');
// static convenience methods:
$equipment = EquipmentTemplate::fromTemplate('PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF');
$equipment = EquipmentTemplate::fromChatCode('[My Cool Equipment;PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF]');
```

**JavaScript :coffee:**
```js
let equipment = new EquipmentTemplate().decode('PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF');
// static convenience methods:
equipment = EquipmentTemplate.fromTemplate('PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF');
equipment = EquipmentTemplate.fromChatCode('[My Cool Equipment;PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF]');
```

#### Return

A call to the `decode()` method returns an array (PHP) or object (JS) similar to the following:
```
Array
(
    [0] => Array
        (
            [id] => 279
            [slot] => 0
            [color] => 9
            [mods] => Array
                (
                    [0] => 190
                    [1] => 204
                    [2] => 329
                )
        )

    ...more items...
)
```
Note: the keys of the returned array are the slot IDs (0-6) - they may not be sequential or ordered


### Encode Equipment Templates

**PHP**
```php
$equipmentTemplate = new EquipmentTemplate;

// add items (will overwrite previous items with same slot id)
$equipmentTemplate->addItem(
	id:    279,
	color: 0,
	mods:  [190, 204, 329],
);

// ... add more items

$code = $equipmentTemplate->encode(); // -> base64 equipment template
```

**JavaScript :coffee:**
```js
let equipmentTemplate = new EquipmentTemplate();

// add iems (will overwrite previous items with same slot id)
equipmentTemplate.addItem(279, 0, [190, 204, 329]);

// ... add more items

let code = equipmentTemplate.encode(); // -> base64 equipment template
```


### Decode paw·ned² Templates

The paw-ned² template:

```
pwnd0000?download paw·ned² @ www.gw-tactics.de Copyright numma_cway aka Redeemer
>aOwFj0xfzITOMMMHMie4O0k6PxZpPkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFFAAACgJAAMM
SAtIFdvdEEKZOAOj4wiM5MXTMm3cZS9dJOu5BpPkppFFEqtEAFEqncAFEaqmAFEaY7/EEaYRIHeqXjEA
AACAgAATMiAtIFNvUy9TbWl0ZQoZOQNEApwT2zQDmemuhQOIDQEQjoPgp5PCicJCDBR6JzigItw4SQkh
tDIIyMgJHeqXjEPPgpghmZ9phOzriUAACIhAAOMyAtIFBhbml4CgZOQNDAcw9QvAIg5ZjOkAcQOBoRoP
gpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6TjdMvKSBAABMAAONCAtIEluZXAxCgZOQ
NDAawDSvAIg5ZrAFgZAEBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6TjdMvKS
BAACMBAAONSAtIEluZXAyCgbOAhkQkGZIfMzdwQM0qqSzJnw7iBoPgpZRCi8JiYBR6JXsgI7wMWQkhtD
LISOALHeqXjELPkZwUP9akeKAACgJAALNiAtIEJpUAoZOAWiQyhMp7INN5I8Y5wJOOZNBpPkpxUP96Xf
q4npI908npIDLropIvV3npIDr7npITFAAACEBAAONyAtIFJlc3RvCgXOAOiAyk8gNtehzWilD56MvYpP
kp5EFEKuEAFEqncAFEaqmAFEaY7/EEaYBIHiKbkILPkZAIP9akeKAACgBAAKOCAtIFNUCgYOABCY4xEA
glAj4ngdQVFAQZAoPgpxlne9rPVaYKSPNvMFJYJRmiEKtATRGW7ipI7AAAAAABgNSAtIE1vUApzZWNvb
mRhcnkgcHJvZmVzc2lvbiBhbmQgZWxpdGUgc2tpbGwgYXJlIGZyZWUsIGJhcmJzIGlzIG9wdGlvbmFsY
OgNDwcjvOkk6hWEqtp9H0iaBpPkpBUPbTkiqwmpI900mpIDLbipIvSvmpIDrzmpINBAAADAAgAAMNyAt
IEUvTW8K<
```

**PHP**
```php
$team = (new PwndTemplate)->decode($pwnd);
// static convenience method:
$team = PwndTemplate::fromTemplate($pwnd);
```

**JavaScript :coffee:**
```js
let team = new PwndTemplate().decode(pwnd);
// static convenience method:
team = PwndTemplate.fromTemplate($pwnd);
```

#### Return

A call to the `decode()` method returns an array (PHP) or object (JS) similar to the following:
```
Array
(
    [0] => Array
        (
            [skills] => OwFkMyd534lkDjzzBjoHuDNZcm+D
            [equipment] => PkpxFP9FySqIlpI90MlpIDLfYpI7oMFZpcpMFpoA
            [weaponsets] => Array
                (
                    [0] => PkZATPZjlsI
                    [1] => PcZg8z6QJpC
                    [2] => PgpgnnN4SJNSauVlC
                )
            [templatename] => 1 - WotA
            [description] =>
            [player] => Player
            [attributes] => Array
                (
                    [0] => 4
                    [1] => 0
                    [2] => 1
                    [3] => 1
                    [4] => 0
                )
            [flags] => Array
                (
                    [0]  => false
                    [1]  => false
                    [2]  => false
                    [3]  => false
                    [4]  => false
                    [5]  => false
                    [6]  => false
                    [7]  => false
                    [8]  => false
                    [9]  => false
                    [10] => false
                    [11] => false
                    [12] => false
                    [13] => true
                    [14] => true
                    [15] => true
                )
        )

        ...more builds...
)
```

### Encode paw·ned² Templates

**PHP**
```php
// We're using Windows-1252 encoding here (default: UTF-8)
$pwndTemplate = new PwndTemplate(PwndTemplate::PAWNED_CHARSET_WINDOWS1252);

$pwndTemplate->addBuild(
	skills:       'OwFj0xfzITOMMMHMie4O0kxZ6PA',
	equipment:    'PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF',
	weaponsets:   ['PcZQ8zoRpkC'],
	templatename: '<template/build name>',
	description:  '<description>',
	player:       '<assigned player/hero>',
	attributes:   [4, 0, 1, 1, 0],
	flags:        [],
);

// add more builds (up to 12)

$pwnd = $pwndTemplate->encode(); // -> pwnd template code
```

**JS**
```js
let pwndTemplate = new PwndTemplate(PwndTemplate.PAWNED_CHARSET_WINDOWS1252);

pwndTemplate.addBuild(
	'OwFj0xfzITOMMMHMie4O0kxZ6PA',
	'PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF',
	['PcZQ8zoRpkC'],
	'<template/build name>',
	'<description>',
	'<assigned player/hero>',
	[4, 0, 1, 1, 0],
	[],
);

let pwnd = pwndTemplate.encode(); // -> pwnd template code
```



# Disclaimer

Use at your own risk!
