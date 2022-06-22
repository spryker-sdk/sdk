### Workflow SDK tool

One or more workflows can be initialized for a project and run via `sdk:workflow:run` command. Workflows are defined in workflow.yaml files either in `config/packages` or in the configuration of extension bundles. Project is limited to the workflows specified during initialization. If none was specified, any workflow can be started by providing it's name to the `sdk:workflow:run` command. Two identical top-level workflows can't run inside the same project.

#### Relevant commands:
```bash
  #  Generate svg image for concrete workflow
  spryker-sdk workflow:dump {workflowName}  | dot -Tsvg -o graph.svg
  # Init project settings with workflow
  spryker-sdk init:sdk:project --workflow={workflowName} --workflow={workflowName} # If you init workflows for the project you can use only these workflows.
  # Run workflow process.
  spryker-sdk sdk:workflow:run {workflowName}
```

#### Behavior of the workflow can be configured by providing specific keys to the metadata of the workflow:
- `transitionResolver`: `sdk:workflow:run` See example below `service` should implement `\SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface`.
- `allowToFail: true`: `sdk:workflow:run` will set the text place if task failed.
- `re-run: true`: `sdk:workflow:run` will run workflow many times when the current one has finished.
- `run: single`: `sdk:workflow:run` will only run single transition and exit. If omitting this setting, task will run available transitions one by one asking which one to run if multiple possible variants exist.
- `before: service_name`: service `service_name` should implement `\SprykerSdk\Sdk\Extension\Dependency\Events\WorkflowEventHandlerInterface` and will be called before transition occurs.
- `after: service_name`: service `service_name` should implement `\SprykerSdk\Sdk\Extension\Dependency\Events\WorkflowEventHandlerInterface` and will be called after transition occurs.
- `guard: service_name`: service `service_name` should implement `\SprykerSdk\Sdk\Extension\Dependency\Events\WorkflowGuardEventHandlerInterface` and will be called to determine if transition is available.
- `task: task_name`: task `task_name` will be executed inside the transition and transition may stop depending on it's result.
- `workflowBefore: workflow_name`: workflow `workflow_name` will run inside the transition and should end before proceeding to the task execution.
- `workflowAfter: workflow_name`: workflow `workflow_name` will run inside the transition after task is executed and should end before finishing the transition.

#### Example workflow definition in `workflow.yaml`:
```yaml
framework:
  workflows:
    hello_world:
      type: workflow # (state_machine) see the docs at https://symfony.com/doc/current/workflow/workflow-and-state-machine.html
      marking_store:
        type: method
        property: status
      metadata:
        re-run: true # Possibility to re-run workflow when the current one is finished
        guard: guard_service_name # checks transition availability for all transitions
        before: handler_service_name # runs before every transition
        run: single # sdk:workflow:run will only run single transition and exit
        after: handler_service_name # runs after every transition
      supports:
        - SprykerSdk\SdkContracts\Entity\WorkflowInterface
      initial_marking: start
      places:
        - start
        - done
      transitions:
        go:
          from: start
          to: done
          metadata: # in order of execution
            transitionResolver: # Resolver needs for resolve next transition
              service: transition_boolean_resolver # Resolver service id. The resolver should implement `\SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface`
              settings:
                  failed: bye # transition name for filed result
                  successful: world # transition name for successful result
            allowToFail: true # Can go to next place if task failed
            guard: guard_service_name # checks this transition availability
            before: handler_service_name # runs before this transition
            workflowBefore: hello_php # workflow starts and should end before proceeding to the task
            task: hello:world # task is executed inside the transition
            workflowAfter: hello_php # workflow starts and should end before finishing the transition
            after: handler_service_name # runs after this transition
    hello_php: # Minimal workflow definition
      type: state_machine
      marking_store:
        type: method
        property: status
      supports:
        - SprykerSdk\SdkContracts\Entity\WorkflowInterface
      initial_marking: start
      places:
        - start
        - done
      transitions:
        go:
          from: start
          to: done
```
