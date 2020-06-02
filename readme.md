# ![tenancy logo](https://avatars3.githubusercontent.com/u/33319474?s=25&v=4) Tenancy for Laravel

Enabling awesome Software as a Service with the Laravel framework.

This is the successor of [hyn/multi-tenant](https://github.com/tenancy/multi-tenant). 
Feel free to show support by starring the project
following progress via [twitter](https://twitter.com/laraveltenancy) and
backing its development over at [OpenCollective](https://opencollective.com/tenancy).

![Tests](https://github.com/tenancy/tenancy/workflows/Tests/badge.svg)
![Code Style](https://github.com/tenancy/tenancy/workflows/Code%20Style/badge.svg)
![Subsplit](https://github.com/tenancy/tenancy/workflows/Subsplit/badge.svg)
[![codecov](https://codecov.io/gh/tenancy/tenancy/branch/master/graph/badge.svg)](https://codecov.io/gh/tenancy/tenancy)

> Before you start, we highly recommend you to read the extensive [online documentation](https://tenancy.dev/docs/tenancy/1.x).

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

- [database drivers](https://tenancy.dev/docs/tenancy/1.x/database-drivers)
- [identification drivers](https://tenancy.dev/docs/tenancy/1.x/identification-drivers)
- [affects](https://tenancy.dev/docs/tenancy/1.x/affects)
- [hooks](https://tenancy.dev/docs/tenancy/1.x/hooks)

# Contributions

This repository is used for developing all tenancy packages.

Contributors need to use this repository for implementing code. All other repositories
are READ-ONLY and overwritten on each subsplit push.

Please read our [Governance documentation](https://tenancy.dev/docs/governance/tenancy) in
case you'd like to get involved.

