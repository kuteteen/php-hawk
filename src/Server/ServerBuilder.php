<?php

namespace Dragooon\Hawk\Server;

use Dragooon\Hawk\Crypto\Crypto;
use Dragooon\Hawk\Time\DefaultTimeProviderFactory;
use Dragooon\Hawk\Time\TimeProviderInterface;
use Dragooon\Hawk\Credentials\CredentialsProviderInterface;

class ServerBuilder
{
    private $crypto;
    private $credentialsProvider;
    private $timeProvider;
    private $nonceValidator;
    private $timestampSkewSec;
    private $localtimeOffsetSec;

    /**
     * @param CredentialsProviderInterface $credentialsProvider
     */
    public function __construct($credentialsProvider)
    {
        $this->credentialsProvider = $credentialsProvider;
    }

    /**
     * @param Crypto $crypto
     * @return $this
     */
    public function setCrypto(Crypto $crypto)
    {
        $this->crypto = $crypto;

        return $this;
    }

    /**
     * @param TimeProviderInterface $timeProvider
     * @return $this
     */
    public function setTimeProvider(TimeProviderInterface $timeProvider)
    {
        $this->timeProvider = $timeProvider;

        return $this;
    }

    /**
     * @param $nonceValidator
     * @return $this
     */
    public function setNonceValidator($nonceValidator)
    {
        $this->nonceValidator = $nonceValidator;

        return $this;
    }

    /**
     * @param int $timestampSkewSec
     * @return $this
     */
    public function setTimestampSkewSec($timestampSkewSec = null)
    {
        $this->timestampSkewSec = $timestampSkewSec;

        return $this;
    }

    /**
     * @param int $localtimeOffsetSec
     * @return $this
     */
    public function setLocaltimeOffsetSec($localtimeOffsetSec = null)
    {
        $this->localtimeOffsetSec = $localtimeOffsetSec;

        return $this;
    }

    /**
     * @return Server
     */
    public function build()
    {
        $crypto = $this->crypto ?: new Crypto;
        $timeProvider = $this->timeProvider ?: DefaultTimeProviderFactory::create();
        $nonceValidator = $this->nonceValidator ?: function($nonce, $timestamp) {
            return true;
        };
        $timestampSkewSec = $this->timestampSkewSec ?: 60;
        $localtimeOffsetSec = $this->localtimeOffsetSec ?: 0;

        return new Server(
            $crypto,
            $this->credentialsProvider,
            $timeProvider,
            $nonceValidator,
            $timestampSkewSec,
            $localtimeOffsetSec
        );
    }

    /**
     * @param CredentialsProviderInterface $credentialsProvider
     * @return static
     */
    public static function create($credentialsProvider)
    {
        return new static($credentialsProvider);
    }
}