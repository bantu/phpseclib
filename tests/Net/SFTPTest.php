<?php
/**
 * @author     Andreas Fischer <bantu@phpbb.com>
 * @copyright  MMXII Andreas Fischer
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

class Net_SFTPTest extends Net_TestBase
{
	const EXAMPLE_FILENAME = 'example.txt';
	const EXAMPLE_CONTENT = "The quick brown fox jumps over the lazy dog\n";

	public function testConstructor()
	{
		$sftp = new Net_SFTP(self::HOSTNAME);

		$this->assertTrue(
			is_object($sftp),
			'Could not construct NET_SFTP object.'
		);

		return $sftp;
	}

	/**
	* @depends testConstructor
	*/
	public function testRSAPrivateKeyLogin($sftp)
	{
		$this->assertTrue(
			$sftp->login(self::USERNAME, $this->getRSAPrivateKey()),
			'SFTP login using RSA private key failed.'
		);

		return $sftp;
	}

	/**
	* @depends testRSAPrivateKeyLogin
	*/
	public function testPwd($sftp)
	{
		$this->assertEquals('/home/phpseclib/', $sftp->pwd());
	}

	/**
	* @depends testRSAPrivateKeyLogin
	*/
	public function testGet($sftp)
	{
		$this->assertEquals(
			self::EXAMPLE_CONTENT,
			$sftp->get(self::EXAMPLE_FILENAME),
			'Content of fetched example file does not match expected example content.'
		);
	}

	/**
	* @depends testRSAPrivateKeyLogin
	*/
	public function testSize($sftp)
	{
		$this->assertEquals(
			strlen(self::EXAMPLE_CONTENT),
			$sftp->size(self::EXAMPLE_FILENAME),
			'Filesize of example file does not match expected filesize.'
		);
	}

	/**
	* @depends testRSAPrivateKeyLogin
	*/
	public function testNList($sftp)
	{
		$result = $sftp->nlist();
		$subset = array('.', '..', self::EXAMPLE_FILENAME);

		foreach ($subset as $item)
		{
			$this->assertContains(
				$item,
				$result,
				"Failed asserting that nlist() array contains '$item'"
			);
		}
	}
}
