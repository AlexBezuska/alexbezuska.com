#!/bin/bash
dotenv -e .env -- bash -c '

rsync -avz dest/ ${SERVER_USER}@${SERVER_IP}:${SERVER_PATH}

'
