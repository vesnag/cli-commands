# Task Scheduler

This repository is a collection of custom Symfony CLI commands created for practice and experimentation to explore Symfony's Console component within the Symfony framework.

## RepoStatusBundle

The `RepoStatusBundle` offers the `app:check-repository-status` command. It can be run via:

```
php bin/console app:check-repository-status
```

### Configuration
Variables can be configured via the .env file. The following variables are used:

```
GITHUB_OWNER=''
GITHUB_REPO=''
GITLAB_OWNER=''
GITLAB_REPO=''
BITBUCKET_OWNER=''
BITBUCKET_REPO=''
```
---

This repository is still a work in progress. The idea is simple: gather some information from the repository and post the status to Slack. It's more of a playground for practicing with the Symfony framework.
