# Spryker SDK Settings

Spryker SDK creates three types of settings sdk, local(private) and shared.

### Shared settings

This is a project setting.
Shared settings is generated on the project init step and placed in the `.ssdk/setting` file in the target project.
This type of settings shares across the team(is not in .gitignore).

### Local(private) setting

This is a project setting.
Local settings also generated on the project init step and placed in the `.ssdk/setting.local` file in the target project.
Local setting contain only private settings and should not be shared.

### SDK setting

This is SDK setting.
SDK settings generates on the SDK init step and exists in the database.

### Setting inheritance
```
SDK -> Shared -> Local
```
- SDK setting can be overwritten by Shared or Local setting.
- Shared setting can be overwritten by Local setting.
