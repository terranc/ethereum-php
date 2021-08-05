<?php
/*
 * This file is a part of "furqansiddiqui/ethereum-php" package.
 * https://github.com/furqansiddiqui/ethereum-php
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/furqansiddiqui/ethereum-php/blob/master/LICENSE
 */

declare(strict_types=1);

namespace FurqanSiddiqui\Ethereum\RPC;

use FurqanSiddiqui\Ethereum\Ethereum;
use FurqanSiddiqui\Ethereum\Exception\RPCInvalidResponseException;
use FurqanSiddiqui\Ethereum\RPC\Models\Transfer;

/**
 * Class InfuraAPI
 * @package FurqanSiddiqui\Ethereum\RPC
 */
class AlchemyAPI extends AbstractRPCClient
{
    /** @var string */
    private string $key;

    /**
     * InfuraAPI constructor.
     * @param Ethereum $eth
     * @param string $key
     */
    public function __construct(Ethereum $eth, string $key)
    {
        parent::__construct($eth);

        $this->key = $key;


        // Set HTTP auth basic
//        $this->httpAuthBasic("", $this->projectSecret);
    }

    /**
     * @return string
     */
    protected function getServerURL(): string
    {
        return sprintf('https://eth-mainnet.alchemyapi.io/v2/%s', $this->key);
    }

    public function alchemy_getAssetTransfers(array $param): ?Transfer
    {
        $transfers = $this->call("alchemy_getAssetTransfers", [$param]);
        if (!is_array($transfers)) {
            throw RPCInvalidResponseException::InvalidDataType("alchemy_getAssetTransfers", "Array", gettype($transfers));
        }

        return new Transfer($this->eth, $transfers);
    }
}
