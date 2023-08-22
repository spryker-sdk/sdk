<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Logger;

use Monolog\Formatter\FormatterInterface;

class NewRelicFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    protected string $workspaceName;

    /**
     * @var string
     */
    protected string $ciExecutionId;

    /**
     * @param string $workspaceName
     * @param string $ciExecutionId
     */
    public function __construct(string $workspaceName, string $ciExecutionId)
    {
        $this->workspaceName = $workspaceName;
        $this->ciExecutionId = $ciExecutionId;
    }

    /**
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    public function format(array $record): array
    {
        if (!isset($record['context'])) {
            $record['context'] = [];
        }

        $record['context']['workspace_name'] = $this->workspaceName;
        $record['context']['ci_execution_id'] = $this->ciExecutionId;

        return $record;
    }

    /**
     * @param array<mixed> $records
     *
     * @return array<mixed>
     */
    public function formatBatch(array $records): array
    {
        foreach ($records as $key => $record) {
            $records[$key] = $this->format($record);
        }

        return $records;
    }
}
