<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use SprykerSdk\Sniffs\AbstractSniffs\AbstractDisallowPrivateSniff;

class DisallowPrivatePropertySniff extends AbstractDisallowPrivateSniff
{
    /**
     * @inheritDoc
     */
    public function register(): array
    {
        return [
            T_VARIABLE,
        ];
    }

    public function process(File $phpCsFile, $stackPointer)
    {
        if ($phpCsFile->findFirstOnLine(T_FUNCTION, $stackPointer)) {
            return;
        }

        parent::process($phpCsFile, $stackPointer);
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return string
     */
    protected function getEntityName(File $phpCsFile, int $stackPointer): string
    {
        $tokens = $phpCsFile->getTokens();

        return $tokens[$stackPointer]['content'];
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return void
     */
    protected function makePrivateProtected(File $phpCsFile, int $stackPointer): void
    {
        $phpCsFile->fixer->beginChangeset();
        $phpCsFile->fixer->replaceToken($phpCsFile->findFirstOnLine(T_PRIVATE, $stackPointer), 'protected');
        $phpCsFile->fixer->endChangeset();
    }
}
