# alexbezuska.com

This repo is on:
[GitLab](https://gitlab.com/AlexBezuska/alexbezuska.com) | [GitHub](https://github.com/AlexBezuska/alexbezuska.com)

Welcome to my website! ... well the git repo for it anyway! Wow you're a nerd aren't you? No problem, I am into some pretty nerdy stuff too like making video games!

More to come... when I feel like it.



## Bugs

- logo missing on firefox
- fonts are extremely thin in firefox/ubuntu
- embedded youtube videos dont work
```
Code for when I get around to fixing this:
<iframe width="560" height="315" src="https://www.youtube.com/embed/iwpL5AaKIQo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
```


## Notes:

data.json is a generated file, don't edit it directly.

## To build:

1. run `npm build` from the terminal, then files will magically appear in the `dest` folder.


### To preview it

Run `npm start` and it will do a python thing and let you view the site at port 3000





# Deployment Script

This repository contains a deployment script that uploads the contents of the `dest/` directory to your server using `rsync`. The script leverages environment variables to securely manage server credentials.

## Prerequisites

You need to have the following installed:
- Node.js
- The `dotenv` Node package
- The `rsync` command-line utility

### Install Dependencies

To install `dotenv` as a global package:

```bash
npm install -g dotenv-cli
```

Make sure `rsync` is installed on your system. It should be available on most Unix-based systems by default.

## .env File Setup

Create a `.env` file in the root of your project with the following variables:

```plaintext
SERVER_USER=your_server_user
SERVER_IP=your_server_ip
SERVER_PATH=path_to_your_server_directory
```

- `SERVER_USER`: Your SSH username for the server.
- `SERVER_IP`: The IP address of your server.
- `SERVER_PATH`: The path on your server where you want to deploy your site.

## Deploy Script

The deployment script `deploy.sh` is responsible for syncing the contents of the `dest/` directory to your server.

```bash
#!/bin/bash
dotenv -e .env -- bash -c '
rsync -avz dest/ ${SERVER_USER}@${SERVER_IP}:${SERVER_PATH}
'
```

## Usage

To deploy your site, simply run the following command:

```bash
bash deploy.sh
```

This will sync the contents of the `dest/` directory to your server using the credentials defined in the `.env` file.

Make sure your `dest/` directory contains your built site before running the deploy script.
