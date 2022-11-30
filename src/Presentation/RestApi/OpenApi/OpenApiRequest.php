<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\OpenApi;

use InvalidArgumentException;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use Symfony\Component\HttpFoundation\Request;

class OpenApiRequest
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected Request $request;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getData(): array
    {
        if (!$this->request->request->has(OpenApiField::DATA)) {
            throw new InvalidArgumentException(sprintf('Request field `%s` doesn\'t exist', OpenApiField::DATA));
        }

        return $this->request->request->all(OpenApiField::DATA);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getAttributes(): array
    {
        $data = $this->getData();

        if (!isset($data[OpenApiField::ATTRIBUTES]) || !is_array($data[OpenApiField::ATTRIBUTES])) {
            throw new InvalidArgumentException(sprintf('Request field `%s` doesn\'t exist or has invalid value', OpenApiField::ATTRIBUTES));
        }

        return $data[OpenApiField::ATTRIBUTES];
    }

    /**
     * @param string $attributeName
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAttribute(string $attributeName, $default = null)
    {
        $attributes = $this->getAttributes();

        return array_key_exists($attributeName, $attributes) ? $attributes[$attributeName] : $default;
    }

    /**
     * @param string $attributeName
     *
     * @return bool
     */
    public function hasAttribute(string $attributeName): bool
    {
        $attributes = $this->getAttributes();

        return array_key_exists($attributeName, $attributes);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getId(): string
    {
        $data = $this->getData();

        if (!isset($data[OpenApiField::ID])) {
            throw new InvalidArgumentException(sprintf('Data field `%s` is not found', OpenApiField::ID));
        }

        return (string)$data[OpenApiField::ID];
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getType(): string
    {
        $data = $this->getData();

        if (!isset($data[OpenApiField::TYPE])) {
            throw new InvalidArgumentException(sprintf('Data field `%s` is not found', OpenApiField::TYPE));
        }

        return (string)$data[OpenApiField::TYPE];
    }
}
