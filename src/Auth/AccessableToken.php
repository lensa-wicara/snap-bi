<?php 

namespace LensaWicara\SnapBI\Auth;

use Illuminate\Support\Facades\Cache;

class AccessableToken
{
    /**
     * Cache provider name
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct($name = 'test')
    {
        $this->name = $name;
    }

    /**
     * Get cache name
     *
     * @return string
     */
    protected function cacheKey()
    {
        return "snap.{$this->name}.access_token";
    }

    /**
     * Store access token payload to cache
     *
     * @return void
     */
    protected function cache(array $payload)
    {
        Cache::put($this->cacheKey(), $payload, data_get($payload, 'expiresIn', 600));
    }

    /**
     * Put access token payload to cache
     *
     * @return void
     */
    public static function put($name, array $payload)
    {
        (new static($name))->cache($payload);
    }

    /**
     * Get access token instance
     *
     * @param string $name
     *
     * @return static
     */
    public static function get($name)
    {
        $token = new static($name);

        if (!Cache::has($token->cacheKey())) {
            (new AccessToken($token->name))->getAccessToken();
        }

        return $token;
    }

    public function __toString()
    {
        $payload = Cache::get($this->cacheKey());

        if (!$payload) {
            throw new \Exception('Access token not found.');
        }

        return data_get($payload, 'accessToken');
    }
}