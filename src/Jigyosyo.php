<?php
namespace Ninton\JpostalJson;

/**
 * Class Jigyosyo
 * @package Ninton\JpostalJson
 */
class Jigyosyo {
	public $index;
	public $city_code;
	public $name_kana;
	public $name;
	public $pref;
	public $city;
	public $town;
	public $address;
	public $postcode;
	public $postcode5;
	public $dealer_name;
	public $flag_1;
	public $flag_2;
	public $flag_3;

	/**
	 * Jigyosyo constructor.
	 * @param int $index
	 * @param array $arr KEN_ALL_UTF8.CSVの1行
	 */
	public function __construct(array $arr, $index)
	{
		$this->index = $index;
		$this->city_code = $arr[0];
		$this->name_kana = $arr[1];
		$this->name = $arr[2];
		$this->pref = $arr[3];
		$this->city = $arr[4];
		$this->town = $arr[5];
		$this->address = $arr[6];
		$this->postcode = $arr[7];
		$this->postcode5 = $arr[8];
		$this->dealer_name = $arr[9];
		$this->flag_1 = $arr[10];
		$this->flag_2 = $arr[11];
		$this->flag_3 = $arr[12];
	}

	/**
	 * Jpsotalクラスのインスタンスを作成する
	 * @return Jpostal
	 */
	function createJpostal()
	{
		$jpostal = new Jpostal();
		$jpostal->type = 1;
		$jpostal->index = $this->index;
		$jpostal->postcode3 = substr($this->postcode, 0, 3);
		$jpostal->postcode = $this->postcode;
		$jpostal->pref = $this->pref;
		$jpostal->city = $this->city;
		$jpostal->town = $this->town;
		$jpostal->address = $this->address;
		$jpostal->jigyosyo_name = $this->name;
		$jpostal->jigyosyo_name_kana = $this->name_kana;

		return $jpostal;
	}
}

