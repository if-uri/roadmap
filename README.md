# roadmap.ifuri.com

GitHub-synced roadmap for the ifURI / urirun ecosystem.

- Markdown lives in this repo (`*.md`, ordered by `manifest.json`).
- `index.php` renders it; content is fetched from GitHub raw and cached on Plesk
  for one day (`config.php` `ttl`), so **edit on GitHub → site updates daily**, no redeploy.
- Deploy the PHP/site: `make deploy` (rsync to the Plesk docroot, preserves cache).

Sites: https://roadmap.ifuri.com · https://ifuri.com · https://docs.ifuri.com
