<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Controller\v1\SdkUpdateSdkController;
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
        $I->sendApiPost(
            static::ENDPOINT,
            SdkUpdateSdkController::TYPE,
            SdkUpdateSdkController::TYPE,
            [
                'developer_email' => 'test',
                'developer_github_account' => 'test',
            ],
        );

        $I->seeSuccessApiResponse(
            Response::HTTP_OK,
            SdkUpdateSdkController::TYPE,
            SdkUpdateSdkController::TYPE,
            [
                'messages' => [],
            ],
        );
    }
}
