<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sniffs\AbstractSniffs;

use PHP_CodeSniffer\Files\File;

abstract class AbstractCrossLayerServiceSniff extends AbstractSdkSniff
{
    protected const FORBIDDEN_LAYERS = [
        'Application' => ['Infrastructure', 'Presentation', 'Extension'],
        'Domain' => ['Infrastructure', 'Presentation', 'Extension', 'Application'],
        'Infrastructure' => ['Extension'],
        'Presentation' => ['Extension']
    ];

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param $stackPointer
     *
     * @return void
     */
    public function process(File $phpCsFile, $stackPointer): void
    {
        $filename = $phpCsFile->getFilename();

        $layers = implode('|', array_keys(static::FORBIDDEN_LAYERS));
        preg_match(sprintf('#/(%s)/(.+).php$#', $layers), $filename, $matches);
        if (!$matches || strpos($filename, '/tests/') !== false) {
            return;
        }

        $layer = $matches[1];
        $layerSetting = static::FORBIDDEN_LAYERS[$layer] ?? null;
        if (!$layerSetting) {
            return;
        }
        $namespaces = $this->getNamespaces($phpCsFile, $stackPointer);

        $excludeNamespacePattern = sprintf(
            '/SprykerSdk\\\\Sdk\\\\(%s)\\\\/',
            sprintf('%s|%s', $layers, 'Extension')
        );
        foreach ($namespaces as $namespacePoint => $namespace)
        {
            preg_match($excludeNamespacePattern, $namespace, $layerMatches);
            if (!$layerMatches) {
                return;
            }

            preg_match(sprintf('/\\\\(%s)\\\\/', implode('|', $layerSetting)), $namespace, $matches);

            if ($matches) {
                $phpCsFile->addError(sprintf('`%s` layer can not use `%s` layer inside.', $layer, $matches[1]), $namespacePoint, 'LayerNotAllowed');
            }
        }
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return array<int, string>
     */
    abstract protected function getNamespaces(File $phpCsFile, int $stackPointer): array;
}
