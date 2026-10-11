#!/usr/bin/env bash
# Baut dist/pkg_planafolio_finanzrechner_<version>.zip und trägt die SHA-256 in updates.xml ein.
set -euo pipefail
cd "$(dirname "$0")"
root=$PWD
v=$(grep -oP '(?<=<version>)[^<]+' pkg_planafolio_finanzrechner.xml)
work=$(mktemp -d)   # Arbeitsordner unter /tmp, räumt das System auf
mkdir -p "$work/packages" dist
(cd mod_planafolio_finanzrechner && zip -qrX "$work/packages/mod_planafolio_finanzrechner.zip" . && cd "$root" && zip -qX "$work/packages/mod_planafolio_finanzrechner.zip" LICENSE)
(cd plg_content_planafolio_finanzrechner && zip -qrX "$work/packages/plg_content_planafolio_finanzrechner.zip" . && cd "$root" && zip -qX "$work/packages/plg_content_planafolio_finanzrechner.zip" LICENSE)
cp pkg_planafolio_finanzrechner.xml script.php LICENSE "$work/"
out="$root/dist/pkg_planafolio_finanzrechner_$v.zip"
rm -f "$out"
(cd "$work" && zip -qrX "$out" .)
sum=$(sha256sum "$out" | cut -d' ' -f1)
sed -i "s|<sha256>[^<]*</sha256>|<sha256>$sum</sha256>|" updates.xml
echo "$out $sum"
