#!/usr/bin/env bash

set -e

if [ "$1" = 'supervisord' ] || [ "$1" = '' ]; then
    if [ ! -f "/node_status_inited" ]; then
        bash /usr/local/bin/init.sh

        if [ -f /opt/setup.sh ]; then
           bash /opt/setup.sh
        fi
    fi

    exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf $1
fi

exec "$@"
