# Task Scheduler

This repository is a collection of custom Symfony CLI commands created for practice and experimentation to explore Symfony's Console component within the Symfony framework.

## RepoStatusBundle

The `RepoStatusBundle` offers the `app:check-repository-status` command. It can be run via:

```
php bin/console app:check-repository-status
```

### Terminal Output
<img width="802" alt="Screenshot 2024-09-03 at 11 55 34" src="https://github.com/user-attachments/assets/fd95c354-5211-4a5a-8a9c-fef82a49052b">

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
---

This repository is still a work in progress. The idea is simple: gather some information from the repository and post the status to Slack. It's more of a playground for practicing with the Symfony framework.
