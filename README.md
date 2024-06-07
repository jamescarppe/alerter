# Alerter

A proof-of-concept container image that uses the Portainer API to check the status of resources (right now just environments, but could be extended to stacks, containers, etc) 
and "alert" when they are down. 

## Setup

1. Build the image.
2. Create a config file - refer to `config.php.sample` for the format and current options.
3. Create a container with the image that mounts your config file at `/config/config.php` within the container's file system.
4. Start the container.
5. Watch the container logs to see the output.

# Example

For a config file monitoring 6 environments that looks like this:

```
$ENVIRONMENTS_CONFIG = [
        2 => [
                "sendAlerts" => false,
        ],
        3 => [
                "sendAlerts" => true,
                "alertRecipient" => "bruce@wayneenterprises.com",
        ],
        4 => [
                "sendAlerts" => true,
                "alertRecipient" => "alfred@wayneenterprises.com",
                "checkinThreshold" => 30,
        ],
        5 => [
                "sendAlerts" => true,
                "alertRecipient" => "bruce@wayneenterprises.com",
        ],
        8 => [
                "sendAlerts" => true,
                "alertRecipient" => "bruce@wayneenterprises.com",
        ],
        18 => [
                "sendAlerts" => true,
                "alertRecipient" => "alfred@wayneenterprises.com",
        ],
];
```

If environment 4 (an Edge environment) was unreachable, and environment 18 (a Docker Standalone environment) was offline, you would see the following in the logs:

```
[2024-06-07 02:10:01] [*] Checking for environment 2 in alerter config
[2024-06-07 02:10:01] [*] Environment exists in config, proceeding
[2024-06-07 02:10:01] [*] Alerting not enabled, moving on
[2024-06-07 02:10:01] [*] Checking for environment 3 in alerter config
[2024-06-07 02:10:01] [*] Environment exists in config, proceeding
[2024-06-07 02:10:01] [*] Alerting enabled, checking status
[2024-06-07 02:10:01] [*] Environment 3 reports as up, no action needed
[2024-06-07 02:10:01] [*] Checking for environment 4 in alerter config
[2024-06-07 02:10:01] [*] Environment exists in config, proceeding
[2024-06-07 02:10:01] [*] Alerting enabled, checking status
[2024-06-07 02:10:01] [!] Edge environment 4 has not checked in within 30 seconds, sending email to bruce@wayneenterprises.com
[2024-06-07 02:10:01] [*] Checking for environment 5 in alerter config
[2024-06-07 02:10:01] [*] Environment exists in config, proceeding
[2024-06-07 02:10:01] [*] Alerting enabled, checking status
[2024-06-07 02:10:01] [*] Environment 5 reports as up, no action needed
[2024-06-07 02:10:01] [*] Checking for environment 8 in alerter config
[2024-06-07 02:10:01] [*] Environment exists in config, proceeding
[2024-06-07 02:10:01] [*] Alerting enabled, checking status
[2024-06-07 02:10:01] [*] Environment 8 reports as up, no action needed
[2024-06-07 02:10:01] [*] Checking for environment 18 in alerter config
[2024-06-07 02:10:01] [*] Environment exists in config, proceeding
[2024-06-07 02:10:01] [*] Alerting enabled, checking status
[2024-06-07 02:10:01] [!] Environment 18 reports as DOWN, sending email to alfred@wayneenterprises.com
```

# Limitations

As this is just a proof-of-concept, no actual alerting is performed, but you can see via the log messages where alerting would take place.

The check code is run every minute via cron. There is no current way to adjust this besides editing the crontab directly.

This is a proof-of concept. It may or may not work. Do not use it in production.
