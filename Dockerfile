FROM php:8.2-cli
COPY src /usr/src/alerter
COPY ./entrypoint.sh /
WORKDIR /usr/src/alerter
RUN apt-get update && apt-get install -y cron
RUN touch /var/log/cron.log
RUN (crontab -l ; echo "* * * * * /usr/local/bin/php /usr/src/alerter/check.php > /proc/1/fd/1 2>/proc/1/fd/2") | crontab
RUN chmod +x /entrypoint.sh
CMD ["bash", "/entrypoint.sh"]
