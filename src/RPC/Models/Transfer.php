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
    public array $transfer;

    /** @var array */
    private array $raw;

    /**
     * Transaction constructor.
     * @param Ethereum $eth
     * @param array $obj
     */
    public function __construct(Ethereum $eth, array $obj)
    {

        $this->transfer = $obj['transfer'] ?? [];

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
