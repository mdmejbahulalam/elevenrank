#!/usr/bin/env bash
# Builds a ready-to-upload zip: production dependencies installed, no tests, no secrets.
# Usage: bash deploy/package.sh   (run from the project root; needs Composer and tar)
set -euo pipefail

root="$(cd "$(dirname "$0")/.." && pwd)"
out="${1:-$root/../elevenrank-platform-release}"
stage="$(mktemp -d)"

mkdir -p "$out"
tar -C "$root" \
	--exclude=.git --exclude=node_modules --exclude=vendor --exclude=tests --exclude=.env \
	--exclude='*.log' --exclude='database/*.sqlite' --exclude=.phpunit.cache --exclude=storage/app/private/releases \
	-cf - . | tar -C "$stage" -xf -

(cd "$stage" && composer install --no-dev --optimize-autoloader --no-interaction --no-scripts)
# Cached provider lists from a dev machine name dev-only packages that are not installed in production.
rm -f "$stage"/bootstrap/cache/*.php
mkdir -p "$stage/storage/framework/"{cache,sessions,views} "$stage/storage/logs" "$stage/bootstrap/cache"

rm -f "$out/elevenrank-platform.zip"
php "$root/deploy/zip.php" "$stage" "$(cd "$out" && pwd -W 2>/dev/null || pwd)/elevenrank-platform.zip"
rm -rf "$stage"

ls -l "$out/elevenrank-platform.zip"
