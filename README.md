# roadmap.ifuri.com

GitHub-synced roadmap for the ifURI / urirun ecosystem.

- Markdown lives in this repo (`*.md`, ordered by `manifest.json`).
- `index.php` renders it; content is fetched from GitHub raw and cached on Plesk
  for one day (`config.php` `ttl`), so **edit on GitHub → site updates daily**, no redeploy.
- Deploy the PHP/site: `make deploy` (rsync to the Plesk docroot, preserves cache).

Sites: https://roadmap.ifuri.com · https://ifuri.com · https://docs.ifuri.com

## Related projects

- Runtime: [tellmesh/urirun](https://github.com/tellmesh/urirun)
- App/host: [if-uri/app](https://github.com/if-uri/app)
- Public docs: [if-uri/docs](https://github.com/if-uri/docs)
- Examples and noVNC demos: [if-uri/examples](https://github.com/if-uri/examples)
- Connector hub: [connect.ifuri.com](https://connect.ifuri.com)
- Installer: [get.ifuri.com](https://get.ifuri.com)
- Current work summary:
  [work-summary-2026-06-20](https://github.com/if-uri/docs/blob/main/work-summary-2026-06-20.md)

Repository notes: [TODO.md](TODO.md) · [CHANGELOG.md](CHANGELOG.md)
