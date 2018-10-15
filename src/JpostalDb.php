<?php
/**
 * Created by PhpStorm.
 * User: aoki
 * Date: 18/10/15
 * Time: 0:24
 */
namespace Ninton\JpostalJson;

class JpostalDb
{
	private $config = [];
	private $db = [];
	private $pref_kana_dict = [];
	private $city_kana_dict = [];
	private $town_kana_dict = [];

	public function __construct(array $config)
	{
		$this->config = $config;

		for ($i = 0; $i <= 999; $i += 1) {
			$postcode3 = sprintf('%03d', $i);
			$this->db[$postcode3] = [];
		}

		$info = $this->create000info();
		$this->add($info);
	}

	private function create000info()
	{
		$version = file_get_contents($this->config['version_txt']);
		$fdate = strftime('%Y-%m-%d', filemtime($this->config['ken_all_csv']));
		$today = strftime('%Y-%m-%d', time());

		$jpostal = new Jpostal();
		$jpostal->postcode = '000info';
		$jpostal->city = "$version $fdate $today";
		$jpostal->city_kana = "$version $fdate $today";

		return $jpostal;
	}

	public function add(Jpostal $jpostal)
	{
		$postcode3 = substr($jpostal->postcode, 0, 3);
		$this->db[$postcode3][] = $jpostal;
	}

	public function trimTown()
	{
		foreach ($this->db as $postcode3 => $jpostal_arr) {
			$out_arr = array_map(function ($jpostal) {
				$jpostal->trimTown();
				$jpostal->trimTownKana();

				return $jpostal;
			}, $jpostal_arr);

			$this->db[$postcode3] = $out_arr;
		}
	}

	public function save(string $json_dir)
	{
		foreach ($this->db as $postcode3 => $jpostal_arr) {
			$path = "$json_dir/$postcode3.json";

			$out_arr = array_map(function ($jpostal) {
				return $jpostal->toArray();
			}, $jpostal_arr);

			$json = json_encode($out_arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			file_put_contents($path, $json);
		}
	}

	public function createPostcode3()
	{
		// 3桁の住所データを作る
		foreach ($this->db as $postcode3 => $jpostal_arr) {
			if (count($jpostal_arr) == 0) {
				continue;
			}

			list($pref, $city) = $this->mostPopularPrefCity($jpostal_arr);
			$jpostal = new Jpostal();
			$jpostal->postcode = $postcode3;
			$jpostal->pref = $pref;
			$jpostal->city = $city;
			$jpostal->pref_kana = $this->searchPrefKana($pref);
			$jpostal->city_kana = $this->searchPrefKana($city);

			$this->add($jpostal);
		}
	}

	private function mostPopularPrefCity(array $jpostal_arr)
	{
		$hist = [];

		foreach ($jpostal_arr as $jpsotal) {
			$key = $jpsotal->pref . '|' . $jpsotal->city;
			if (!isset($hist[$key])) {
				$hist[$key] = 0;
			}
			$hist[$key] += 1;
		}

		arsort( $hist, SORT_NUMERIC );
		$keys = array_keys($hist);
		$key = array_shift($keys);
		list($pref, $city) = explode('|', $key);
		return [$pref, $city];
	}

	public function fillBlankKana()
	{
		foreach ($this->db as $postcode3 => $jpostal_arr) {
			foreach ($jpostal_arr as $jpostal) {
				$this->addDict($jpostal->pref, $jpostal->pref_kana, $jpostal->city, $jpostal->city_kana, $jpostal->town, $jpostal->town_kana);
			}
		}

		foreach ($this->db as $postcode3 => $jpostal_arr) {
			foreach ($jpostal_arr as $i => $jpostal) {
				if ($jpostal->pref_kana === '') {
					$jpostal->pref_kana = $this->searchPrefKana($jpostal->pref);
				}
				if ($jpostal->city !== '' && $jpostal->city_kana === '') {
					$jpostal->city_kana = $this->searchCityKana($jpostal->pref, $jpostal->city);
				}

				if ($jpostal->town !== '' && $jpostal->town_kana === '') {
					$jpostal->town_kana = $this->searchTownKana($jpostal->pref, $jpostal->city, $jpostal->town);
				}

				$this->db[$postcode3][$i] = $jpostal;
			}
		}
	}

	private function addDict(string $pref, string $pref_kana, string $city, string $city_kana, string $town, string $town_kana)
	{
		if ($pref !== '' && $pref_kana !== '') {
			$this->pref_kana_dict[$pref] = $pref_kana;
		}

		if ($city !== '' && $city_kana !== '') {
			$this->city_kana_dict[$pref][$city] = $city_kana;
		}

		if ($town !== '' && $town_kana !== '') {
			$this->town_kana_dict[$pref][$city][$town] = $town_kana;
		}
	}

	private function searchPrefKana(string $pref)
	{
		if (isset($this->pref_kana_dict[$pref])) {
			return $this->pref_kana_dict[$pref];
		}

		return '';
	}

	private function searchCityKana(string $pref, string $city)
	{
		if (isset($this->city_kana_dict[$pref][$city])) {
			return $this->city_kana_dict[$pref][$city];
		}

		return '';
	}

	private function searchTownKana(string $pref, string $city, string $town)
	{
		if (isset($this->town_kana_dict[$pref][$city][$town])) {
			return $this->town_kana_dict[$pref][$city][$town];
		}

		return '';
	}

	public function convertJsonp()
	{
		// jsonを読み込んで、関数名jQuery_jpostal_callback(でjsonp化して保存する
		// すでにjsonp化されていたら、何もしない

		for ($i = 0; $i <= 999; $i += 1) {
			$postcode3 = sprintf('%03d', $i);
			$path = $this->config['json_dir'] . '/' . $postcode3 . '.json';
			$data = file_get_contents($path);

			if (preg_match('/^jQuery_jpostal_callback\(/', $data)) {
				continue;
			}

			$jsonp_data = <<<END
jQuery_jpostal_callback(
$data
);
END;
			file_put_contents($path, $jsonp_data);
		}
	}
}