<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\Annotation\GenericAnnotation;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\AnnotationTypeHelper;
use SlevomatCodingStandard\Helpers\TypeHelper;
use SlevomatCodingStandard\Helpers\TypeHintHelper;
use SprykerSdk\Sniffs\AbstractSniffs\AbstractCrossLayerServiceSniff;

class CrossLayerServiceAnnotationSniff extends AbstractCrossLayerServiceSniff
{
    /**
     * @return array
     */
    public function register(): array
    {
        return [
            T_DOC_COMMENT_OPEN_TAG,
        ];
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpCsFile
     * @param int $stackPointer
     *
     * @return array<string>
     */
    protected function getNamespaces(File $phpCsFile, int $stackPointer): array
    {
        $annotations = AnnotationHelper::getAnnotations($phpCsFile, $stackPointer);

        $namespaces = [];
        foreach ($annotations as $annotationsByName) {
            foreach ($annotationsByName as $annotation) {
                if ($annotation instanceof GenericAnnotation) {
                    continue;
                }

                if ($annotation->isInvalid()) {
                    continue;
                }

                foreach (AnnotationHelper::getAnnotationTypes($annotation) as $annotationType) {
                    foreach (AnnotationTypeHelper::getIdentifierTypeNodes($annotationType) as $typeHintNode) {
                        $typeHint = AnnotationTypeHelper::getTypeHintFromNode($typeHintNode);

                        $lowercasedTypeHint = strtolower($typeHint);
                        if (
                            TypeHintHelper::isSimpleTypeHint($lowercasedTypeHint)
                            || TypeHintHelper::isSimpleUnofficialTypeHints($lowercasedTypeHint)
                            || !TypeHelper::isTypeName($typeHint)
                            || TypeHintHelper::isTypeDefinedInAnnotation($phpCsFile, $stackPointer, $typeHint)
                        ) {
                            continue;
                        }
                        $startPoint = $annotation->getStartPointer();
                        $namespaces[$startPoint] = TypeHintHelper::getFullyQualifiedTypeHint(
                            $phpCsFile,
                            $startPoint,
                            $typeHint
                        );
                    }
                }
            }
        }

        return $namespaces;
    }
}
