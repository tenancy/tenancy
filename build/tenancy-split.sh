#!/usr/bin/env bash

if [ $# -gt 0 ]
    then branch="$1"
    else branch="master"
fi

git subsplit init git@github.com:tenancy/tenancy.git

# Framework
git subsplit publish "
    src/Tenancy:git@github.com:tenancy/framework.git
" --heads="$branch"

# Affects
git subsplit publish "
    src/Affects/Broadcasts:git@github.com:tenancy/affects-broadcasts.git
    src/Affects/Cache:git@github.com:tenancy/affects-cache.git
    src/Affects/Configs:git@github.com:tenancy/affects-configs.git
    src/Affects/Connections:git@github.com:tenancy/affects-connections.git
    src/Affects/Filesystems:git@github.com:tenancy/affects-filesystems.git
    src/Affects/Logs:git@github.com:tenancy/affects-logs.git
    src/Affects/Mails:git@github.com:tenancy/affects-mails.git
    src/Affects/Models:git@github.com:tenancy/affects-models.git
    src/Affects/Routes:git@github.com:tenancy/affects-routes.git
    src/Affects/URLs:git@github.com:tenancy/affects-urls.git
    src/Affects/Views:git@github.com:tenancy/affects-views.git
" --heads="$branch"

# Lifecycle Hooks
git subsplit publish "
    src/Hooks/Database:git@github.com:tenancy/hooks-database.git
    src/Hooks/Hostname:git@github.com:tenancy/hooks-hostname.git
    src/Hooks/Migration:git@github.com:tenancy/hooks-migration.git
" --heads="$branch"

# Identification drivers
git subsplit publish "
    src/Identification/Console:git@github.com:tenancy/identification-driver-console.git
    src/Identification/Environment:git@github.com:tenancy/identification-driver-environment.git
    src/Identification/Http:git@github.com:tenancy/identification-driver-http.git
    src/Identification/Queue:git@github.com:tenancy/identification-driver-queue.git
" --heads="$branch"

# Database drivers
git subsplit publish "
    src/Database/Mysql:git@github.com:tenancy/db-driver-mysql.git
    src/Database/Sqlite:git@github.com:tenancy/db-driver-sqlite.git
" --heads="$branch"

# Testing (Abandoned)
# git subsplit publish "
#     src/Testing:git@github.com:tenancy/testing.git
# " --heads="$branch"

rm -rf .subsplit/
