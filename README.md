# Alerter

A proof-of-concept container image that uses the Portainer API to check the status of resources (right now just environments, but could be extended to stacks, containers, etc) 
and "alert" when they are down. 

## Setup

1. Build the image.
2. Create a config file - refer to `config.php.sample` for the format and current options.
3. Create a container with the image that mounts your config file at `/config/config.php` within the container's file system.
4. Start the container.
5. Watch the container logs to see the output.

# Limitations

As this is just a proof-of-concept, no actual alerting is performed, but you can see via the log messages where alerting would take place.

The check code is run every minute via cron. There is no current way to adjust this besides editing the crontab directly.

This is a proof-of concept. It may or may not work. Do not use it in production.
