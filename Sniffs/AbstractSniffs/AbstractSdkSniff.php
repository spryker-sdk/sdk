<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerSdk\Sniffs\AbstractSniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\EmptyFileException;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use Spryker\Traits\BasicsTrait;

abstract class AbstractSdkSniff implements Sniff
{
    use BasicsTrait;

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    protected function isPhpStormMarker(File $phpCsFile, int $stackPointer)
    {
        $tokens = $phpCsFile->getTokens();
        $docBlockEndIndex = $this->findRelatedDocBlock($phpCsFile, $stackPointer);

        /** @var int $docBlockStartIndex */
        $docBlockStartIndex = $tokens[$docBlockEndIndex]['comment_opener'];

        for ($i = $docBlockStartIndex + 1; $i < $docBlockEndIndex; ++$i) {
            if (empty($tokens[$i]['content'])) {
                continue;
            }
            $content = $tokens[$i]['content'];
            $pos = stripos($content, '@noinspection');
            if ($pos === false) {
                continue;
            }

            if ($pos && strpos('@noinspection', '@') === 0 && substr($content, $pos - 1, $pos) === '{') {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get level of indentation, 0 based.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $index
     *
     * @return int
     */
    protected function getIndentationLevel(File $phpcsFile, int $index): int
    {
        $tokens = $phpcsFile->getTokens();

        $whitespace = $this->getIndentationWhitespace($phpcsFile, $index);
        $char = $this->getIndentationCharacter($whitespace);

        $level = $tokens[$index]['column'] - 1;

        if ($char === "\t") {
            return $level;
        }

        return (int)($level / 4);
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     *
     * @return string
     */
    protected function getNamespace(File $phpCsFile): string
    {
        $className = $this->getClassName($phpCsFile);
        $classNameParts = explode('\\', $className);

        return $classNameParts[0];
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     *
     * @return string|null
     */
    protected function getClassNameWithNamespace(File $phpCsFile): ?string
    {
        try {
            $lastToken = TokenHelper::getLastTokenPointer($phpCsFile);
        } catch (EmptyFileException $e) {
            return null;
        }

        if (!NamespaceHelper::findCurrentNamespaceName($phpCsFile, $lastToken)) {
            return null;
        }

        $prevIndex = $phpCsFile->findPrevious(TokenHelper::$typeKeywordTokenCodes, $lastToken);
        if (!$prevIndex) {
            return null;
        }

        return ClassHelper::getFullyQualifiedName(
            $phpCsFile,
            $prevIndex,
        );
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     *
     * @return string
     */
    protected function getClassName(File $phpCsFile): string
    {
        $namespace = $this->getClassNameWithNamespace($phpCsFile);

        if ($namespace) {
            return trim($namespace, '\\');
        }

        $fileName = $phpCsFile->getFilename();
        $fileNameParts = explode(DIRECTORY_SEPARATOR, $fileName);
        $directoryPosition = array_search('src', array_values($fileNameParts), true);
        if (!$directoryPosition) {
            $directoryPosition = array_search('tests', array_values($fileNameParts), true) + 1;
        }
        $classNameParts = array_slice($fileNameParts, $directoryPosition + 1);
        $className = implode('\\', $classNameParts);
        $className = str_replace('.php', '', $className);

        return $className;
    }

    /**
     * Checks if the given token scope contains a single or multiple token codes/types.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param array<string|int>|string|int $search
     * @param int $start
     * @param int $end
     * @param bool $skipNested
     *
     * @return bool
     */
    protected function contains(File $phpcsFile, $search, int $start, int $end, bool $skipNested = true): bool
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $start; $i <= $end; $i++) {
            if ($skipNested && $tokens[$i]['code'] === T_OPEN_PARENTHESIS) {
                $i = $tokens[$i]['parenthesis_closer'];

                continue;
            }
            if ($skipNested && $tokens[$i]['code'] === T_OPEN_SHORT_ARRAY) {
                $i = $tokens[$i]['bracket_closer'];

                continue;
            }
            if ($skipNested && $tokens[$i]['code'] === T_OPEN_CURLY_BRACKET) {
                $i = $tokens[$i]['bracket_closer'];

                continue;
            }

            if ($this->isGivenKind($search, $tokens[$i])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $content
     * @param bool $correctLength
     *
     * @return string
     */
    protected function getIndentationCharacter(string $content, bool $correctLength = false): string
    {
        if (strpos($content, "\n")) {
            $parts = explode("\n", $content);
            array_shift($parts);
        } else {
            $parts = (array)$content;
        }

        $char = "\t";
        $countTabs = $countSpaces = 0;
        foreach ($parts as $part) {
            $countTabs += substr_count($part, $char);
            $countSpaces += (int)(substr_count($part, ' ') / 4);
        }

        if ($countSpaces > $countTabs) {
            $char = $correctLength ? '    ' : ' ';
        }

        return $char;
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $prevIndex
     *
     * @return string
     */
    protected function getIndentationWhitespace(File $phpcsFile, int $prevIndex): string
    {
        $tokens = $phpcsFile->getTokens();

        $firstIndex = $this->getFirstTokenOfLine($tokens, $prevIndex);
        $whitespace = '';
        if ($tokens[$firstIndex]['type'] === 'T_WHITESPACE' || $tokens[$firstIndex]['type'] === 'T_DOC_COMMENT_WHITESPACE') {
            $whitespace = $tokens[$firstIndex]['content'];
        }

        return $whitespace;
    }

    /**
     * @param array<int, array<string, mixed>> $tokens
     * @param int $index
     *
     * @return int
     */
    protected function getFirstTokenOfLine(array $tokens, int $index): int
    {
        $line = $tokens[$index]['line'];

        $currentIndex = $index;
        while ($tokens[$currentIndex - 1]['line'] === $line) {
            $currentIndex--;
        }

        return $currentIndex;
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return int|null Stackpointer value of docblock end tag, or null if cannot be found
     */
    protected function findRelatedDocBlock(File $phpCsFile, int $stackPointer): ?int
    {
        $tokens = $phpCsFile->getTokens();

        $beginningOfLine = $this->getFirstTokenOfLine($tokens, $stackPointer);

        $prevContentIndex = $phpCsFile->findPrevious(T_WHITESPACE, $beginningOfLine - 1, null, true);
        if (!$prevContentIndex) {
            return null;
        }
        if ($tokens[$prevContentIndex]['type'] === 'T_ATTRIBUTE_END') {
            $beginningOfLine = $this->getFirstTokenOfLine($tokens, $prevContentIndex);
        }

        if (!empty($tokens[$beginningOfLine - 2]) && $tokens[$beginningOfLine - 2]['type'] === 'T_DOC_COMMENT_CLOSE_TAG') {
            return $beginningOfLine - 2;
        }

        if (!empty($tokens[$beginningOfLine - 3]) && $tokens[$beginningOfLine - 3]['type'] === 'T_DOC_COMMENT_CLOSE_TAG') {
            return $beginningOfLine - 3;
        }

        return null;
    }
}
