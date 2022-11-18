<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Controller\v1\SdkUpdateSdkController;
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
 * @group SdkUpdateSdkControllerCest
 * Add your own group annotations below this line
 */
class SdkUpdateSdkControllerCest
{
    /**
     * @var string
     */
    protected const ENDPOINT = '/sdk-update-sdk';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function iSeeJsonResponseAfterCallSdkUpdateSdkEndpoint(AcceptanceTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(static::ENDPOINT, [
            OpenApiField::DATA => [
                OpenApiField::TYPE => SdkUpdateSdkController::TYPE,
                OpenApiField::ID => SdkUpdateSdkController::TYPE,
                OpenApiField::ATTRIBUTES => [
                    'developer_email' => 'test',
                    'developer_github_account' => 'test',
                ],
            ],
        ]);
        $I->seeResponseCodeIs(Response::HTTP_OK);

        $I->seeResponseContainsJson(
            [
                OpenApiField::DATA => [
                    OpenApiField::TYPE => SdkUpdateSdkController::TYPE,
                    OpenApiField::ID => SdkUpdateSdkController::TYPE,
                    OpenApiField::ATTRIBUTES => [
                        'messages' => [],
                    ],
                ],
            ],
        );
    }
}
