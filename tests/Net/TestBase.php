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
			gethostbyname(self::HOSTNAME) !== self::HOSTNAME;
	}

	public function setUp()
	{
		if (!self::$network_available)
		{
			$this->markTestSkipped('Network is unavailable.');
		}

		parent::setUp();
	}

	protected function getRSAPrivateKey()
	{
		$key = new Crypt_RSA();

		$key->loadKey(
			"-----BEGIN RSA PRIVATE KEY-----\n" .
			"MIICXQIBAAKBgQC0VM7Xdyi5WEwfb9v2PUgYijEIdY3jhEm0h64YiCP5+OphavC7\n" .
			"7xk8K8YNht7Q8kTBRGwRF0eDr3tybQscR6cBt0u/dBCouSZXOsLD8xfhD9NC3A1o\n" .
			"5xLt2wsTuZ6rqK4ZIaMyLk293OaRk6SHr7Eb/Hz3IZ8/NiexkL7FOjMbzwIDAQAB\n" .
			"AoGAcnuczQFjIzfBK/wRwuPoz1t8AYjPyW8Ec83nYr+bR2kVMz93EXibpq2LkK7c\n" .
			"6f3EVIYhrUAAMMPJNT2w3gVHKijWX9Q9tDI7VTV0dG26u62hw55R19NF2Qiigpac\n" .
			"FKU2RLvtnHd5R3R2KjKeWDHDewei14tWSow9dUyWduxDAdECQQDcCPm98OLO+Z23\n" .
			"4IpHe0jOPDGNPJ5ZAcdVzcIL08Kgsh8AuyyGq6FphttfiOj0ThFXQBimeVog7KSy\n" .
			"8yBkX1RNAkEA0c59Vic0BCShfDpvT4tCZUS1bm7MXo5MvVt5LIoZfwnVd88AcVdS\n" .
			"hkQLQdmq6gc/Mnk8EQKwNqQlR/KhzbSuiwJARktTxduUCf1y5pCEfKulKcXPKsjn\n" .
			"6ZWI4h+W04J2VjIxd2FUqz1flr/vi4jIq2vZXF9swJpaMdSIFWdYvNg7rQJBAMGi\n" .
			"+F65U/K29Cu5qt4ZQzA/18uSiyCB6SWi9RU8aAetxc0LyfbRhyLQTit6f5n7EkK9\n" .
			"TcjNWGE14gqjTt6R8b0CQQCzyaNaTadproJW63OqR0/xv4PnSnHElYMv5pnw03Ol\n" .
			"3ze3ZusQPncwzQltXF5l6llvSojh69hsrCamCjx69VAT\n" .
			"-----END RSA PRIVATE KEY-----\n"
		);

		return $key;
	}

	protected function getRSAPublicKey()
	{
		return $this->getRSAPrivateKey()->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
	}
}
