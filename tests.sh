#!/bin/bash
docker exec -t code_mentor_automation-php-1 bash -c "XDEBUG_MODE=off /var/www/vendor/bin/phpunit /var/www/tests --colors --testdox"