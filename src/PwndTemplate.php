<?php
/**
 * Class PwndTemplate
 *
 * @created      21.05.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace BuildWars\GWTemplates;

use InvalidArgumentException;
use RuntimeException;
use Throwable;
use function array_chunk;
use function array_fill;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_sum;
use function array_values;
use function base64_decode;
use function boolval;
use function count;
use function explode;
use function implode;
use function intdiv;
use function intval;
use function is_string;
use function mb_detect_encoding;
use function mb_internal_encoding;
use function rtrim;
use function sprintf;
use function str_contains;
use function str_replace;
use function str_split;
use function str_starts_with;
use function strlen;
use function strpos;
use function strrpos;
use function substr;
use function trim;

/**
 * Biblically accurate paw·ned² team build encoder/decoder
 *
 * Thanks to Redeemer (paw·ned² developer) and Antodias (formerly gwcom.de)!
 *
 * @link https://memorial.redeemer.biz/pawned2/
 */
final class PwndTemplate extends TemplateAbstract{

	public const PWND_HEADER_COMMENT = 'pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates';

	public const PAWNED_CHARSET_UNDEFINED   = 0;
	public const PAWNED_CHARSET_WINDOWS1252 = 1;
	public const PAWNED_CHARSET_UTF8        = 2;

	public const ENCODING_UNDEFINED   = 'undefined';
	public const ENCODING_ASCII       = 'ASCII';
	public const ENCODING_WINDOWS1252 = 'Windows-1252';
	public const ENCODING_UTF8        = 'UTF-8';


	public const CHARSETS = [
		self::PAWNED_CHARSET_UNDEFINED   => self::ENCODING_UNDEFINED,
		self::PAWNED_CHARSET_WINDOWS1252 => self::ENCODING_WINDOWS1252,
		self::PAWNED_CHARSET_UTF8        => self::ENCODING_UTF8,
	];

	public const CON_LUNAR_FORTUNE       = 0;
	public const CON_CANDY_CORN          = 1;
	public const CON_GOLDEN_EGG          = 2;
	public const CON_BDAY_CUPCAKE        = 3;
	public const CON_PUMPKIN_PIE         = 4;
	public const CON_CANDY_APPLE         = 5;
	public const CON_WAR_SUPPLIES        = 6;
	public const CON_DRAKE_KABOB         = 7;
	public const CON_SKALEFIN_SOUP       = 8;
	public const CON_PAHNAI_SALAD        = 9;
	public const CON_GREEN_CANDY         = 10;
	public const CON_BLUE_CANDY          = 11;
	public const CON_RED_CANDY           = 12;
	public const CON_ESSENCE_OF_CELERITY = 13;
	public const CON_ARMOR_OF_SALVATION  = 14;
	public const CON_GRAIL_OF_MIGHT      = 15;
	// not yet implemented
#	public const MOD_OF_THE_WARRIOR      = 16;
#	public const MOD_OF_THE_RANGER       = 17;
#	public const MOD_OF_THE_MONK         = 18;
#	public const MOD_OF_THE_NECROMACER   = 19;
#	public const MOD_OF_THE_MESMER       = 20;
#	public const MOD_OF_THE_ELEMENTALIST = 21;
#	public const MOD_OF_THE_ASSASSIN     = 22;
#	public const MOD_OF_THE_RITUALIST    = 23;
#	public const MOD_OF_THE_PARAGON      = 24;
#	public const MOD_OF_THE_DERVISH      = 25;

	public const FLAGS = [
		self::CON_LUNAR_FORTUNE       => 'buLunarFortune',
		self::CON_CANDY_CORN          => 'buCandyCorn',
		self::CON_GOLDEN_EGG          => 'buGoldenEgg',
		self::CON_BDAY_CUPCAKE        => 'buBirthdayCupcake',
		self::CON_PUMPKIN_PIE         => 'buSliceOfPumpkinPie',
		self::CON_CANDY_APPLE         => 'buCandyApple',
		self::CON_WAR_SUPPLIES        => 'buWarSupplies',
		self::CON_DRAKE_KABOB         => 'buDrakeKabob',
		self::CON_SKALEFIN_SOUP       => 'buBowlOfSkalefinSoup',
		self::CON_PAHNAI_SALAD        => 'buPahnaiSalad',
		self::CON_GREEN_CANDY         => 'buGreenRockCandy',
		self::CON_BLUE_CANDY          => 'buBlueRockCandy',
		self::CON_RED_CANDY           => 'buRedRockCandy',
		self::CON_ESSENCE_OF_CELERITY => 'buEssenceOfCelerity',
		self::CON_ARMOR_OF_SALVATION  => 'buArmorOfSalvation',
		self::CON_GRAIL_OF_MIGHT      => 'buGrailOfMight',
		// not yet implemented
#		self::MOD_OF_THE_WARRIOR      => 'buOfTheWarrior',
#		self::MOD_OF_THE_RANGER       => 'buOfTheRanger',
#		self::MOD_OF_THE_MONK         => 'buOfTheMonk',
#		self::MOD_OF_THE_NECROMACER   => 'buOfTheNecromancer',
#		self::MOD_OF_THE_MESMER       => 'buOfTheMesmer',
#		self::MOD_OF_THE_ELEMENTALIST => 'buOfTheElementalist',
#		self::MOD_OF_THE_ASSASSIN     => 'buOfTheAsssassin',
#		self::MOD_OF_THE_RITUALIST    => 'buOfTheRitualist',
#		self::MOD_OF_THE_PARAGON      => 'buOffTheParagon',
#		self::MOD_OF_THE_DERVISH      => 'buOffTheDervish',
	];

	private const BASE64             = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
	private const PWND_HEADER_PREFIX = 'pwnd';

	/**
	 * @var array{skills: string, equipment: string, weaponsets: string[], flags: string, player: string, description: string}[]
	 */
	private array $builds = [];

	private readonly string $encoding;
	private readonly int    $encodingFlag;

	/**
	 * PwndTemplate constructor
	 *
	 * The `$encodingFlag` parameter is only used in template encoding operations.
	 * Template decoding uses the flag given in the template header.
	 */
	public function __construct(int $encodingFlag = self::PAWNED_CHARSET_UTF8){
		$this->encoding     = self::getEncoding($encodingFlag);
		$this->encodingFlag = $encodingFlag;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function fromChatCode(string $chatCode):array{
		throw new RuntimeException('not supported');
	}

	/**
	 * Returns the character encoding given in the header flag
	 *
	 * @see \mb_list_encodings()
	 * @throws \InvalidArgumentException
	 */
	public static function getEncoding(int $encodingFlag):string{

		if(!array_key_exists($encodingFlag, self::CHARSETS)){
			throw new InvalidArgumentException('invalid encoding flag'); // @codeCoverageIgnore
		}

		return self::CHARSETS[$encodingFlag];
	}

	/**
	 * Parses the paw-ned² header flags
	 *
	 *   0: breaking change
	 *   1: unused
	 *   2: unused
	 *   3: character encoding
	 *
	 * @throws \RuntimeException
	 */
	public static function parseHeader(string $pwnd):array{
		$header = substr($pwnd, 0, 8);

		if(!str_starts_with($header, 'pwnd')){
			throw new RuntimeException('invalid paw-ned² template');
		}

		// read header flags, skip over the "pwnd"
		$headerFlags = array_map(intval(...), str_split(substr($header, 4)));

		if($headerFlags[0] !== 0){
			throw new RuntimeException('paw-ned² breaking change detected'); // @codeCoverageIgnore
		}

		return $headerFlags;
	}

	/**
	 * Decodes the given paw-ned² template into an array
	 *
	 * @return array{skills: string, equipment:string, weaponsets: string[], templatename: string, description: string, player: string, attributes: int[], flags: bool[]}[]
	 * @throws \InvalidArgumentException
	 */
	public function decode(string $template):array{
		// detect encoding (we're doing this first as the header parser will throw on invalid template)
		$headerFlags = self::parseHeader($template);
		$encoding    = self::getEncoding($headerFlags[3]);

		// find the template string
		$pwnd  = str_replace(["\r", "\n", "\t"], '', trim($template));
		$start = strrpos($pwnd, '>'); // maybe not the best idea... (better than reading from the start tho)
		$end   = strpos($pwnd, '<', $start);

		if($start === false || $end === false || ($end - 1) <= $start){
			throw new InvalidArgumentException('invalid paw-ned² template');
		}

		// fix possible URL de/encoding errors in the base64 string
		$this->string = $this->checkCharacterSet(substr($pwnd, ($start + 1), ($end - 1 - $start)));
		$this->offset = 0;

		$total  = strlen($this->string);
		$builds = [];

		while($this->offset < $total){
			$skills     = $this->readString();
			$equipment  = $this->readString();
			$weaponsets = [];

			for($i = 0; $i < 3; $i++){
				$weaponsets[$i] = $this->readString();
			}

			$extra  = $this->readString();
			$player = $this->readString();
			$desc   = $this->readString(true);

			[$templatename, $description] = $this->decodeDescription($desc, $encoding);
			/** @phan-suppress-next-line PhanTypeInvalidDimOffsetArrayDestructuring ??? */
			[$attributeBonuses, $flags]   = $this->decodeFlags($extra);

			$builds[] = [
				'skills'       => $skills,
				'equipment'    => $equipment,
				'weaponsets'   => $weaponsets,
				'templatename' => $templatename,
				'description'  => $description,
				'player'       => $this->decodePlayerName($player, $encoding),
				'attributes'   => $attributeBonuses,
				'flags'        => $flags,
			];
		}

		return $builds;
	}

	/**
	 * Encodes the given build(s) into a paw-ned² template
	 */
	public function encode():string{
		$pwnd = '>';

		foreach($this->builds as $build){
			$pwnd .= $this->writeString($build['skills']);
			$pwnd .= $this->writeString($build['equipment']);

			foreach($build['weaponsets'] as $set){
				$pwnd .= $this->writeString($set);
			}

			$pwnd .= $this->writeString($build['flags']);
			$pwnd .= $this->writeString($build['player']);
			$pwnd .= $this->writeString($build['description'], true);
		}

		$pwnd .= '<';

		return sprintf(
			"%s%s?%s\n%s",
			self::PWND_HEADER_PREFIX,
			$this->writeHeaderFlags(),
			self::PWND_HEADER_COMMENT,
			implode("\n", str_split($pwnd, 80)),
		);
	}

	/**
	 * Adds a build item
	 *
	 * @param string[] $weaponsets
	 * @param int[]    $attributes
	 * @param bool[]   $flags
	 * @throws \InvalidArgumentException
	 */
	public function addBuild(
		string $skills,
		string $equipment = '',
		array  $weaponsets = [],
		string $templatename = '',
		string $description = '',
		string $player = '',
		array  $attributes = [],
		array  $flags = [],
	):self{

		if(count($this->builds) >= 12){
			throw new InvalidArgumentException('maximum 12 builds'); // @codeCoverageIgnore
		}

		$this->builds[] = [
			'skills'      => $this->checkCharacterSet($skills),
			'equipment'   => $this->checkCharacterSet($equipment),
			'weaponsets'  => $this->normalizeWeaponsets($weaponsets),
			'flags'       => $this->encodeFlags($attributes, $flags),
			'player'      => $this->encodePlayername($player, $this->encoding),
			'description' => $this->encodeDescription($templatename, $description, $this->encoding),
		];

		return $this;
	}

	/**
	 * Clears all currently added build items
	 *
	 * @codeCoverageIgnore
	 */
	public function clearBuilds():self{
		$this->builds = [];

		return $this;
	}

	/**
	 * Checks/normalizes the given weapon sets, limits input to 3 items
	 *
	 * @param  string[] $weaponsets
	 * @return string[]
	 */
	private function normalizeWeaponsets(array $weaponsets):array{
		$normalizedWeaponsets = ['', '', ''];

		$i = 0;

		foreach($weaponsets as $weaponset){

			if($i > 2){
				break;
			}

			// nope
			if(!is_string($weaponset)){
				continue;
			}

			$weaponset = trim($weaponset);

			// skip empty
			if($weaponset === ''){
				continue;
			}

			// we're being generous and just skip invalid items
			try{
				$weaponset = $this->checkCharacterSet($weaponset);
			}
			catch(Throwable){
				continue;
			}

			$normalizedWeaponsets[$i] = $weaponset;

			$i++;
		}

		return $normalizedWeaponsets;
	}

	/**
	 * Decodes the player name field
	 */
	private function decodePlayerName(string $playerB64, string $from_encoding):string{
		$player = $this->base64decode($playerB64);

		return $this->decodeField($player, $from_encoding);
	}

	/**
	 * Encodes the player name field according to the given character encoding
	 *
	 * @throws \InvalidArgumentException
	 */
	private function encodePlayername(string $name, string $to_encoding):string{
		$name = trim($name);

		if($name === ''){
			return '';
		}

		$encodedName = $this->encodeField($name, $to_encoding);
		$encodedLen  = strlen($encodedName);

		// 32 in windows-1252/ASCII, 48 in utf-8, apparently
		if($this->encodingFlag !== self::PAWNED_CHARSET_UTF8 && $encodedLen > 32){
			throw new InvalidArgumentException('player name cannot be longer than 32 bytes');
		}
		elseif($encodedLen > 48){
			throw new InvalidArgumentException('player name cannot be longer than 48 bytes in UTF-8 mode');
		}

		return $this->base64encode($encodedName);
	}

	/**
	 * Decodes the template name/description field
	 *
	 * @return array{0: string, 1: string}
	 */
	private function decodeDescription(string $descB64, string $from_encoding):array{
		$desc = $this->base64decode($descB64);

		// the LF should always be present, even if both fields are empty
		if(strlen($desc) <= 1){
			return ['', ''];
		}

		$desc = $this->decodeField($desc, $from_encoding);

		// for some reason there was no LF character (trimmed from the end while decoding),
		// we'll assume that whatever the string is as template name
		if(!str_contains($desc, "\n")){
			return [$desc, ''];
		}

		return explode("\n", $desc, 2);
	}

	/**
	 * Encodes the description field from the given template name and description
	 *
	 * @throws \InvalidArgumentException
	 */
	private function encodeDescription(string $templatename, string $description, string $to_encoding):string{
		// field is empty, always return a line break
		if($templatename.$description === ''){
			return 'Cg'; // "\n" in base64
		}

		$field = $this->encodeField($templatename."\n".$description, $to_encoding);

		// for some reason the field can be longer than 255, up to 2-3 bytes [citation needed]
		if(strlen($field) > 258){
			throw new InvalidArgumentException('template name and description combined cannot be longer than 255 bytes');
		}

		return $this->base64encode($field);
	}

	/**
	 * Decodes Attribute bonuses and flags
	 *
	 * @return array{0: int[], 1: bool[]}
	 * @phan-suppress PhanTypeMismatchReturn
	 */
	private function decodeFlags(string $flagsB64):array{

		if($flagsB64 === ''){
			// return an "empty" array
			return [
				array_fill(0, 5, 0),
				array_fill(0, count(self::FLAGS), false),
			];
		}

		$bonuses = [];
		$flag_b2 = '';

		// we're reading a maximum of 6 bytes (6 bits each, for a total of 36 - the flag length is 34 bits)
		// the first 3 bytes for attribute bonuses
		for($i = 0; $i < 6; $i++){
			// we're going to zero-fill here
			$ord = $this->base64_ord(($flagsB64[$i] ?? 'A'));

			if($i < 3){
				$bonuses[] = (($ord & 0b111000) >> 3);
				$bonuses[] = ($ord & 0b000111);
			}
			// the bytes for the consumable flags are read as big-endian, aka we need to flip them
			else{
				$flag_b2 .= $this->decbin_pad($ord, 6);
			}
		}

		// the last value is unused and always 0
		unset($bonuses[5]);

		$flag_val = str_split($flag_b2);
		$flags    = [];

		foreach(array_keys(self::FLAGS) as $flag){
			$flags[$flag] = boolval($flag_val[$flag]);
		}

		return [$bonuses, $flags];
	}

	/**
	 * @param int[]  $attributes
	 * @param bool[] $flags
	 */
	private function encodeFlags(array $attributes, array $flags):string{
		$attributes = array_values($attributes);
		$flag_sum   = array_sum($flags);

		if(array_sum($attributes) === 0 && $flag_sum === 0){
			return '';
		}

		// zero-fill possibly missing values
		while(count($attributes) < 6){ // phpcs:ignore
			$attributes[] = 0;
		}

		$b64 = '';

		// combine 2 times 3 bits each
		foreach(array_chunk($attributes, 2) as [$hi, $lo]){
			$b64 .= $this->base64_chr(($hi << 3) | $lo);
		}

		// we'll only write flags if any of them are set
		if($flag_sum > 0){
			$base2 = '';

			foreach(array_keys(self::FLAGS) as $flag){
				$base2 .= (int)(isset($flags[$flag]) && boolval($flags[$flag]) === true);
			}

			$bin  = $this->encodeBase2ToBinary($base2);
			$b64 .= $this->base64encode($bin);
		}

		// cut off unnecessary zero padding
		return rtrim($b64, 'A');
	}

	/**
	 * Converts the given string from the given encoding to UTF-8 (or internal encoding)
	 *
	 * @throws \RuntimeException
	 */
	private function decodeField(string $data, string $from_encoding):string{

		if($data === ''){
			return '';
		}

		if($from_encoding === self::ENCODING_UNDEFINED){
			$encodings     = [self::ENCODING_WINDOWS1252, self::ENCODING_UTF8, self::ENCODING_ASCII];
			$from_encoding = mb_detect_encoding($data, $encodings, true);

			if($from_encoding === false){
				throw new RuntimeException('cannot detect encoding of the given string'); // @codeCoverageIgnore
			}
		}

		$internal_encoding = mb_internal_encoding();

		// avoid double decoding
		if($from_encoding === $internal_encoding){
			return trim($data);
		}

		$data = mb_convert_encoding($data, $internal_encoding, $from_encoding);

		if($data === false){
			throw new RuntimeException(sprintf('error converting from %s', $from_encoding)); // @codeCoverageIgnore
		}

		return trim($data);
	}

	/**
	 * Converts the given string to the given encoding from UTF-8 (or internal encoding)
	 *
	 * @throws \RuntimeException
	 */
	private function encodeField(string $data, string $to_encoding):string{

		if($data === ''){
			return '';
		}

		if($to_encoding === self::ENCODING_UNDEFINED){
			$to_encoding = self::ENCODING_ASCII; // not sure on that one, we'll run with it for now
		}

		$internal_encoding = mb_internal_encoding();

		// avoid double encoding
		if($to_encoding === $internal_encoding){
			return $data;
		}

		$data = mb_convert_encoding($data, $to_encoding, $internal_encoding);

		if($data === false){
			throw new RuntimeException(sprintf('error converting to %s', $to_encoding)); // @codeCoverageIgnore
		}

		return $data;
	}

	/**
	 * Returns the ordinal for the given base64 character
	 *
	 * @throws \InvalidArgumentException
	 */
	private function base64_ord(string $chr):int{
		/** @phan-suppress-next-line PhanParamSuspiciousOrder */
		$ord = strpos(self::BASE64, $chr);

		if($ord === false){
			throw new InvalidArgumentException(sprintf('invalid character given: "%s"', $chr)); // @codeCoverageIgnore
		}

		return $ord;
	}

	/**
	 * Returns the base64 character for the given ordinal
	 *
	 * @throws \InvalidArgumentException
	 */
	private function base64_chr(int $ord):string{

		if(!isset(self::BASE64[$ord])){
			throw new InvalidArgumentException(sprintf('invalid ordinal given: "%s"', $ord)); // @codeCoverageIgnore
		}

		return self::BASE64[$ord];
	}

	/**
	 * Reads a base64 encoded field from the template string
	 */
	private function readString(bool $isVariableField = false):string{
		$length = $this->read(1);

		if($isVariableField){
			$length *= 64;
			$length += $this->read(1);
		}

		$str           = substr($this->string, $this->offset, $length);
		$this->offset += $length;

		return $str;
	}

	/**
	 * Writes a base64 encoded field to the template string
	 */
	private function writeString(string $str, bool $isVariableField = false):string{
		$length = strlen($str);

		if($isVariableField){
			$len  = $this->base64_chr(intdiv($length, 64));
			$len .= $this->base64_chr($length % 64);

			return $len.$str;
		}

		return $this->base64_chr($length).$str;
	}

	/**
	 * Encodes the header flag string
	 */
	private function writeHeaderFlags():string{
		// not much to do here yet
		$headerFlags = [0, 0, 0, $this->encodingFlag];

		return implode('', $headerFlags);
	}

	/**
	 * Reads a length byte from the template string
	 *
	 * @see \BuildWars\GWTemplates\PwndTemplate::readString()
	 */
	protected function read(int $length):int{
		$dec           = $this->base64_ord(substr($this->string, $this->offset, $length));
		$this->offset += $length;

		return $dec;
	}

	/**
	 * Standard base64-decode to avoid some issues with unpadded strings (Sodium is a bit too strict here)
	 */
	protected function base64decode(string $base64):string{
		$base64 = $this->checkCharacterSet($base64);

		return base64_decode($base64, true);
	}

}
