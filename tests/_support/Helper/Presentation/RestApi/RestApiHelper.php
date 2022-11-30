<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Presentation\RestApi;

use Codeception\Module;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;

class RestApiHelper extends Module
{
    /**
     * @param string $type
     * @param string $id
     * @param array $attributes
     *
     * @return array<array>
     */
    public function createSuccessJsonStruct(string $type, string $id, array $attributes = []): array
    {
        $data = [
            OpenApiField::DATA => [
                OpenApiField::TYPE => $type,
                OpenApiField::ID => $id,
            ],
        ];

        if (count($attributes)) {
            $data[OpenApiField::DATA][OpenApiField::ATTRIBUTES] = $attributes;
        }

        return $data;
    }

    /**
     * @param array $details
     * @param int $code
     * @param string $status
     *
     * @return array
     */
    public function createErrorJsonStruct(array $details, int $code, string $status): array
    {
        return [
            OpenApiField::DETAILS => $details,
            OpenApiField::CODE => $code,
            OpenApiField::STATUS => $status,
        ];
    }
}
