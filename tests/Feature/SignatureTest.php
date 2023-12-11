<?php

namespace LensaWicara\SnapBI;

use Illuminate\Contracts\Config\Repository;
use LensaWicara\SnapBI\Signature\AsymmetricPayload;
use LensaWicara\SnapBI\Support\Signature;
use LensaWicara\Tests\TestCase;
use phpseclib3\Crypt\RSA;
use PHPUnit\Framework\Attributes\Test;

class SignatureTest extends TestCase
{
    const KEYS = __DIR__.'/keys';

    const PUBLIC_KEY = self::KEYS.'/snap_test_public.key';

    const PRIVATE_KEY = self::KEYS.'/snap_test_private.key';

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \phpseclib3\Crypt\RSA\PrivateKey $key */
        $rsa = RSA::createKey(4096);

        // if file does not exist, create it
        if (! file_exists(self::KEYS)) {
            mkdir(self::KEYS, 0777, true);
        }

        // if .key file does not exist, create it
        if (! file_exists(self::PUBLIC_KEY) || ! file_exists(self::PRIVATE_KEY)) {
            touch(self::PUBLIC_KEY);
            touch(self::PRIVATE_KEY);
        }

        file_put_contents(self::PUBLIC_KEY, (string) $rsa->getPublicKey());
        file_put_contents(self::PRIVATE_KEY, (string) $rsa);
    }

    public function getEnvironmentSetUp($app)
    {
        // set config to use test keys
        $app->make(Repository::class)->set('snap-bi.providers.aspi.public_key', self::PUBLIC_KEY);
        $app->make(Repository::class)->set('snap-bi.providers.aspi.private_key', self::PRIVATE_KEY);
    }

    #[Test]
    // can generate asymmetric signature
    public function can_generate_asymmetric_signature()
    {
        $signature = new Signature();

        $asymmetricPayload = new AsymmetricPayload(
            config('snap-bi.providers.aspi.client_id')
        );

        $response = $signature->asymmetric(
            $asymmetricPayload
        );

        // assert string
        $this->assertIsString($response);
    }

    #[Test]
    // can verify asymmetric signature
    public function can_verify_asymmetric_signature()
    {
        $signature = new Signature();

        $asymmetricPayload = new AsymmetricPayload(
            config('snap-bi.providers.aspi.client_id')
        );

        $sign = $signature->asymmetric(
            $asymmetricPayload
        );

        $verify = $signature->asymmetricVerify(
            $asymmetricPayload,
            $sign,
        );

        $this->assertIsString($sign);
        $this->assertIsInt($verify);
        $this->assertEquals(1, $verify);
    }
}
