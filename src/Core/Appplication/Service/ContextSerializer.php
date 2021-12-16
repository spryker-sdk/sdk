<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class ContextSerializer
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return string
     */
    public function serialize(ContextInterface $context): string
    {
        $data = [
            'tags' => $context->getTags(),
            'resolved_values' => $context->getResolvedValues(),
            'messages' => array_map(function (MessageInterface $message): array {
                return [
                    'message' => $message->getMessage(),
                    'verbosity' => $message->getVerbosity(),
                ];
            }, $context->getMessages()),
            'violation_reports' => ['list of violation report files'],
        ];

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $content
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function deserialize(string $content): ContextInterface
    {
        $context = new Context();
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (array_key_exists('tags', $data) && is_array($data['tags'])) {
            $context->setTags($data['tags']);
        }

        if (array_key_exists('resolved_values', $data) && is_array($data['resolved_values'])) {
            $context->setResolvedValues($data['resolved_values']);
        }

        if (array_key_exists('messages', $data) && is_array($data['messages'])) {
            $context->setMessages(array_map(function (array $messageData): Message {
                return new Message($messageData['message'], $messageData['verbosity'] ?? MessageInterface::INFO);
            }, $data['messages']));
        }

        return $context;
    }
}
