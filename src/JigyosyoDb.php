<?php
namespace Ninton\JpostalJson;

/**
 * Class JigyosyoDb
 * @package Ninton\JpostalJson
 */
class JigyosyoDb
{
	/**
	 * @var array
	 */
	private $db = [];

	/**
	 * JigyosyoDb constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * JIGYOSYO_UTF8.CSVを読み込む
	 * @param string $path
	 */
	public function load(string $path)
	{
		$fp = fopen($path, 'r');
		while ($arr = fgetcsv($fp)) {
			$jigyosyo = new Jigyosyo($arr);
			$this->add($jigyosyo);
		}
		fclose($fp);
	}

	/**
	 * 内部DBに追加する
	 * @param Jigyosyo $jigyosyo
	 */
	public function add(Jigyosyo $jigyosyo)
	{
		if (!isset($this->db[$jigyosyo->postcode])) {
			$this->db[$jigyosyo->postcode] = [];
		}
		$this->db[$jigyosyo->postcode][] = $jigyosyo;
	}

	/**
	 * @return \Generator
	 */
	public function getJigyosyos()
	{
		foreach ($this->db as $postcode => $jigyosyo_arr) {
			foreach ($jigyosyo_arr as $jigyosyo) {
				yield $jigyosyo;
			}
		}
	}
}