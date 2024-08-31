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
#VCS Clients Configuration
GITHUB_OWNER=github_owner
GITHUB_REPO=github_repo
GITLAB_OWNER='gitlab_owner
GITLAB_REPO=gitlab_repo
BITBUCKET_OWNER=bitbucket_owner
BITBUCKET_REPO=bitbucker_repo
GITHUB_API_BASE_URL=https://api.github.com

# Slack Bot User OAuth Token
SLACK_BOT_TOKEN=xoxp-******

# Slack Configuration
SLACK_CHANNEL=your-channel
```
---

This repository is still a work in progress. The idea is simple: gather some information from the repository and post the status to Slack. It's more of a playground for practicing with the Symfony framework.
