# Profiler

Profiler is available only in the dev environment.

### How to enable the profiler
To enable the profiler, add `SDK_PROFILER_ENABLED=1` into the `.env.dev.local` configuration.

### Usage
After you enabled the profiler, the data is collected into the `<project-dir>/var/profiler` directory.
Each file represents the particular SDK process call.
You can manually clean or manage the files in the directory.

### Viewing the profiler data
To view the profiler data, start the viewer server with this command:

```shell
#start the server listening to the 8000 port
spryker-sdk --mode=docker sdk --profiler 8000
```
Now, you can find the profiler data at `http://127.0.0.1:8000`.
