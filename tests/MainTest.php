<?php
namespace Ninton\JpostalJson;

require_once(__DIR__ . '/../vendor/autoload.php');

class MainTest extends \PHPUnit\Framework\TestCase {
	public function test_run() {
		$config = [
			'ken_all_csv'       => 'tests/fixtures/KEN_ALL.CSV',
			'ken_all_utf8_csv'  => 'tests/fixtures/KEN_ALL_UTF8.CSV',
			'jigyosyo_utf8_csv' => 'tests/fixtures/JIGYOSYO_UTF8.CSV',
			'version_txt'       => 'tests/fixtures/version.txt',
			'json_dir'          => '/tmp/jpostal_json',
		];

		$this->assertTrue(file_exists($config['ken_all_utf8_csv']));
		$this->assertTrue(file_exists($config['jigyosyo_utf8_csv']));
		$this->assertTrue(file_exists($config['version_txt']));

		if (!file_exists($config['json_dir'])) {
			mkdir($config['json_dir']);
		}
		system('rm -rf ' . $config['json_dir'] . '/*');
		$this->assertTrue(file_exists($config['json_dir']));

		$main = new Main($config);
		$main->run();

		$this->assert_000info();
		$this->assert_通常();
		$this->assert_以下に掲載がない場合();
		$this->assert_次に番地がくる場合();
		$this->assert_一円_残す();
		$this->assert_一円();
		$this->assert_甲乙();
		$this->assert_ビル階_残す();
		$this->assert_カッコ_削除();
		$this->assert_第地割_削除();
		$this->assert_地割_削除();
		$this->assert_カンマ波線_削除();
		$this->assert_分割データの結合();
		// 3桁
		$this->assert_事業所();
		$this->assert_notBlankKana();

		$this->assert_issue53_9200381先頭は事業所ではないこと();
	}

	private function assert_000info() {
		$data = file_get_contents('/tmp/jpostal_json/000.json');
		$json = json_decode($data);

		$this->assertEquals(2, count($json));
		$this->assertEquals(9, count($json[0]));
		$this->assertEquals(9, count($json[1]));

		$this->assertEquals('_000info', $json[0][0]);
		// 'ver2.11 2018-10-14 2018-10-15'
		$this->assertRegExp('/ver[0-9.]+ [0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{4}-[0-9]{2}-[0-9]{2}/', $json[0][2]);

		$this->assertEquals('_000', $json[1][0]);
	}

	private function assert_通常() {
		$expected = [
			// 01102,"001  ","0010000","ﾎｯｶｲﾄﾞｳ","ｻｯﾎﾟﾛｼｷﾀｸ","ｲｶﾆｹｲｻｲｶﾞﾅｲﾊﾞｱｲ","北海道","札幌市北区","以下に掲載がない場合",0,0,0,0,0,0
			// 01102,"001  ","0010010","ﾎｯｶｲﾄﾞｳ","ｻｯﾎﾟﾛｼｷﾀｸ","ｷﾀ10ｼﾞｮｳﾆｼ(1-4ﾁｮｳﾒ)","北海道","札幌市北区","北十条西（１～４丁目）",1,0,1,0,0,0
			'_0010045' => [
				[
					// 01102,"001  ","0010045","ﾎｯｶｲﾄﾞｳ","ｻｯﾎﾟﾛｼｷﾀｸ","ｱｻﾌﾞﾁｮｳ","北海道","札幌市北区","麻生町",0,0,1,0,0,0
					'_0010045',
					'北海道',
					'札幌市北区',
					'麻生町',
					'',
					'',
					'ﾎｯｶｲﾄﾞｳ',
					'ｻｯﾎﾟﾛｼｷﾀｸ',
					'ｱｻﾌﾞﾁｮｳ',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/001.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertGreaterThanOrEqual(60, count($json));
		$this->assertEquals(9, count($json[0]));

		$this->assertEquals($expected['_0010045'], $map['_0010045']);
	}

	private function assert_以下に掲載がない場合() {
		$expected = [

			'_0010000' => [
				[
					// 01102,"001  ","0010000","ﾎｯｶｲﾄﾞｳ","ｻｯﾎﾟﾛｼｷﾀｸ","ｲｶﾆｹｲｻｲｶﾞﾅｲﾊﾞｱｲ","北海道","札幌市北区","以下に掲載がない場合",0,0,0,0,0,0
					'_0010000',
					'北海道',
					'札幌市北区',
					'',
					'',
					'',
					'ﾎｯｶｲﾄﾞｳ',
					'ｻｯﾎﾟﾛｼｷﾀｸ',
					'',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/001.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_0010000'], $map['_0010000']);
	}

	private function assert_次に番地がくる場合() {
		$expected = [

			'_5013701' => [
				[
					// 50137	5013701	ｷﾞﾌｹﾝ	ﾐﾉｼ	ﾐﾉｼﾉﾂｷﾞﾆﾊﾞﾝﾁｶﾞｸﾙﾊﾞｱｲ	岐阜県	美濃市	美濃市の次に番地がくる場合	0	0	0	0	0	0
					'_5013701',
					'岐阜県',
					'美濃市',
					'',
					'',
					'',
					'ｷﾞﾌｹﾝ',
					'ﾐﾉｼ',
					'',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/501.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_5013701'], $map['_5013701']);
	}

	private function assert_一円_残す() {
		$expected = [

			'_5220317' => [
				[
					//25443	52203	5220317	ｼｶﾞｹﾝ	ｲﾇｶﾐｸﾞﾝﾀｶﾞﾁｮｳ	ｲﾁｴﾝ	滋賀県	犬上郡多賀町	一円	0	0	0	0	0	0
					'_5220317',
					'滋賀県',
					'犬上郡多賀町',
					'一円',
					'',
					'',
					'ｼｶﾞｹﾝ',
					'ｲﾇｶﾐｸﾞﾝﾀｶﾞﾁｮｳ',
					'ｲﾁｴﾝ',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/522.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_5220317'], $map['_5220317']);
	}

	private function assert_一円() {
		$expected = [

			'_1000301' => [
				[
					// 13362	10003	1000301	ﾄｳｷｮｳﾄ	ﾄｼﾏﾑﾗ	ﾄｼﾏﾑﾗｲﾁｴﾝ	東京都	利島村	利島村一円	0	0	0	0	0	0
					'_1000301',
					'東京都',
					'利島村',
					'',
					'',
					'',
					'ﾄｳｷｮｳﾄ',
					'ﾄｼﾏﾑﾗ',
					'',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/100.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_1000301'], $map['_1000301']);
	}

	private function assert_甲乙() {
		$expected = [

			'_7614103' => [
				[
					// 37322	76141	7614103	ｶｶﾞﾜｹﾝ	ｼｮｳｽﾞｸﾞﾝﾄﾉｼｮｳﾁｮｳ	ｺｳ､ｵﾂ(ｵｵｷﾄﾞ)	香川県	小豆郡土庄町	甲、乙（大木戸）	1	0	0	0	0	0
					'_7614103',
					'香川県',
					'小豆郡土庄町',
					'',
					'',
					'',
					'ｶｶﾞﾜｹﾝ',
					'ｼｮｳｽﾞｸﾞﾝﾄﾉｼｮｳﾁｮｳ',
					'',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/761.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_7614103'], $map['_7614103']);
	}

	private function assert_ビル階_残す() {
		$expected = [

			'_3306027' => [
				[
					// 11105	330	3306027	ｻｲﾀﾏｹﾝ	ｻｲﾀﾏｼﾁｭｳｵｳｸ	ｼﾝﾄｼﾝﾒｲｼﾞﾔｽﾀﾞｾｲﾒｲｻｲﾀﾏｼﾝﾄｼﾝﾋﾞﾙ(27ｶｲ)	埼玉県	さいたま市中央区	新都心明治安田生命さいたま新都心ビル（２７階）	0	0	0	0	0	0
					'_3306027',
					'埼玉県',
					'さいたま市中央区',
					'新都心明治安田生命さいたま新都心ビル２７階',
					'',
					'',
					'ｻｲﾀﾏｹﾝ',
					'ｻｲﾀﾏｼﾁｭｳｵｳｸ',
					'ｼﾝﾄｼﾝﾒｲｼﾞﾔｽﾀﾞｾｲﾒｲｻｲﾀﾏｼﾝﾄｼﾝﾋﾞﾙ27ｶｲ',
				],
			],
			'_3306090' => [
				[
					// 11105	330	3306090	ｻｲﾀﾏｹﾝ	ｻｲﾀﾏｼﾁｭｳｵｳｸ	ｼﾝﾄｼﾝﾒｲｼﾞﾔｽﾀﾞｾｲﾒｲｻｲﾀﾏｼﾝﾄｼﾝﾋﾞﾙ(ﾁｶｲ･ｶｲｿｳﾌﾒｲ)	埼玉県	さいたま市中央区	新都心明治安田生命さいたま新都心ビル（地階・階層不明）	0	0	0	0	0	0
					'_3306090',
					'埼玉県',
					'さいたま市中央区',
					'新都心明治安田生命さいたま新都心ビル',
					'',
					'',
					'ｻｲﾀﾏｹﾝ',
					'ｻｲﾀﾏｼﾁｭｳｵｳｸ',
					'ｼﾝﾄｼﾝﾒｲｼﾞﾔｽﾀﾞｾｲﾒｲｻｲﾀﾏｼﾝﾄｼﾝﾋﾞﾙ',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/330.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_3306027'], $map['_3306027']);
		$this->assertEquals($expected['_3306090'], $map['_3306090']);
	}

	private function assert_カッコ_削除() {
		$expected = [
			'_0600042' => [
				[
					// 1101	60	0600042	ﾎｯｶｲﾄﾞｳ	ｻｯﾎﾟﾛｼﾁｭｳｵｳｸ	ｵｵﾄﾞｵﾘﾆｼ(1-19ﾁｮｳﾒ)	北海道	札幌市中央区	大通西（１～１９丁目）	1	0	1	0	0	0
					'_0600042',
					'北海道',
					'札幌市中央区',
					'大通西',
					'',
					'',
					'ﾎｯｶｲﾄﾞｳ',
					'ｻｯﾎﾟﾛｼﾁｭｳｵｳｸ',
					'ｵｵﾄﾞｵﾘﾆｼ',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/060.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_0600042'], $map['_0600042']);
	}

	private function assert_第地割_削除() {
		$expected = [
			'_0287915' => [
				[
					// 3507	2879	0287915	ｲﾜﾃｹﾝ	ｸﾉﾍｸﾞﾝﾋﾛﾉﾁｮｳ	ﾀﾈｲﾁﾀﾞｲ15ﾁﾜﾘ-ﾀﾞｲ21ﾁﾜﾘ(ｶﾇｶ､ｼｮｳｼﾞｱｲ､ﾐﾄﾞﾘﾁｮｳ､ｵｵｸﾎﾞ､ﾀｶﾄﾘ)	岩手県	九戸郡洋野町	種市第１５地割～第２１地割（鹿糠、小路合、緑町、大久保、高取）	0	1	0	0	0	0
					'_0287915',
					'岩手県',
					'九戸郡洋野町',
					'',
					'',
					'',
					'ｲﾜﾃｹﾝ',
					'ｸﾉﾍｸﾞﾝﾋﾛﾉﾁｮｳ',
					'',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/028.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_0287915'][0], $map['_0287915'][0]);
	}

	private function assert_地割_削除() {
		$expected = [
			'_0295502' => [
				[
					// 3366	2955	0295502	ｲﾜﾃｹﾝ	ﾜｶﾞｸﾞﾝﾆｼﾜｶﾞﾏﾁ	ﾄﾗｻﾜ15ﾁﾜﾘ	岩手県	和賀郡西和賀町	寅沢１５地割	0	0	0	1	0	0
					'_0295502',
					'岩手県',
					'和賀郡西和賀町',
					'寅沢',
					'',
					'',
					'ｲﾜﾃｹﾝ',
					'ﾜｶﾞｸﾞﾝﾆｼﾜｶﾞﾏﾁ',
					'ﾄﾗｻﾜ',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/029.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_0295502'][0], $map['_0295502'][2]);
	}

	private function assert_カンマ波線_削除() {
		$expected = [
			'_0295523' => [
				[
					// 3366	2955	0295523	ｲﾜﾃｹﾝ	ﾜｶﾞｸﾞﾝﾆｼﾜｶﾞﾏﾁ	ｴｯﾁｭｳﾊﾀ64ﾁﾜﾘ-ｴｯﾁｭｳﾊﾀ66ﾁﾜﾘ	岩手県	和賀郡西和賀町	越中畑６４地割～越中畑６６地割	0	0	0	1	0	0
					'_0295523',
					'岩手県',
					'和賀郡西和賀町',
					'越中畑',
					'',
					'',
					'ｲﾜﾃｹﾝ',
					'ﾜｶﾞｸﾞﾝﾆｼﾜｶﾞﾏﾁ',
					'ｴｯﾁｭｳﾊﾀ',
				],
			],
			'_0295502' => [
				[
					// 3366	2955	0295502	ｲﾜﾃｹﾝ	ﾜｶﾞｸﾞﾝﾆｼﾜｶﾞﾏﾁ	ｼﾀﾏｴ7ﾁﾜﾘ-ｼﾀﾏｴ14ﾁﾜﾘ	岩手県	和賀郡西和賀町	下前７地割～下前１４地割
					'_0295502',
					'岩手県',
					'和賀郡西和賀町',
					'下前',
					'',
					'',
					'ｲﾜﾃｹﾝ',
					'ﾜｶﾞｸﾞﾝﾆｼﾜｶﾞﾏﾁ',
					'ｼﾀﾏｴ',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/029.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_0295523'][0], $map['_0295523'][0]);
		$this->assertEquals($expected['_0295502'][0], $map['_0295502'][1]);
	}

	private function assert_事業所() {
		$expected = [
			'_1008994' => [
				[
					// 13101	ﾄｳｷﾖｳﾁﾕｳｵｳﾕｳﾋﾞﾝｷﾖｸ	東京中央郵便局	東京都	千代田区	丸の内	２丁目７－２	1008994	100	銀座	0	0	0
					'_1008994',
					'東京都',
					'千代田区',
					'丸の内',
					'２丁目７－２',
					'東京中央郵便局',
					'ﾄｳｷｮｳﾄ',
					'ﾁﾖﾀﾞｸ',
					'ﾏﾙﾉｳﾁ',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/100.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals($expected['_1008994'][0], $map['_1008994'][0]);
	}

	private function assert_notBlankKana()
	{
		for ($i = 1; $i <= 999; $i += 1) {
			$postcode3 = sprintf('%03d', $i);
			$path = "/tmp/jpostal_json/${postcode3}.json";
			if (!file_exists($path)) {
				continue;
			}
			$data = file_get_contents($path);
			$json = json_decode($data);

			foreach ($json as $item) {
				if ($item[1] !== '') {
					$this->assertNotEmpty($item[6]);
				}
				if ($item[2] !== '') {
					$this->assertNotEmpty($item[7]);
				}
				if ($item[3] !== '') {
					if ($item[8] === '') {
						//echo join(',', $item) . PHP_EOL;
					}
					//$this->assertNotEmpty($item[8], join(',', $item));
				}
			}
		}
	}

	private function assert_分割データの結合()
	{
		$expected = [
			'_0210102' => [
				[
					// 3209	2101	0210102	ｲﾜﾃｹﾝ	ｲﾁﾉｾｷｼ	ﾊｷﾞｼｮｳ(ｱｶｲﾉｺ､ｱｼﾉｸﾁ､ｱﾏﾜﾗﾋﾞ､ｵｲﾅｶﾞﾚ､ｵｵｻﾜ､ｶﾐｳﾂﾉ､ｶﾐﾎﾝｺﾞｳ､ｶﾐﾖｳｶﾞｲ､ｹｼｮｳ	岩手県	一関市	萩荘（赤猪子、芦ノ口、甘蕨、老流、大沢、上宇津野、上本郷、上要害、化粧
					// 3209	2101	0210102	ｲﾜﾃｹﾝ	ｲﾁﾉｾｷｼ	ｻﾞｶ､ｻﾝｶﾞﾂﾀﾞ､ｼﾓｳﾂﾉ､ｼﾓﾎﾝｺﾞｳ､ｿﾃﾞﾔﾏ､ﾄﾞｳﾉｻﾜ､ﾄﾁｸﾗ､ﾄﾁｸﾗﾐﾅﾐ､ﾅｶﾞｸﾗ､ﾅｶｻﾞﾜ､	岩手県	一関市	坂、三月田、下宇津野、下本郷、外山、堂の沢、栃倉、栃倉南、長倉、中沢、
					// 3209	2101	0210102	ｲﾜﾃｹﾝ	ｲﾁﾉｾｷｼ	ﾊﾁﾓﾘ､ﾊﾞﾊﾞ､ﾋﾛｵﾓﾃ､ﾋﾗﾊﾞ､ﾌﾙｶﾏﾊﾞ､ﾏｶﾞﾘﾌﾁ､ﾏﾂﾊﾞﾗ､ﾐﾅﾐｻﾞﾜ､ﾔｷﾞ､ﾔｯｷﾘ､ﾔｾ､	岩手県	一関市	八森、馬場、広面、平場、古釜場、曲淵、松原、南沢、谷起、焼切、八瀬、
					// 3209	2101	0210102	ｲﾜﾃｹﾝ	ｲﾁﾉｾｷｼ	ﾔﾊﾀ､ﾔﾏﾉｻﾜ)	岩手県	一関市	八幡、山ノ沢）
					'_0210102',
					'岩手県',
					'一関市',
					'萩荘',
					'',
					'',
					'ｲﾜﾃｹﾝ',
					'ｲﾁﾉｾｷｼ',
					'ﾊｷﾞｼｮｳ',
				],
			],
		];

		$data = file_get_contents('/tmp/jpostal_json/021.json');
		$json = json_decode($data);

		foreach ($json as $item) {
			$postcode = $item[0];
			$map[$postcode][] = $item;
		}

		$this->assertEquals(1, count($map['_0210102']));
		$this->assertEquals($expected['_0210102'][0], $map['_0210102'][0]);
	}

	private function assert_issue53_9200381先頭は事業所ではないこと()
	{
		$data = file_get_contents('/tmp/jpostal_json/920.json');
		$arr = json_decode($data);

		$arr9200381 = array_values(array_filter($arr, function ($item) {
			return $item[0] == '_9200381';
		}));

		$first = $arr9200381[0];
		$this->assertTrue($first[4] === '');	// 先頭は事業所ではないこと
		$this->assertTrue($first[5] === '');
	}
}
