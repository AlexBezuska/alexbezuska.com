#!/bin/bash

# Load env vars manually
SERVER_USER="fufroom"
SERVER_IP="192.241.148.163"
SERVER_PATH="/var/www/alexbezuska.com/"

# Sync files
rsync -avz dest/ "${SERVER_USER}@${SERVER_IP}:${SERVER_PATH}"
