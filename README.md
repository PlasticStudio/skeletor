# Skeletor
Base install for all PlasticStudio projects.

## Required
- branch off /develop
- use feature/ or release/ branch names
- merge back into /develop once change is ready to put in master


## Functionality
Describe any noteworthy pieces of functionality specific to this project.

Such as:

For email send functionality, the `From` email address can be set globally in the `.env` file, like so:

`SS_SEND_ALL_EMAILS_FROM="email@goes.here"`

This setting will be retrieved by the `EmailFrom()` method in `SiteConfigExtension` (defaults to `noreply@plasticstudio.co` if not set).


## Key Integrations
Such as integrations with a third party via API, upstream or downstream dependencies, etc.


## Critical Areas
Critical parts of the project, either from a codebase or business perspective (or both).
These are areas of the project that *should* be checked/tested any time a release is deployed.
This could be a detailed release/go-live checklist.


## Major Changes
### eg, deprecated stuff etc
For example "We've removed DMS from the composer file and added it to the project in order to address the Object issue in php7.3, seeing as the plugin cannot be upgraded without significant structural changes."


## Cron Tasks
List any cron tasks that run in the background of this project. These can often get forgotten, especially during migrations between environments.