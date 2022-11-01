<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Presentation\RestApi\Controller\v1;

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
 * @group SdkInitSdkControllerCest
 * Add your own group annotations below this line
 */
class SdkInitSdkControllerCest
{
    /**
     * @var string
     */
    protected const ENDPOINT = '/sdk-init-sdk';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function iSeeJsonResponseAfterCallHelloWorldEndpoint(AcceptanceTester $I): void
    {
        $I->sendPost(static::ENDPOINT, ['developer_email' => 'test', 'developer_github_account' => 'test']);
        $I->seeResponseCodeIs(Response::HTTP_OK);

        $I->seeResponseContainsJson([]);
    }
}
