<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use SprykerSdk\Sniffs\AbstractSniffs\AbstractCrossLayerServiceSniff;

class CrossLayerServiceUsageSniff extends AbstractCrossLayerServiceSniff
{
    /**
     * @return mixed[]|void
     */
    public function register()
    {
        return [
            T_USE,
        ];
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return array<int, string>
     */
    protected function getNamespaces(File $phpCsFile, int $stackPointer): array
    {
        $endOfNamespacePosition = $phpCsFile->findEndOfStatement($stackPointer);

        $tokens = $phpCsFile->getTokens();
        $namespaceTokens = array_splice($tokens, $stackPointer + 2, $endOfNamespacePosition - $stackPointer - 2);

        $namespace = '';
        foreach ($namespaceTokens as $token) {
            $namespace .= $token['content'];
        }

        return [$stackPointer => $namespace];
    }
}
