<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Infrastructure\Repository;

use InspectionDoc\Entity\InspectionDoc;
use InspectionDoc\Entity\InspectionDocInterface;
use InspectionDoc\Infrastructure\DataProvider\InspectionDocDataProviderInterface;

class InspectionDocRepository implements InspectionDocRepositoryInterface
{
    /**
     * @var array<string, \InspectionDoc\Entity\InspectionDocInterface>
     */
    protected array $inspectionDocsCache = [];

    /**
     * @var \InspectionDoc\Infrastructure\DataProvider\InspectionDocDataProviderInterface
     */
    protected InspectionDocDataProviderInterface $inspectionDocDataProvider;

    /**
     * @param \InspectionDoc\Infrastructure\DataProvider\InspectionDocDataProviderInterface $inspectionDocDataProvider
     */
    public function __construct(InspectionDocDataProviderInterface $inspectionDocDataProvider)
    {
        $this->inspectionDocDataProvider = $inspectionDocDataProvider;
    }

    /**
     * @param string $errorCode
     *
     * @return \InspectionDoc\Entity\InspectionDocInterface|null
     */
    public function findByErrorCode(string $errorCode): ?InspectionDocInterface
    {
        $indexedInspectionDocs = $this->inspectionDocDataProvider->getInspectionDocs();

        $inspectionId = $this->findInspectionIdByErrorCode(array_keys($indexedInspectionDocs), $errorCode);

        if ($inspectionId === null) {
            return null;
        }

        $inspectionDoc = $indexedInspectionDocs[$inspectionId];

        return $this->createInspectionDoc($inspectionId, $inspectionDoc);
    }

    /**
     * @param array $inspectionsIds
     * @param string $errorCode
     *
     * @return string|null
     */
    protected function findInspectionIdByErrorCode(array $inspectionsIds, string $errorCode): ?string
    {
        $inspectionIdCandidates = [];

        foreach ($inspectionsIds as $inspectionsId) {
            if (!$this->strStartsWith($errorCode, $inspectionsId)) {
                continue;
            }

            $inspectionIdCandidates[] = $inspectionsId;
        }

        return $this->getMaxLengthMatch($inspectionIdCandidates);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    protected function strStartsWith(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, mb_strlen($needle)) === 0;
    }

    /**
     * @param array<string> $inspectionsIds
     *
     * @return string|null
     */
    protected function getMaxLengthMatch(array $inspectionsIds): ?string
    {
        usort($inspectionsIds, static fn (string $a, string $b): int => -1 * (mb_strlen($a) <=> mb_strlen($b)));

        return $inspectionsIds[0] ?? null;
    }

    /**
     * @param string $inspectionId
     * @param array $inspectionDocData
     *
     * @return \InspectionDoc\Entity\InspectionDocInterface
     */
    protected function createInspectionDoc(string $inspectionId, array $inspectionDocData): InspectionDocInterface
    {
        if (!isset($this->inspectionDocsCache[$inspectionId])) {
            $this->inspectionDocsCache[$inspectionId] = new InspectionDoc($inspectionId, $inspectionDocData['link'] ?? '');
        }

        return $this->inspectionDocsCache[$inspectionId];
    }
}
