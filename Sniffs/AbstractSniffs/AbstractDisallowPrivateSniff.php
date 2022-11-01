<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sniffs\AbstractSniffs;

use PHP_CodeSniffer\Files\File;
use SprykerSdk\Sniffs\AbstractSniffs\AbstractSdkSniff;

abstract class AbstractDisallowPrivateSniff extends AbstractSdkSniff
{
    /**
     * @inheritDoc
     */
    public function process(File $phpCsFile, $stackPointer)
    {
        if ($this->isPrivate($phpCsFile, $stackPointer) && !$this->isPhpStormMarker($phpCsFile, $stackPointer)) {
            $classEntity = $this->getClassEntity($phpCsFile, $stackPointer);
            $fix = $phpCsFile->addFixableError($classEntity . ' is private.', $stackPointer, 'PrivateNotAllowed');
            if ($fix) {
                $this->makePrivateProtected($phpCsFile, $stackPointer);
            }
        }
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    protected function isPrivate(File $phpCsFile, int $stackPointer): bool
    {
        $privateTokenPointer = $phpCsFile->findFirstOnLine(T_PRIVATE, $stackPointer);

        if ($privateTokenPointer) {
            return true;
        }

        return false;
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
        $entityNamePosition = $phpCsFile->findNext(T_STRING, $stackPointer);

        return $tokens[$entityNamePosition]['content'];
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     *
     * @return string
     */
    protected function getClassName(File $phpCsFile): string
    {
        $fileName = $phpCsFile->getFilename();
        $fileNameParts = explode(DIRECTORY_SEPARATOR, $fileName);
        $sourceDirectoryPosition = array_search('src', array_values($fileNameParts), true);
        $classNameParts = array_slice($fileNameParts, $sourceDirectoryPosition + 1);
        $className = implode('\\', $classNameParts);
        $className = str_replace('.php', '', $className);

        return $className;
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return string
     */
    protected function getClassEntity(File $phpCsFile, int $stackPointer): string
    {
        $className = $this->getClassName($phpCsFile);
        $entityName = $this->getEntityName($phpCsFile, $stackPointer);

        return $className . '::' . $entityName;
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
        $phpCsFile->fixer->replaceToken($stackPointer - 2, 'protected');
        $phpCsFile->fixer->endChangeset();
    }
}
