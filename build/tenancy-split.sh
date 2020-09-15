#!/usr/bin/env bash

git subsplit init git@github.com:tenancy/tenancy.git

# Tenancy.
git subsplit publish --heads="master" src/Tenancy:git@github.com:tenancy/framework.git

# Affects
git subsplit publish --heads="master" src/Affects/Broadcasts:git@github.com:tenancy/affects-broadcasts.git
git subsplit publish --heads="master" src/Affects/Cache:git@github.com:tenancy/affects-cache.git
git subsplit publish --heads="master" src/Affects/Configs:git@github.com:tenancy/affects-configs.git
git subsplit publish --heads="master" src/Affects/Connections:git@github.com:tenancy/affects-connections.git
git subsplit publish --heads="master" src/Affects/Filesystems:git@github.com:tenancy/affects-filesystems.git
git subsplit publish --heads="master" src/Affects/Logs:git@github.com:tenancy/affects-logs.git
git subsplit publish --heads="master" src/Affects/Mails:git@github.com:tenancy/affects-mails.git
git subsplit publish --heads="master" src/Affects/Models:git@github.com:tenancy/affects-models.git
git subsplit publish --heads="master" src/Affects/Routes:git@github.com:tenancy/affects-routes.git
git subsplit publish --heads="master" src/Affects/URLs:git@github.com:tenancy/affects-urls.git
git subsplit publish --heads="master" src/Affects/Views:git@github.com:tenancy/affects-views.git

# Lifecycle Hooks
git subsplit publish --heads="master" src/Hooks/Database:git@github.com:tenancy/hooks-database.git
git subsplit publish --heads="master" src/Hooks/Hostname:git@github.com:tenancy/hooks-hostname.git
git subsplit publish --heads="master" src/Hooks/Migration:git@github.com:tenancy/hooks-migration.git

# Identification drivers
git subsplit publish --heads="master" src/Identification/Console:git@github.com:tenancy/identification-driver-console.git
git subsplit publish --heads="master" src/Identification/Environment:git@github.com:tenancy/identification-driver-environment.git
git subsplit publish --heads="master" src/Identification/Http:git@github.com:tenancy/identification-driver-http.git
git subsplit publish --heads="master" src/Identification/Queue:git@github.com:tenancy/identification-driver-queue.git

# Database drivers
git subsplit publish --heads="master" src/Database/Mysql:git@github.com:tenancy/db-driver-mysql.git
git subsplit publish --heads="master" src/Database/Sqlite:git@github.com:tenancy/db-driver-sqlite.git

# Testing (Abandoned)
# git subsplit publish --heads="master" src/Testing:git@github.com:tenancy/testing.git

rm -rf .subsplit/
