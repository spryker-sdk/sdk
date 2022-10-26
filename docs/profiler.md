# Profiler

Profiler available only in dev environment.

### How to enable the profiler
Need to add `SDK_PROFILER_ENABLED=1` into the `.env.dev.local` configuration.

### The profiler usage
After you enabled the profiler the data will be collected into the `<project-dir>/var/profiler` directory.
Each file represents the particular sdk process call.
You can manually clean or manage this files in directory.

### Viewing and analyze the profiler data
To view the profiler data you need to start viewer server by the command
```shell
#start the server listening the 8000 port
spryker-sdk --mode=docker sdk --profiler 8000
```
Now you can find the profiler data in `http://127.0.0.1:8000`
