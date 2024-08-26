#!/bin/sh

# Start the cron daemon
cron

# Keep the container running by tailing the cron log file
tail -f /var/log/cron.log
