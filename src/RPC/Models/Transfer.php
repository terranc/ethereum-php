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

namespace FurqanSiddiqui\Ethereum\RPC\Models;

use FurqanSiddiqui\Ethereum\Ethereum;
use FurqanSiddiqui\Ethereum\Math\Integers;
use FurqanSiddiqui\Ethereum\Math\WEIValue;

/**
 * Class Transaction
 * @package FurqanSiddiqui\Ethereum\RPC\Models
 */
class Transfer extends AbstractRPCResponseModel
{
    /** @var string */
    public string $hash;
    /** @var null|string */
    public ?string $blockNum;
    /** @var null|string */
    public ?string $erc721TokenId = null;
    /** @var string */
    public string $from;
    /** @var string|null */
    public ?string $to;
    /** @var string|null */
    public ?string $category;
    /** @var string */
    public string $value;
    /** @var null|string */
    public ?string $asset;

    /** @var array */
    private array $raw;

    /**
     * Transaction constructor.
     * @param Ethereum $eth
     * @param array $obj
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCResponseParseException
     */
    public function __construct(Ethereum $eth, array $obj)
    {
        // Primary param
        $hash = $obj["hash"];
        if (!is_string($hash) && !preg_match('/^0x[a-f0-9]{66}$/i', $hash)) {
            throw $this->unexpectedParamValue("hash", "hash", gettype($hash));
        }

        $this->hash = $hash;
        $shortTxHash = sprintf("%s...%s", substr($this->hash, 0, 6), substr($this->hash, -4));
        $this->parseExceptionPrefix = sprintf('Ethereum Tx [%s]: ', $shortTxHash);

        // Props (prop => (bool)nullable)
        $props = [
            "blockNum" => true,
            "erc721TokenId" => true,
            "category" => true,
            "value" => false,
            "from" => false,
            "to" => true,
        ];

        foreach ($props as $prop => $nullable) {
            $value = isset($obj[$prop]) ? $obj[$prop] : null;
            if (!is_string($value) || !preg_match('/^0x[a-f0-9]*$/i', $value)) {
                if (is_null($value) && !$nullable) {
                    $this->unexpectedParamValue($prop, "hash", gettype($value));
                } else {
                    $this->unexpectedParamValue($prop, "hash", gettype($value));
                }
            }

            $this->$prop = $value;
        }
        unset($prop, $value, $nullable);

        // From and To
        $this->from = $obj["from"];
        if (!is_string($this->from) || !preg_match('/^0x[a-f0-9]{40}$/i', $this->from)) {
            throw $this->unexpectedParamValue("from", "address");
        }

        $this->to = $obj["to"] ?? null;
        if (is_string($this->to)) {
            if (!preg_match('/^0x[a-f0-9]{40}$/i', $this->to)) {
                throw $this->unexpectedParamValue("to", "address");
            }
        }

        // Decimals
        $decProps = [
            "blockNum",
            "value",
        ];

        foreach ($decProps as $decProp) {
            if($this->$decProp) {
                $this->$decProp = Integers::Unpack($this->$decProp)->value();
            }
        }

        $this->raw = $obj;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->raw;
    }
}
