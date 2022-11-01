#!/bin/bash

# removing nginx logs
sudo rm data/logs/nginx/access.log
sudo rm data/logs/nginx/error.log

# removing app logs
sudo rm data/logs/php/cli.log
sudo rm data/logs/php/web.log
sudo rm data/logs/php/db.log

# removing tests logs
sudo rm tests/fixtures/logs/php/cli.log
sudo rm tests/fixtures/logs/php/web.log
sudo rm tests/fixtures/logs/php/db.log

# removing uploaded csvs
sudo rm data/csv/*.csv

# removing sessions
sudo rm data/sessions/sess_*