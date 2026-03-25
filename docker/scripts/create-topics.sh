#!/bin/bash

set -e

BROKER="kafka:9092"

echo "Waiting for Kafka..."

until kafka-topics --bootstrap-server $BROKER --list > /dev/null 2>&1; do
  echo "Kafka not ready yet..."
  sleep 2
done

echo "Creating topics..."

kafka-topics --create \
  --if-not-exists \
  --topic users.events \
  --bootstrap-server $BROKER \
  --partitions 3 \
  --replication-factor 1

kafka-topics --create \
  --if-not-exists \
  --topic users.retry \
  --bootstrap-server $BROKER \
  --partitions 3 \
  --replication-factor 1

kafka-topics --create \
  --if-not-exists \
  --topic users.dlq \
  --bootstrap-server $BROKER \
  --partitions 1 \
  --replication-factor 1

echo "Topics created successfully"
