# paw·ned² template format

The [paw·ned²](https://redeemer.biz/guild-wars/projekte/pawned2/) template format is a proprietary file format,
used to share [Guild Wars](https://www.guildwars.com/) team builds.

## Header

The header consists of a 4 byte prefix `pwnd`, followed by 4 bytes flags.

```
pwnd0001
```

### Header-Flags

The header-flags are 4 integer values, starting from byte 0 from the prefix:

- byte 4: breaking version, template format change
- byte 5: unused, always 0
- byte 6: unused, always 0
- byte 7: character encoding used in name and description fields
	- `0`: undefined/ANSI
	- `1`: Windows-1252
	- `2`: UTF-8


### Comments
The header may be followed by a comment-like string. While optional, paw·ned² and other encoders use a question mark `?` to separate the comment from the header.

An example header with comment:

```
pwnd0001?download pawned2 @ memorial.redeemer.biz | Copyright 2008-2018 Redeemer
```

## Body

The body is a base64-like string, enclosed by a *greater than* `>` and a *less than* `<` sign which signify start and end of the body.
Anything between the header and the `>`, and after `<` is ignored. Newlines are entirely optional and will be ignored by decoders.
The template code should not be followed by anything but whitespace after the closing `<` character.

An example body:
```
>aOwFj0xfzITOMMMHMie4O0kxZ6PAAAACgBAAHV290QQoXOQhDMJkCLu90b6XAAkBExFDAAAACIhAAGV
m9SCgZOQhDEMkTSvAIg5ZFgAAZAEBXMAAAACMBAAHSW5lcAoZOAhjYghr4OYMp5kT4NMHnVVAAAAAACA
gAAUU3BsaW50ZXItUmVzdG8K<
```

The base64-like string contains the base64-encoded information for one or more player builds, including:

- skill template
- equipment template for armor, runes and insigias and primary weaponset
- 0-3 additional weaponsets
- player name
- description (which contains the template name)
- flags for additional attribute values and consumable items

Each field is preceded by one or two bytes length information, which is determined by reading the position of the given character in the base64 alphabet.

### Skill template

The skill template is preceded by one byte length information (a maximum of 63 bytes), followed by the base64-encoded template code which is processed according to the [skill template format](https://wiki.guildwars.com/wiki/Skill_template_format).

### Equipment template

The equipment template is preceded by one byte length information as well, followed by the base64-encoded template code which is processed according to the [equipment template format](https://wiki.guildwars.com/wiki/Equipment_template_format).

### Additional weaponsets

The additional weaponsets are 3 fields, each processed in the same way as the equipment template.

### Flags

The flags field is preceded by one byte length information, fillowed by a base64-encoded string that is decoded it the same way as the template codes.

#### Attribute bonuses

The first 15 bits are attribute bonuses for display in the UI, 5 times 3 bits (a maximum of bonus value of 7) for each attribute of the primary profession ordered by attribute ID (with exception of the primary attribute which is always the first value), followed by 3 empty bits.

#### Flags

The flags start from bit 18; they are a bitmask enumerated according to the following list, starting from the leftmost bit as flag 0:

```
buLunarFortune
buCandyCorn
buGoldenEgg
buBirthdayCupcake
buSliceOfPumpkinPie
buCandyApple
buWarSupplies
buDrakeKabob
buBowlOfSkalefinSoup
buPahnaiSalad
buGreenRockCandy
buBlueRockCandy
buRedRockCandy
buEssenceOfCelerity
buArmorOfSalvation
buGrailOfMight
```

Currently not implemented are the "of The \<Profession\>" mods, starting from flag 16 onwards

```
buOfTheWarrior
buOfTheRanger
buOfTheMonk
buOfTheNecromancer
buOfTheMesmer
buOfTheElementalist
buOfTheAsssassin
buOfTheRitualist
buOffTheParagon
buOffTheDervish
```

### Player name

The player name is preceded by one byte length information, followed by a base64-encoded string that is encoded according to the character set given in the header.

### Template name and description

The template name and description share a single field, preceded by two bytes length information, followed by the base64-encoded description.
The read-length is calculated by multiplying the base64 ordinal of the first byte by 64, then adding base64 ordinal of the second byte.

The content of the field is variable length with a total of 256 bytes minus one for a newline character `\n` (the first occurence) that splits template name and description content.
Both strings are encoded according to the character set given in the header. If template name and description both are empty, the field will still contain a single newline character.

## Overview

| field                    | length bytes | max base64 length | max raw length (bytes)   | info                                                                                                                                                  |
|--------------------------|--------------|-------------------|--------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------|
| skills                   | 1            | 63                | -                        | [skill template](https://wiki.guildwars.com/wiki/Skill_template_format)                                                                               |
| equipment                | 1            | 63                | -                        | [equipment template](https://wiki.guildwars.com/wiki/Equipment_template_format)                                                                       |
| weaponsets (3x)          | 1 each       | 63 each           | -                        | [equipment template](https://wiki.guildwars.com/wiki/Equipment_template_format)                                                                       |
| flags                    | 1            | 63                | 5 (with zero-padding)    | currently a total of 34 bits: 18 bits attribute bonuses, 16 bits flags for consumable items, encoded in the same way as skill and equipment templates |
| player                   | 1            | 63                | 32, 48 in UTF-8 mode (?) | character encoding according to header                                                                                                                |
| templatename/description | 2            | 5 * 64 + 63       | 256 (255 + `\n`)         | variable length for both fields, separated by the first occurence of `\n`, character encoding according to header                                     |
