# Troubleshooting

## `spryker-sdk` command not found
`spryker-sdk` is an alias that uses {sdk_folder}/bin/spryker-sdk.sh script.
If `spryker-sdk` alias doesn't exist, please add it manually to your shell rc file. Depending on your shell it can be `~/.bashrc`, `~/.zshrc`, `~/.profile`, etc.".
`echo alias spryker-sdk={sdk_folder}/bin/spryker-sdk.sh`
Run `source {rc file}` to load `spryker-sdk` alias for the current session.
