<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Acceptance\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Tests\AcceptanceTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group Acceptance
 * @group Presentation
 * @group RestApi
 * @group Controller
 * @group v1
 * @group HelloControllerCest
 * Add your own group annotations below this line
 */
class HelloControllerCest
{
    /**
     * @var string
     */
    protected const ENDPOINT = '/hello-world';

    /**
     * @skip Need to fix CI for API
     *
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function iSeeJsonResponseAfterCallHelloWorldEndpoint(AcceptanceTester $I): void
    {
        $I->sendGet(self::ENDPOINT);
        $I->seeResponseCodeIs(Response::HTTP_OK);

        $I->seeResponseContainsJson(['result' => "  RUN  /bin/echo \"hello 'World' 'World'\"\n  OUT  hello 'World' 'World'\n  OUT  \n  RES  Command ran successfully\nDebug: Executing stage: hello\nInfo: hello 'World' 'World'\n"]);
    }
}
