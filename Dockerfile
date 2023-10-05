FROM registry.redhat.io/rhel9/php-80:1-92.1695131429
MAINTAINER Chris Jenkins "chris@chrisj.co.uk"
EXPOSE 8080
COPY . /opt/app-root/src
CMD /bin/bash -c 'php -S 0.0.0.0:8080'
