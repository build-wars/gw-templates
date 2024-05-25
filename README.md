# build-wars/gw-templates

A [Guild Wars](https://www.guildwars.com) build template encoder/decoder

[![PHP Version Support][php-badge]][php]
[![Packagist version][packagist-badge]][packagist]
[![License][license-badge]][license]
[![Continuous Integration][gh-action-badge]][gh-action]
[![CodeCov][coverage-badge]][coverage]
[![Packagist downloads][downloads-badge]][downloads]

[php-badge]: https://img.shields.io/packagist/php-v/build-wars/gw-templates?logo=php&color=8892BF&logoColor=fff
[php]: https://www.php.net/supported-versions.php
[packagist-badge]: https://img.shields.io/packagist/v/build-wars/gw-templates.svg?logo=packagist&logoColor=fff
[packagist]: https://packagist.org/packages/build-wars/gw-templates
[license-badge]: https://img.shields.io/github/license/build-wars/gw-templates
[license]: https://github.com/build-wars/gw-templates/blob/main/LICENSE
[gh-action-badge]: https://img.shields.io/github/actions/workflow/status/build-wars/gw-templates/ci.yml?branch=main&logo=github&logoColor=fff
[gh-action]: https://github.com/build-wars/gw-templates/actions/workflows/ci.yml?query=branch%3Amain
[coverage-badge]: https://img.shields.io/codecov/c/github/build-wars/gw-templates.svg?logo=codecov&logoColor=fff
[coverage]: https://codecov.io/github/build-wars/gw-templates
[downloads-badge]: https://img.shields.io/packagist/dt/build-wars/gw-templates.svg?logo=packagist&logoColor=fff
[downloads]: https://packagist.org/packages/build-wars/gw-templates/stats

# Overview

## Features

Encodes and decodes Guild Wars [skill](https://wiki.guildwars.com/wiki/Skill_template_format)
and [equipment](https://wiki.guildwars.com/wiki/Equipment_template_format)
templates, as well as [paw·ned²](https://memorial.redeemer.biz/pawned2/) team builds.


## Requirements

- PHP 8.1+
  - `ext-sodium`

# Documentation

## Installation with [composer](https://getcomposer.org)

### Terminal

```
composer require buildwars/gw-templates
```


### composer.json

```json
{
	"require": {
		"php": "^8.1",
		"buildwars/gw-templates": "^1.0"
	}
}
```

Note: check the [releases](https://github.com/buildwars/gw-templates/releases) for valid versions.


## Usage

### Skill templates

**Encode**

```php
$code = (new SkillTemplate)->encode(
	prof_pri:   7,
	prof_sec:   1,
	attributes: [29 => 12, 31 => 3, 35 => 12],
	skills:     [782, 780, 775, 1954, 952, 2356, 1649, 1018],
);
// -> base64 skill template
```

**Decode**

```php
$skills = (new SkillTemplate)->decode('OwFj0xfzITOMMMHMie4O0kxZ6PA');
```

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

Please note that the codes might not necessarily match between decode/encode.


### Equipment templates

**Encode**

```php
$equipmentTemplate = new EquipmentTemplate;

// add iems (will overwrite previous items with same slot id)
$equipmentTemplate->addItem(
	id:    279,
	color: 0,
	mods:  [190, 204, 329],
);

// ... add more items

$code = $equipmentTemplate->encode(); // -> base64 equipment template
```

**Decode**

```php
$equipment = (new EquipmentTemplate)->decode('PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF');
```

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
    [2] => Array
        (
            [id] => 164
            [slot] => 2
            [color] => 9
            [mods] => Array
                (
                    [0] => 290
                    [1] => 158
                )
        )
    [3] => Array
        (
            [id] => 166
            [slot] => 3
            [color] => 9
            [mods] => Array
                (
                    [0] => 290
                    [1] => 353
                )
        )
    [4] => Array
        (
            [id] => 271
            [slot] => 4
            [color] => 9
            [mods] => Array
                (
                    [0] => 290
                    [1] => 179
                )
        )
    [5] => Array
        (
            [id] => 0
            [slot] => 5
            [color] => 9
            [mods] => Array
                (
                    [0] => 290
                    [1] => 165
                )
        )
    [6] => Array
        (
            [id] => 165
            [slot] => 6
            [color] => 9
            [mods] => Array
                (
                    [0] => 290
                    [1] => 162
                )
        )
)
```


### paw·ned² templates

**Encode**

```php
$pwndTemplate = new PwndTemplate;

$pwndTemplate->addBuild(
	skills:      'OwFj0xfzITOMMMHMie4O0kxZ6PA',
	equipment:   'PkpxFP9FzSqIlpI90MlpIDLfopInVBgpILlLlpIFF',
	weaponsets:  ['PcZQ8zoRpkC'],
	player:      '<assigned player/hero>',
	description: '<build name>' . "\r\n" . '<description>',
);

// add more builds (up to 12)

$pwnd = $pwndTemplate->encode(); // -> pwnd template code
```

**Decode**

```php
$pwnd = <<<PWND
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
PWND;

$team = (new PwndTemplate)->decode($pwnd);
```

```
Array
(
    [0] => Array
        (
            [skills] => OwFj0xfzITOMMMHMie4O0k6PxZ
            [equipment] => PkpxFP9FzSqAA5AAJBAZBApBAJ
            [weaponsets] => Array
                (
                    [0] =>
                    [1] =>
                    [2] =>
                )
            [player] => Player
            [description] => 1 - WotA
        )
    [1] => Array
        (
            [skills] => OAOj4wiM5MXTMm3cZS9dJOu5B
            [equipment] => PkppFFEqtEAFEqncAFEaqmAFEaY7/EEaYRIHeqXjE
            [weaponsets] => Array
                (
                    [0] =>
                    [1] =>
                    [2] =>
                )
            [player] => Xandra
            [description] => 2 - SoS/Smite
        )
    [2] => Array
        (
            [skills] => OQNEApwT2zQDmemuhQOIDQEQj
            [equipment] => Pgp5PCicJCDBR6JzigItw4SQkhtDIIyMgJHeqXjE
            [weaponsets] => Array
                (
                    [0] => PgpghmZ9phOzriU
                    [1] =>
                    [2] =>
                )
            [player] => Gwen
            [description] => 3 - Panix
        )
    [3] => Array
        (
            [skills] => OQNDAcw9QvAIg5ZjOkAcQOBoR
            [equipment] => PgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjE
            [weaponsets] => Array
                (
                    [0] => PkpwRNz6TjdMvKSB
                    [1] =>
                    [2] =>
                )
            [player] => Norgu
            [description] => 4 - Inep1
        )
    [4] => Array
        (
            [skills] => OQNDAawDSvAIg5ZrAFgZAEBoR
            [equipment] => PgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjE
            [weaponsets] => Array
                (
                    [0] => PkpwRNz6TjdMvKSB
                    [1] =>
                    [2] =>
                )
            [player] => Razah or [Mercenary]
            [description] => 5 - Inep2
        )
    [5] => Array
        (
            [skills] => OAhkQkGZIfMzdwQM0qqSzJnw7iB
            [equipment] => PgpZRCi8JiYBR6JXsgI7wMWQkhtDLISOALHeqXjE
            [weaponsets] => Array
                (
                    [0] => PkZwUP9akeK
                    [1] =>
                    [2] =>
                )
            [player] => Livia
            [description] => 6 - BiP
        )
    [6] => Array
        (
            [skills] => OAWiQyhMp7INN5I8Y5wJOOZNB
            [equipment] => PkpxUP96Xfq4npI908npIDLropIvV3npIDr7npITF
            [weaponsets] => Array
                (
                    [0] =>
                    [1] =>
                    [2] =>
                )
            [player] => Razah or [Mercenary]
            [description] => 7 - Resto
        )
    [7] => Array
        (
            [skills] => OAOiAyk8gNtehzWilD56MvY
            [equipment] => Pkp5EFEKuEAFEqncAFEaqmAFEaY7/EEaYBIHiKbkI
            [weaponsets] => Array
                (
                    [0] => PkZAIP9akeK
                    [1] =>
                    [2] =>
                )
            [player] => Zei Ri
            [description] => 8 - ST
        )
    [8] => Array
        (
            [skills] => OABCY4xEAglAj4ngdQVFAQZA
            [equipment] => Pgpxlne9rPVaYKSPNvMFJYJRmiEKtATRGW7ipI7A
            [weaponsets] => Array
                (
                    [0] =>
                    [1] =>
                    [2] =>
                )
            [player] => Olias
            [description] => 5 - MoP
secondary profession and elite skill are free, barbs is optional
        )
    [9] => Array
        (
            [skills] => OgNDwcjvOkk6hWEqtp9H0iaB
            [equipment] => PkpBUPbTkiqwmpI900mpIDLbipIvSvmpIDrzmpINB
            [weaponsets] => Array
                (
                    [0] =>
                    [1] =>
                    [2] =>
                )
            [player] => Zhed Shadowhoof
            [description] => 7 - E/Mo
        )
)
```

# Disclaimer

Use at your own risk!
