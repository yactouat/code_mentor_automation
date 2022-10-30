#!/bin/bash
docker exec -t udacity_sl_automation-php-1 bash -c "XDEBUG_MODE=off /var/www/vendor/bin/phpunit /var/www/tests --colors --testdox"