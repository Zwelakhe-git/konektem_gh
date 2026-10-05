#!/usr/bin/env bash
set -euo pipefail

IN="if0_39722397_zvelake_inserts.sql"
OUT="if0_39722397_zvelake_inserts_fixed.sql"

# Copy first
cp "$IN" "$OUT"

# --- 1. Remove duplicate uppercase INSERT blocks ---
# These tables exist only in lowercase form. Delete the whole block from
# the header comment "-- Дамп данных таблицы `X`" through the final ";" of that block.

for T in "Artists" "Images" "Services" "Videos" "PremiumSubscribers" "UserActivityLog"; do
  # Delete from the "-- Дамп данных таблицы `X`" line up to and including the next blank line + next "--" line.
  # Safer: use awk to drop everything from the marker to the line after the terminating ";" of the last INSERT.
  awk -v tbl="$T" '
    BEGIN { skip=0 }
    $0 ~ "^-- Дамп данных таблицы `" tbl "`" { skip=1 }
    skip==1 && /^;[[:space:]]*$/ { skip=0; next }
    skip==0 { print }
  ' "$OUT" > "$OUT.tmp" && mv "$OUT.tmp" "$OUT"
done

# --- 2. Fix `images` insert: url -> location ---
sed -i 's/INSERT INTO `images` (`id`, `url`, `mime_type`)/INSERT INTO `images` (`id`, `location`, `mime_type`)/' "$OUT"

# --- 3. Fix `videos` insert: image_id,title,url -> vidImg,vidTitle,location ---
sed -i 's/INSERT INTO `videos` (`id`, `image_id`, `title`, `url`, `mime_type`, `likes`, `downloads`, `plays`)/INSERT INTO `videos` (`id`, `vidImg`, `vidTitle`, `location`, `mime_type`, `likes`, `downloads`, `plays`)/' "$OUT"

echo "Done. Output: $OUT"