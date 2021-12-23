<?php

namespace SprykerSdk\Sdk\Tests;

use Codeception\Actor;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends Actor
{
    use _generated\UnitTesterActions;

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function createTask(): TaskInterface
    {
        return new Task(
            'task',
            'short description',
            [],
            new Lifecycle(new InitializedEventData(), new UpdatedEventData(), new RemovedEventData()),
            '0.0.1'
        );
    }
}
