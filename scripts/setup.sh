#!/usr/bin/env bash

SETUP_COMPLETE_CHECK="var/setup_complete"
KEY_DIR="./.host_ssh_keys"
PRIVATE_KEY="$KEY_DIR/id_rsa"
PUBLIC_KEY="$PRIVATE_KEY.pub"
AUTHORIZED_KEYS="$HOME/.ssh/authorized_keys"

if [ -f "$SETUP_COMPLETE_CHECK" ]; then
    echo "Setup already completed!"
    exit 1
fi

if [ -n "$SUDO_USER" ]; then
    echo "Do not run with sudo!"
    exit 1
fi

### Generate SSH key pair for container to host communication

if [ -f "$PRIVATE_KEY" ]; then
    echo "Private key already exists: $PRIVATE_KEY"
    exit 1
fi

mkdir -p "$KEY_DIR"
sudo chown -R "$USER" "$KEY_DIR"

echo "Generating new SSH key pair..."
ssh-keygen -t rsa -b 4096 -f "$PRIVATE_KEY" -N '' -C "key for container to host communication"

sudo chgrp www-data "$PRIVATE_KEY"
sudo chmod 640 "$PRIVATE_KEY"

# Ensure the public key isn't already in authorized_keys and add it if it's not
if grep -q -f "$PUBLIC_KEY" "$AUTHORIZED_KEYS"; then
    echo "Public key already in $AUTHORIZED_KEYS"
else
    echo "Adding public key to $AUTHORIZED_KEYS"
    cat "$PUBLIC_KEY" >> "$AUTHORIZED_KEYS"
fi

### Setup .env.local

touch .env.local

echo "Enter your host username [$USER]:"
read -r HOST_USER
HOST_USER=${HOST_USER:-$USER}
echo "Adding 'HOST_USER=$HOST_USER' to .env.local"
echo "HOST_USER=$HOST_USER" >> .env.local

echo -e "\e[1;36mSetup finished\e[0m"

sudo touch var/setup_complete
