<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Controller\v1\RunTaskController;
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
 * @group RunTaskControllerCest
 * Add your own group annotations below this line
 */
class RunTaskControllerCest
{
    /**
     * @var string
     */
    protected const ENDPOINT = '/task/hello:world';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function iSeeJsonResponseAfterCallRunTaskEndpoint(AcceptanceTester $I): void
    {
        $I->sendApiPost(
            static::ENDPOINT,
            'hello:world',
            RunTaskController::TYPE,
            [
                'world' => 'World',
                'somebody' => 'World',
            ],
        );

        $I->seeSuccessApiResponse(
            Response::HTTP_OK,
            'hello:world',
            RunTaskController::TYPE,
            [
                'messages' => [
                    'Executing stage: hello',
                    'hello \'World\' \'World\'',
                ],
            ],
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function iSeeBadRequestAfterCallRunTaskEndpoint(AcceptanceTester $I): void
    {
        $I->sendApiPost(
            static::ENDPOINT,
            'hello:world',
            RunTaskController::TYPE,
            [
                'world' => 'World',
            ],
        );

        $I->seeErrorApiResponse(
            Response::HTTP_BAD_REQUEST,
            ['Invalid request. Parameter `somebody` is missing.'],
            Response::HTTP_BAD_REQUEST,
            (string)Response::HTTP_BAD_REQUEST,
        );
    }
}
