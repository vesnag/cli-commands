# Task Scheduler

This repository is a collection of custom Symfony CLI commands created for practice and experimentation to explore Symfony's Console component within the Symfony framework.

## RepoStatusBundle

The `RepoStatusBundle` offers the `app:check-repository-status` command. It can be run via:

```
php bin/console app:check-repository-status
```

### Terminal Output
<img width="816" alt="Screenshot 2024-09-04 at 13 28 18" src="https://github.com/user-attachments/assets/b0f6b81c-bf27-41a3-a4b4-f5aaf2364aa2">

### Slack Message
<img width="583" alt="Screenshot 2024-09-03 at 11 53 22" src="https://github.com/user-attachments/assets/6200e17f-5696-4317-a494-5f47d7717039">


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

## Castor Tasks

This repository also includes tasks for static analysis, code formatting, and unit testing using [Castor](https://github.com/jolicode/castor). To run these tasks, use the following commands:

* Run static analysis and code formatting: `castor task:code:validate`
* Run unit tests: `castor task:code:run-tests`
* Run both validation and unit tests: `castor task:code:validate-and-test`
* Check repository status `castor task:symfony:check-repository-status`


---

This repository is still a work in progress and will always be. The idea is simple: gather some information from the repository and post the status to Slack. It's more of a playground for practicing with the Symfony framework.
