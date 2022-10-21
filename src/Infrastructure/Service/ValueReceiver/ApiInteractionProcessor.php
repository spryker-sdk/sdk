<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver;

use Exception;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;
use SprykerSdk\Sdk\Infrastructure\Event\RequestDataReceiverInterface;

class ApiInteractionProcessor implements InteractionProcessorInterface, RequestDataReceiverInterface
{
    /**
     * @var array
     */
    protected array $data;

    /**
     * @param array $data
     *
     * @return void
     */
    public function setRequestData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return !empty($this->data[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key];
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface $receiverValue
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function receiveValue(ReceiverValueInterface $receiverValue)
    {
        $key = '';
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new Exception('Request is wrong.');
    }
}
