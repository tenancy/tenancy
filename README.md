# ![Tenancy logo](https://avatars3.githubusercontent.com/u/33319474?s=25&v=4) Tenancy Ecosystem (for Laravel)

Enabling awesome Software as a Service with the Laravel framework.

This is the successor of [hyn/multi-tenant](https://github.com/tenancy/multi-tenant). 

Feel free to show support by starring the project
following progress via [twitter](https://twitter.com/tenancydev) and
backing its development over at [OpenCollective](https://opencollective.com/tenancy) or [GitHub Sponsors](https://github.com/sponsors/tenancy).

![Tests](https://github.com/tenancy/tenancy/actions/workflows/tests.yaml/badge.svg)
<!-- ![Code Style](https://github.com/tenancy/tenancy/workflows/Code%20Style/badge.svg) -->
[![Code Coverage](https://codecov.io/gh/tenancy/tenancy/branch/master/graph/badge.svg)](https://codecov.io/gh/tenancy/tenancy)
![Subsplit](https://github.com/tenancy/tenancy/workflows/Subsplit/badge.svg)

> Before you start, we highly recommend you to read the extensive [online documentation](https://tenancy.dev/docs/tenancy/2.x).

# Installation

To give this package a spin, install all components at once:

```bash
$ composer require tenancy/tenancy
```

Otherwise make sure to selectively install the components you need and at least the framework:

```bash
$ composer require tenancy/framework
```

After that you'll need to decide on and configure:

- [Database drivers](https://tenancy.dev/docs/tenancy/2.x/database-drivers)
- [Identification drivers](https://tenancy.dev/docs/tenancy/2.x/identification-drivers)
- [Affects](https://tenancy.dev/docs/tenancy/2.x/affects)
- [Hooks](https://tenancy.dev/docs/tenancy/2.x/hooks)

# Contributions

This repository is used for developing all tenancy packages.

Contributors need to use this repository for implementing code. All other repositories
are READ-ONLY and overwritten on each subsplit push.

Please read our [Governance documentation](https://tenancy.dev/docs/governance/tenancy) in
case you'd like to get involved.

## Local Testing
Testing the ecosystem locally is possible when:
- You have Docker (and docker-compose) installed
- You have Bash installed

When you have those requirements, you can simply run `./test` in order to run the test in Docker containers. By default they will run the most recent versions of all our dependencies 
