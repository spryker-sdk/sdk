<?php

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use Symfony\Component\Workflow\Registry;

class ProjectWorkflow
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \Symfony\Component\Workflow\Registry|null
     */
    protected ?Registry $workflows;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected WorkflowRepositoryInterface $workflowRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \Symfony\Component\Workflow\Registry $workflows
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface $workflowRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        Registry $workflows,
        WorkflowRepositoryInterface $workflowRepository
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->workflows = $workflows;
        $this->workflowRepository = $workflowRepository;
    }

    public function echo()
    {

//        $f = new Workflow('saddsvsvdcsadcsdc',[],'default');
//        $this->workflowRepository->save($f);
//
        $projectWorkflow = $this->workflowRepository->findOne($this->projectSettingRepository->getOneByPath('project_id'));
        $workFlow = $this->workflows->get($projectWorkflow, 'default');
//        $this->workflowRepository->save($f);
//        $workFlow->apply($f, 'fix');
        $fa = $workFlow->getEnabledTransitions($projectWorkflow)[0];
//        $workFlow->apply($projectWorkflow, 'checking');
        $this->workflowRepository->save($projectWorkflow);
        \var_dump($projectWorkflow, $fa);
//        \var_dump($workFlow->getEnabledTransitions($projectWorkflow));
//        \var_dump($workFlow->can($projectWorkflow,'check'));
        die;
    }
}
