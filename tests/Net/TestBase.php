<?php
/**
 * @author     Andreas Fischer <bantu@phpbb.com>
 * @copyright  MMXII Andreas Fischer
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

abstract class Net_TestBase extends PHPUnit_Framework_TestCase
{
	const HOSTNAME = 'phpseclib.unittest.bantux.org';
	const USERNAME = 'phpseclib';

	static protected $network_available;

	static public function setUpBeforeClass()
	{
		self::$network_available =
			function_exists('fsockopen') &&
			gethostbyname(self::HOSTNAME) !== self::HOSTNAME &&
			file_exists(dirname(__FILE__) . '/../id_rsa');
	}

	public function setUp()
	{
		if (!self::$network_available)
		{
			$this->markTestSkipped('Network is unavailable or RSA private key file id_rsa does not exist.');
		}

		parent::setUp();
	}

	protected function getRSAPrivateKey()
	{
		$key = new Crypt_RSA();
		$key->loadKey(file_get_contents(dirname(__FILE__) . '/../id_rsa'));

		return $key;
	}

	protected function getRSAPublicKey()
	{
		return $this->getRSAPrivateKey()->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
	}
}
