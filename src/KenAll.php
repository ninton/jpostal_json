<?php
namespace Ninton\JpostalJson;

/**
 * Class KenAll
 * @package Ninton\JpostalJson
 */
class KenAll {
	public $index;
	public $city_code;
	public $postcode5;
	public $postcode;
	public $pref_kana;
	public $city_kana;
	public $town_kana;
	public $pref;
	public $city;
	public $town;
	public $flag_1;
	public $flag_2;
	public $flag_3;
	public $flag_4;
	public $flag_5;
	public $flag_6;

	/**
	 * KenAll constructor.
	 * @param int $index
	 * @param array $arr
	 */
	public function __construct(array $arr, $index)
	{
		$this->index = $index;
		$this->city_code = $arr[0];
		$this->postcode5 = $arr[1];
		$this->postcode = $arr[2];
		$this->pref_kana = $arr[3];
		$this->city_kana = $arr[4];
		$this->town_kana = $arr[5];
		$this->pref = $arr[6];
		$this->city = $arr[7];
		$this->town = $arr[8];
		$this->flag_1 = $arr[9];
		$this->flag_2 = $arr[10];
		$this->flag_3 = $arr[11];
		$this->flag_4 = $arr[12];
		$this->flag_5 = $arr[13];
		$this->flag_6 = $arr[14];
	}

	/**
	 * Jpsotalクラスのインスタンスを作成する
	 * @return Jpostal
	 */
	function createJpostal()
	{
		$jpostal = new Jpostal();
		$jpostal->type = 0;
		$jpostal->index = $this->index;
		$jpostal->postcode3 = substr($this->postcode, 0, 3);
		$jpostal->postcode = $this->postcode;
		$jpostal->pref = $this->pref;
		$jpostal->city = $this->city;
		$jpostal->town = $this->town;
		$jpostal->pref_kana = $this->pref_kana;
		$jpostal->city_kana = $this->city_kana;
		$jpostal->town_kana = $this->town_kana;

		return $jpostal;
	}
}
