<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\ApiDoc;

use Nelmio\ApiDocBundle\Describer\DescriberInterface;
use OpenApi\Annotations\OpenApi;

class SdkInitSdkDescriber implements DescriberInterface
{
    /**
     * @param \OpenApi\Annotations\OpenApi $api
     *
     * @return void
     */
    public function describe(OpenApi $api): void
    {
    }
}
