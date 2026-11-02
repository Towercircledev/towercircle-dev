#!/usr/bin/env bash
# Daily backup of the database and uploaded files, kept for 14 days.
# Run it by hand once and test a restore (README → "Backups"). Then add to the root crontab (sudo crontab -e):
#   15 2 * * * /var/www/towercircle/deploy/backup.sh >> /var/log/towercircle-backup.log 2>&1
#
# These backups live on the same server. Also keep a copy elsewhere: Lightsail automatic snapshots,
# and/or copy /var/backups/towercircle to S3 (README → "Backups").
set -euo pipefail
umask 077

APP=/var/www/towercircle
DEST=/var/backups/towercircle
STAMP=$(date +%F-%H%M)
mkdir -p "$DEST"

# Read the database settings from .env without running it as a script (passwords with $, quotes or
# spaces would break that, and it would run anything written in .env).
env_value() {
    local line value
    line=$(grep -E "^$1=" "$APP/.env" | tail -n 1 || true)
    value=${line#*=}
    value=${value%$'\r'}
    if [[ "$value" == \"*\" && "$value" == *\" ]]; then value=${value:1:-1}; value=${value//\\\"/\"}; fi
    if [[ "$value" == \'*\' && "$value" == *\' ]]; then value=${value:1:-1}; fi
    printf '%s' "$value"
}
DB_HOST=$(env_value DB_HOST); DB_PORT=$(env_value DB_PORT)
DB_DATABASE=$(env_value DB_DATABASE); DB_USERNAME=$(env_value DB_USERNAME); DB_PASSWORD=$(env_value DB_PASSWORD)
if [ -z "$DB_DATABASE" ] || [ -z "$DB_USERNAME" ]; then
    echo "$(date '+%F %T') Backup FAILED: DB_DATABASE / DB_USERNAME missing in $APP/.env" >&2
    exit 1
fi

# The password goes in a private temporary file, never on the command line (where other users could see it).
CNF=$(mktemp)
trap 'rm -f "$CNF" "$DEST/db-$STAMP.sql.gz.part"' EXIT
# In MySQL option files a quoted value treats \ and " specially, so escape them.
esc() { local v=${1//\\/\\\\}; printf '%s' "${v//\"/\\\"}"; }
{
    echo "[client]"
    echo "user=\"$(esc "$DB_USERNAME")\""
    echo "password=\"$(esc "$DB_PASSWORD")\""
    echo "host=${DB_HOST:-localhost}"
    echo "port=${DB_PORT:-3306}"
} > "$CNF"

# Database: written to a .part file first, so a failed dump never looks like a good backup.
mysqldump --defaults-extra-file="$CNF" --single-transaction --routines --no-tablespaces "$DB_DATABASE" \
    | gzip > "$DEST/db-$STAMP.sql.gz.part"
gzip -t "$DEST/db-$STAMP.sql.gz.part"
mv "$DEST/db-$STAMP.sql.gz.part" "$DEST/db-$STAMP.sql.gz"

# Uploaded photos and kept invoice logos.
tar -czf "$DEST/files-$STAMP.tgz" -C "$APP/storage/app" public private 2>/dev/null \
    || tar -czf "$DEST/files-$STAMP.tgz" -C "$APP/storage/app" public

find "$DEST" -type f -mtime +14 -delete
echo "$(date '+%F %T') Backup OK: $DEST/db-$STAMP.sql.gz ($(du -h "$DEST/db-$STAMP.sql.gz" | cut -f1))"
