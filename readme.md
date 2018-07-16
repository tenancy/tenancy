![tenancy logo](https://avatars3.githubusercontent.com/u/33319474?s=50&v=4)

This is the successor of [hyn/multi-tenant](https://github.com/hyn/multi-tenant). It is still
in development. Feel free to show support by starring the project
following progress via [twitter](https://twitter.com/laraveltenancy) and
backing its development over at [OpenCollective](https://opencollective.com/tenancy).

[![CircleCI](https://circleci.com/gh/tenancy/framework.svg?style=svg)](https://circleci.com/gh/tenancy/framework)
[![codecov](https://codecov.io/gh/tenancy/framework/branch/master/graph/badge.svg)](https://codecov.io/gh/tenancy/framework)

# development

This repository is used for developing all tenancy packages. In case you're looking for the
related issues, check the [tenancy/tenancy](https://github.com/tenancy/tenancy) repository
instead.

Contributors need to use this repository for implementing code. All other repositories
are READ-ONLY and overwritten on each subsplit push.

# subsplitting

Subsplitting is the ability to push code from subdirectories into their own repository.
By maintaining one monolith for the framework we can easily build, test and deploy
features and refactorings.

> The actual subsplitting process is reserved to package maintainers, as you'd
need write access to the repositories and master branches for that.

- install [subsplit](https://github.com/dflydev/git-subsplit)
- run `bash build/tenancy-split.sh`
