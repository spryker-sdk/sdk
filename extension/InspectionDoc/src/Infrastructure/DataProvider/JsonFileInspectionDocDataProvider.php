<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Infrastructure\DataProvider;

use JsonException;
use Psr\Log\LoggerInterface;

class JsonFileInspectionDocDataProvider implements InspectionDocDataProviderInterface
{
    /**
     * @var string
     */
    protected string $inspectionDocDataFilePath;

    /**
     * @var array|null
     */
    protected ?array $loadedDocs = null;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param string $inspectionDocDataFilePath
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(string $inspectionDocDataFilePath, LoggerInterface $logger)
    {
        $this->inspectionDocDataFilePath = $inspectionDocDataFilePath;
        $this->logger = $logger;
    }

    /**
     * @return array<mixed>
     */
    public function getInspectionDocs(): array
    {
        if ($this->loadedDocs !== null) {
            return $this->loadedDocs;
        }

        $loadedDocs = file_get_contents($this->inspectionDocDataFilePath);

        if ($loadedDocs === false) {
            $this->logger->error(
                sprintf('Unable to load doc file `%s` error `%s`', $this->inspectionDocDataFilePath, error_get_last()['message'] ?? ''),
            );

            return [];
        }

        try {
            $loadedDocsJson = json_decode($loadedDocs, true, 512, \JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->logger->error($e->getMessage());

            return [];
        }

        $this->loadedDocs = $loadedDocsJson;

        return $this->loadedDocs;
    }
}
