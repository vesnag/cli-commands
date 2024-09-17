# Task Scheduler

This repository is a collection of custom Symfony CLI commands created for practice and experimentation to explore Symfony's Console component within the Symfony framework.

## RepoStatusBundle

The `RepoStatusBundle` offers the `app:check-repository-status` command. It can be run via:

```
php bin/console app:check-repository-status
```

### Terminal Output
<img width="978" alt="Screenshot 2024-09-08 at 14 45 34" src="https://github.com/user-attachments/assets/27dd0b39-e014-4e5c-8467-c0dc7ab9b3b1">

### Slack Message
<img width="489" alt="Screenshot 2024-09-08 at 14 45 53" src="https://github.com/user-attachments/assets/8b2b516e-c803-4056-a2f2-452d41191574">

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
DEFAULT_VCS_AUTHOR=usename

# Slack Bot User OAuth Token
SLACK_BOT_TOKEN=xoxp-******

# Slack Configuration
SLACK_CHANNEL=your-channel
SLACK_API_BASE_URL=https://slack.com/api
```

## Castor Tasks

This repository also includes tasks for static analysis, code formatting, and unit testing using [Castor](https://github.com/jolicode/castor). To run these tasks, use the following commands:

* Run static analysis and code formatting: `castor code:validate`
* Run unit tests: `castor code:run-tests`
* Run both validation and unit tests: `castor code:validate-and-test`

---

This repository is still a work in progress and will always be. The idea is simple: gather some information from the repository and post the status to Slack. It's more of a playground for practicing with the Symfony framework.
