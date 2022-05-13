### Workflow SDK tool

```bash
  #  Generate svg image for concrete workflow
  spryker-sdk workflow:dump {workflowName}  | dot -Tsvg -o graph.svg
  # Init project settings with workflow
  spryker-sdk init:sdk:project --workflow={workflowName}
  # Run workflow process.
  spryker-sdk sdk:workflow:run
```
