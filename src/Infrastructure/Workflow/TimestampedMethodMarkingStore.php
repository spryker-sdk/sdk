<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Workflow;

use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;

/**
 * @see \Symfony\Component\Workflow\MarkingStore\MethodMarkingStore
 */
final class TimestampedMethodMarkingStore implements MarkingStoreInterface
{
    /**
     * @var bool
     */
    protected bool $singleState;

    /**
     * @var string
     */
    protected string $property;

    /**
     * @param bool $singleState
     * @param string $property
     */
    public function __construct(bool $singleState = false, string $property = 'marking')
    {
        $this->singleState = $singleState;
        $this->property = $property;
    }

    /**
     * @param object $subject
     *
     * @throws \Symfony\Component\Workflow\Exception\LogicException
     *
     * @return \Symfony\Component\Workflow\Marking
     */
    public function getMarking(object $subject): Marking
    {
        $method = 'get' . ucfirst($this->property);

        if (!method_exists($subject, $method)) {
            throw new LogicException(sprintf(
                'The method "%s::%s()" does not exist.',
                $subject::class,
                $method,
            ));
        }

        $marking = $subject->{$method}();

        if ($marking === null) {
            return new TimestampedMarking();
        }

        if ($this->singleState) {
            $marking = [(string)$marking => time()];
        }

        return new TimestampedMarking($marking);
    }

    /**
     * @param object $subject
     * @param \Symfony\Component\Workflow\Marking $marking
     * @param array $context
     *
     * @throws \Symfony\Component\Workflow\Exception\LogicException
     *
     * @return void
     */
    public function setMarking(object $subject, Marking $marking, array $context = []): void
    {
        $markingData = $marking->getPlaces();

        if ($this->singleState) {
            $markingData = [key($marking) => current($marking)];
        }

        $method = 'set' . ucfirst($this->property);

        if (!method_exists($subject, $method)) {
            throw new LogicException(sprintf(
                'The method "%s::%s()" does not exist.',
                $subject::class,
                $method,
            ));
        }

        $subject->{$method}($markingData, $context);
    }
}
