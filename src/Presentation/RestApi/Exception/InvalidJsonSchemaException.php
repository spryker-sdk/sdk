<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidJsonSchemaException extends Exception
{
    /**
     * @var array<string>
     */
    protected array $details = [];

    /**
     * @var string
     */
    protected string $status;

    /**
     * @param array $details
     * @param int $code
     * @param string $status
     */
    public function __construct(
        array $details = [],
        int $code = Response::HTTP_BAD_REQUEST,
        string $status = ''
    ) {
        $this->details = $details;
        $this->code = $code;
        $this->status = $status;

        parent::__construct($details[0] ?? '', $code);
    }

    /**
     * @return array<string>
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
