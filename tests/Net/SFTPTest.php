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
	const EXAMPLE_DIRNAME = 'phpseclib_dir_test';

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

	/**
	* @depends testRSAPrivateKeyLogin
	*/
	public function testRawList($sftp)
	{
		$result = $sftp->rawlist();
		$files = array('.', '..', self::EXAMPLE_FILENAME);

		foreach ($files as $key)
		{
			$this->assertArrayHasKey($key, $result);

			$this->assertTrue(
				isset($result[$key]) && is_array($result[$key]),
				"Failed asserting that $result[$key] is set and an array."
			);
		}

		$this->assertTrue(
			isset($result[self::EXAMPLE_FILENAME]['size']) &&
			$result[self::EXAMPLE_FILENAME]['size'] === strlen(self::EXAMPLE_CONTENT),
			'Failed asserting that rawlist() result contains the example file and its size is equal to the excepted size.'
		);
	}

	/**
	* @depends testRSAPrivateKeyLogin
	*/
	public function testMkDir($sftp)
	{
		$dirname = self::EXAMPLE_DIRNAME;

		$this->assertTrue(
			$sftp->mkdir($dirname),
			"Failed asserting that a new directory called '$dirname' could be created."
		);

		$this->assertFalse(
			$sftp->mkdir($dirname),
			"Failed asserting that a new directory called '$dirname' could not be created (because it already exists)."
		);

		return $sftp;
	}

	/**
	* @depends testMkDir
	*/
	public function testChDir($sftp)
	{
		$dirname = self::EXAMPLE_DIRNAME;
		$pwd = $sftp->pwd();

		$this->assertTrue(
			$sftp->chdir($dirname),
			"Failed asserting that directory could be changed to '$dirname'."
		);

		$this->assertEquals(
			$pwd . $dirname . '/',
			$sftp->pwd(),
			'Failed asserting that result of pwd() returns the new path.'
		);

		$this->assertTrue(
			$sftp->chdir('../'),
			"Failed asserting that directory could be changed to one level up."
		);

		return $sftp;
	}

	/**
	* @depends testChDir
	*/
	public function testRenameDir($sftp)
	{
		$dirname = self::EXAMPLE_DIRNAME;
		$dirname2 = self::EXAMPLE_DIRNAME . '2';
		$dirname3 = self::EXAMPLE_DIRNAME . '3';

		$this->assertTrue(
			$sftp->rename($dirname, $dirname2),
			"Failed asserting that directory '$dirname' could be renamed to '$dirname2'."
		);

		$this->assertFalse(
			$sftp->rename($dirname, $dirname3),
			"Failed asserting that directory '$dirname' could not be renamed to '$dirname3' (because '$dirname' does not exists)."
		);

		$this->assertTrue(
			$sftp->rename($dirname2, $dirname),
			"Failed asserting that directory '$dirname2' could be renamed back to '$dirname'."
		);

		return $sftp;
	}

	/**
	* @depends testRenameDir
	*/
	public function testRmDir($sftp)
	{
		$dirname = self::EXAMPLE_DIRNAME;

		$this->assertTrue(
			$sftp->rmdir($dirname),
			"Failed asserting that the '$dirname' directory could be deleted."
		);

		$this->assertFalse(
			$sftp->rmdir($dirname),
			"Failed asserting that the '$dirname' could not be deleted (because it does not exists)."
		);
	}
}
