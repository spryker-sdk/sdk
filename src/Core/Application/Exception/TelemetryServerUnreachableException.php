<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Exception;

use Exception;

/**
 * Must be thrown when destination endpoint is unreachable. For example due to internet connection.
 */
class TelemetryServerUnreachableException extends Exception
{
}
