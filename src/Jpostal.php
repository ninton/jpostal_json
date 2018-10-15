<?php
namespace Ninton\JpostalJson;

/**
 * Class Jpostal
 * @package Ninton\JpostalJson
 */
class Jpostal {
	public $postcode = '';
	public $pref = '';
	public $city = '';
	public $town = '';
	public $address = '';
	public $jigyosyo_name = '';
	public $pref_kana = '';
	public $city_kana = '';
	public $town_kana = '';
	public $jigyosyo_name_kana = '';

	/**
	 * Jpostal constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * テスト用
	 * @return string
	 */
	public function __toString()
	{
		return $this->postcode
			. $this->pref
			. $this->city
			. $this->town
			. $this->address
			. $this->jigyosyo_name
			. $this->pref_kana
			. $this->city_kana
			. $this->town_kana
			. $this->jigyosyo_name;
	}

	/**
	 * json保存用の配列に変換する
	 * @return array
	 */
	public function toArray()
	{
		return [
			'_' . $this->postcode,
			$this->pref,
			$this->city,
			$this->town,
			$this->address,
			$this->jigyosyo_name,
			$this->pref_kana,
			$this->city_kana,
			$this->town_kana,
		];
	}

	/**
	 * 町域の不要テキストを削除する
	 */
	public function trimTown()
	{
		if ($this->town === '以下に掲載がない場合') {
			$this->town = '';

		} else if (preg_match('/の次に番地がくる場合/', $this->town)) {
			$this->town = '';

		} else if ($this->town === '一円') {
			; // noop

		} else if (preg_match('/一円/', $this->town)) {
			$this->town = '';

		} else if (preg_match('/^甲、乙/', $this->town)) {
			$this->town = '';

		} else if (preg_match('/（((０|１|２|３|４|５|６|７|８|９)+階)）$/', $this->town)) {
			$this->town = preg_replace('/（((０|１|２|３|４|５|６|７|８|９)+階)）$/', '$1', $this->town);

		} else if (preg_match('/（.+）$/', $this->town)) {
			$this->town = preg_replace('/（.+）$/', '', $this->town);

		} else if (preg_match('/第(０|１|２|３|４|５|６|７|８|９)+地割.*/', $this->town)) {
			$this->town = preg_replace('/第(０|１|２|３|４|５|６|７|８|９)+地割.*$/', '', $this->town);

		} else if (preg_match('/(０|１|２|３|４|５|６|７|８|９)+地割.*/', $this->town)) {
			$this->town = preg_replace('/(０|１|２|３|４|５|６|７|８|９)+地割.*$/', '', $this->town);
		}

		if (preg_match('/(～|、)/', $this->town)) {
			$this->town = '';
			$this->town_kana = '';
		}
	}

	/**
	 * 町域カナの不要テキストを削除する
	 */
	public function trimTownKana()
	{
		if ($this->town_kana === 'ｲｶﾆｹｲｻｲｶﾞﾅｲﾊﾞｱｲ') {
			$this->town_kana = '';

		} else if (preg_match('/ﾂｷﾞﾆﾊﾞﾝﾁｶﾞｸﾙﾊﾞｱｲ/', $this->town_kana)) {
			$this->town_kana = '';

		} else if ($this->town_kana === 'ｲﾁｴﾝ') {
			; // noop

		} else if (preg_match('/ｲﾁｴﾝ/', $this->town_kana)) {
			$this->town_kana = '';

		} else if (preg_match('/^ｺｳ､ｵﾂ/', $this->town_kana)) {
			$this->town_kana = '';

		} else if (preg_match('/\(([0-9]+ｶｲ)\)$/', $this->town_kana)) {
			$this->town_kana = preg_replace('/\(([0-9]+ｶｲ)\)$/', '$1', $this->town_kana);

		} else if (preg_match('/\(.+\)$/', $this->town_kana)) {
			$this->town_kana = preg_replace('/\(.+\)$/', '', $this->town_kana);

		} else if (preg_match('/ﾀﾞｲ[0-9]+ﾁﾜﾘ.*$/', $this->town_kana)) {
			$this->town_kana = preg_replace('/ﾀﾞｲ[0-9]+ﾁﾜﾘ.*$/', '', $this->town_kana);

		} else if (preg_match('/[0-9]+ﾁﾜﾘ.*$/', $this->town_kana)) {
			$this->town_kana = preg_replace('/[0-9]+ﾁﾜﾘ.*$/', '', $this->town_kana);
		}
	}
}

