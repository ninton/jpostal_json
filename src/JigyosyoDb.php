<?php
namespace Ninton\JpostalJson;

class JigyosyoDb
{
	private $db = [];

	public function __construct()
	{
	}

	public function load(string $path)
	{
		$fp = fopen($path, 'r');
		while ($arr = fgetcsv($fp)) {
			$jigyosyo = new Jigyosyo($arr);
			$this->add($jigyosyo);
		}
		fclose($fp);
	}

	public function add(Jigyosyo $jigyosyo)
	{
		if (!isset($this->db[$jigyosyo->postcode])) {
			$this->db[$jigyosyo->postcode] = [];
		}
		$this->db[$jigyosyo->postcode][] = $jigyosyo;
	}

	public function getJigyosyos()
	{
		foreach ($this->db as $postcode => $jigyosyo_arr) {
			foreach ($jigyosyo_arr as $jigyosyo) {
				yield $jigyosyo;
			}
		}
	}
}