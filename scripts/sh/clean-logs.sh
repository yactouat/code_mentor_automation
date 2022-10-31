#!/bin/bash

# removing nginx logs
sudo rm data/logs/nginx/access.log
sudo rm data/logs/nginx/error.log

# removing app logs
sudo rm data/logs/php/cli.log
sudo rm data/logs/php/web_app.log
sudo rm data/logs/php/db.log

# removing tests logs
sudo rm tests/fixtures/logs/php/cli.log
sudo rm tests/fixtures/logs/php/web_app.log
sudo rm tests/fixtures/logs/php/db.log