# spryker-sdk
nano ~/.bashrc
add alias spryker-sdk='php {path to sdk}/bin/console'
source ~/.bashrc
can run just spryker-sdk -h from project directory

All tasks should run from project root directory

rm {SDK}/var/data.db && rm {SDK}/.ssdk && rm {SDK}/.ssdk.log && spryker-sdk init:sdk && spryker-sdk init:project
