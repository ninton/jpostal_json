<?php
namespace Ninton\JpostalJson;

class KenAllDb
{
	private $db = [];

	public function __construct()
	{
	}

	public function load(string $path)
	{
		$fp = fopen($path, 'r');
		while ($arr = fgetcsv($fp)) {
			$kenAll = new KenAll($arr);
			$this->add($kenAll);
		}
		fclose($fp);
	}

	public function add(KenAll $kenAll)
	{
		if (!isset($this->db[$kenAll->postcode])) {
			$this->db[$kenAll->postcode] = [];
		}
		$this->db[$kenAll->postcode][] = $kenAll;
	}

	public function getKenAlls()
	{
		foreach ($this->db as $postcode => $ken_all_arr) {
			foreach ($ken_all_arr as $kenAll) {
				yield $kenAll;
			}
		}
	}

	public function joinMultiLines()
	{
		// townが複数行に分割された行を、1行にする
		foreach ($this->db as $postcode => $ken_all_arr) {
			$this->db[$postcode] = $this->joinMultiLinesBody($ken_all_arr);
		}
	}

	private function joinMultiLinesBody(array $ken_all_arr)
	{
		$out_arr = [];

		while ($ken_all = array_shift($ken_all_arr)) {
			$f1 = preg_match('/（/', $ken_all->town);
			$f2 = preg_match('/）/', $ken_all->town);

			if ($f1 && !$f2) {
				while ($next = array_shift($ken_all_arr)) {
					$ken_all->town .= $next->town;
					if ($ken_all->town_kana !== $next->town_kana) {
						$ken_all->town_kana .= $next->town_kana;
					}

					$f2 = preg_match('/）/', $next->town);
					if ( $f2 ) {
						break;
					}
				}
			}

			$out_arr[] = $ken_all;
		}

		return $out_arr;
	}
}