<?php
namespace Ninton\JpostalJson;

/**
 * Class Main
 * @package Ninton\JpostalJson
 */
class Main
{
	private $config;

	/**
	 * Main constructor.
	 * @param array $config
	 */
	public function __construct(array $config = [])
	{
		$this->config = $config;
	}

	/**
	 * メイン処理
	 */
	public function run()
	{
		$this->jpostalDb = new JpostalDb($this->config);

		$this->importKenAll();
		$this->jpostalDb->trimTown();
		$this->jpostalDb->createPostcode3();

		$this->importJigyosyo();
		$this->jpostalDb->fillBlankKana();

		$this->jpostalDb->save($this->config['json_dir']);
	}

	/**
	 * json/*.jsonをjsonpに変換する
	 */
	public function jsonp()
	{
		$this->jpostalDb->convertJsonp();
	}

	private function importKenAll()
	{
		$this->kenAllDb = new KenAllDb();
		$this->kenAllDb->load($this->config['ken_all_utf8_csv']);
		$this->kenAllDb->joinMultiLines();

		foreach ($this->kenAllDb->getKenAlls() as $kenAll) {
			$jpostal = $kenAll->createJpostal();
			$this->jpostalDb->add($jpostal);
		}
	}

	private function importJigyosyo()
	{
		$this->jigyosyoDb = new JigyosyoDb();
		$this->jigyosyoDb->load($this->config['jigyosyo_utf8_csv']);
		foreach ($this->jigyosyoDb->getJigyosyos() as $jigyosyo) {
			$jpostal = $jigyosyo->createJpostal();
			$this->jpostalDb->add($jpostal);
		}
	}
}