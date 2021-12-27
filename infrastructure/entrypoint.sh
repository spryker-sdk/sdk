#!/bin/bash

pattern='.*php:'
if [[ "$@" =~ $pattern ]]; then
     /bin/bash -c "/data/bin/console $@"
  else
     /bin/bash
fi
