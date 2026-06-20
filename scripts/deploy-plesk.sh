#!/usr/bin/env bash
# Author: Tom Sapletta · https://tom.sapletta.com
# Part of the ifURI solution.

# Publish roadmap.ifuri.com (PHP, GitHub-synced). Preserves the server cache.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
REMOTE="${IFURI_DEPLOY_HOST:-ifuri@ifuri.com}"
DOCROOT="${IFURI_ROADMAP_DOCROOT:-/var/www/vhosts/ifuri.com/roadmap.ifuri.com}"
echo "== deploy roadmap.ifuri.com =="
rsync -az --delete \
  --exclude '.git' --exclude 'scripts' --exclude '.github' \
  --exclude 'cache/*.md' --exclude 'cache/*.at' \
  "${ROOT}/" "${REMOTE}:${DOCROOT}/"
ssh "${REMOTE}" "cd '${DOCROOT}' && find . -type d -exec chmod 755 {} + && find . -type f -exec chmod a+r {} + && chmod 775 cache"
curl -fsSI "https://roadmap.ifuri.com/" | head -3 || true
echo done
