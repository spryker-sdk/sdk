<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Logger;

class TransactionNameNewRelicProcessor
{
    /**
     * @var string
     */
    protected string $transactionName;

    /**
     * @param string $transactionName
     */
    public function __construct(string $transactionName)
    {
        $this->transactionName = $transactionName;
    }

    /**
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    public function __invoke(array $record): array
    {
        if (!isset($record['context'])) {
            $record['context'] = [];
        }

        $record['context']['transaction_name'] = $this->transactionName;

        return $record;
    }
}
