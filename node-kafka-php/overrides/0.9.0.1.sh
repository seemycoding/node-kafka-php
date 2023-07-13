#!/bin/bash -e
echo "Removing 'listeners' from server.properties pre-bootstrap"
sed -i -e '/^listeners=/d' "$KAFKA_HOME/config/server.properties"
