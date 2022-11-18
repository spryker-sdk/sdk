<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Controller\v1\SdkInitProjectController;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use SprykerSdk\Sdk\Tests\AcceptanceTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Acceptance
 * @group Presentation
 * @group RestApi
 * @group Controller
 * @group v1
 * @group SdkInitProjectControllerCest
 * Add your own group annotations below this line
 */
class SdkInitProjectControllerCest
{
    /**
     * @var string
     */
    protected const ENDPOINT = '/sdk-init-project';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function iSeeJsonResponseAfterCallSdkInitProjectEndpoint(AcceptanceTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(static::ENDPOINT, [
            OpenApiField::DATA => [
                OpenApiField::TYPE => SdkInitProjectController::TYPE,
                OpenApiField::ID => SdkInitProjectController::TYPE,
                OpenApiField::ATTRIBUTES => [
                    'report_usage_statistics' => false,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(Response::HTTP_OK);

        $I->seeResponseContainsJson([]);
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function iSeeBadRequestAfterCallSdkInitProjectEndpoint(AcceptanceTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(static::ENDPOINT, [
            OpenApiField::DATA => [
                OpenApiField::TYPE => SdkInitProjectController::TYPE,
                OpenApiField::ID => SdkInitProjectController::TYPE,
            ],
        ]);

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }
}
