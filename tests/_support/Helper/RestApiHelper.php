<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper;

use Codeception\Module\REST;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;

class RestApiHelper extends REST
{
    /**
     * @part json
     * @part xml
     *
     * @param string $endpoint
     * @param string $id
     * @param string $type
     * @param array $attributes
     *
     * @return void
     */
    public function sendApiPost(string $endpoint, string $id, string $type, array $attributes = []): void
    {
        $this->haveHttpHeader('Content-Type', 'application/json');
        $this->sendPost($endpoint, [
            OpenApiField::DATA => [
                OpenApiField::ID => $id,
                OpenApiField::TYPE => $type,
                OpenApiField::ATTRIBUTES => $attributes,
            ],
        ]);
    }

    /**
     * @part json
     * @part xml
     *
     * @param string $responseCode
     * @param string $id
     * @param string $type
     * @param array $attributes
     *
     * @return void
     */
    public function seeApiResponse(string $responseCode, string $id, string $type, array $attributes = []): void
    {
        $this->seeResponseCodeIs($responseCode);

        $this->seeResponseContainsJson([
            OpenApiField::DATA => [
                OpenApiField::ID => $id,
                OpenApiField::TYPE => $type,
                OpenApiField::ATTRIBUTES => $attributes,
            ],
        ]);
    }
}
