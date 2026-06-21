# connect.ifuri.com + connectors (repo: if-uri/connect.ifuri.com)

## State
- PHP connector hub. Endpoints: `/` picker, `/connectors/{id}(.json)`, `/connectors.json`,
  `/registry.json`, `/search.json`, `/submit`, `POST /validate-connector`,
  `/install?connectors=…` (shell installer). Schemas in `schema/*.json`, logic in `lib/hub.php`.
- Deploy via `scripts/deploy-plesk.sh` (rsync, no `--delete` → preserves `data/`).
- Seed package repos now exist for `urirun-connector-http-check`,
  `urirun-connector-time-tools` and `urirun-connector-browser-control`; each has
  local tests and Docker compose smoke targets.
- Missing enforcement: connector CI, generated registry verification, and one
  stable decorator/import style across packages.

## What a "connector" is
A manifest describing URI routes a third party exposes (DNS, planfile, MCP server, browser,
kvm, llm, …) → bridges into a **urirun binding/registry** the app/flows can call.

## Tasks
- [x] **Seed real connectors**: planfile, namecheap-dns, mcp-filesystem, browser (noVNC),
  llm (local/qwen), kvm, get-node — as validated manifests in the catalog.
  (13 connectors in `data/connectors.json`, all schema-valid; polyglot set covers
  PHP/Go/JS too — base64/hash/uuid.)
- [x] **Standard decorator API**: connector examples use `@urirun.command(...)`,
  not versioned imports such as `@v2.uri_command(...)`. (Verified in connector
  tests/sources; remaining `urirun.v2` refs are registry-compile imports, not the decorator.)
- [x] **Manifest ↔ urirun bridge**: connector.schema → `urirun.bindings.v2` mapping
  so `/install?connectors=…` produces a registry the app runs (`ifuri-app urirun-call`).
  (Live hub: `/install?connectors=planfile` returns a runnable pip/urirun script; `/registry.json` valid.)
- [x] **Entry points**: every Python connector exposes bindings via a stable
  `urirun.bindings` entry point so `urirun scan` can discover it after install.
  (15/15 Python connectors declare it; base64/hash/uuid are polyglot PHP/Go/JS.)
- [x] **Validation in CI**: validate every connector manifest against `schema/*.json` (see [50-cicd](50-cicd.md)).
  (`scripts/validate_connectors.py` — 13 manifests + catalog, 0 errors; in `ci-deploy.yml`.)
- [x] **Connector detail pages**: route list, schemas, install command, examples,
  JSON-LD and links to source/tests for each connector. (`/connectors/{id}.json` live, valid JSON.)
- [x] **Submit flow**: harden `POST /validate-connector`; rate-limit; spam guard for `/submit`.
  (lib/hub.php hub_rate_limit + hub_client_ip; api/validate_connector.php enforces
  POST-only (405), per-IP sliding-window rate limit (429 + Retry-After), 64 KB body
  cap (413) and JSON depth cap 32. Verified live: 429 after limit, 413 on >64 KB, 405 on GET.)
- [x] **Signing/trust**: optional signed manifests + a "verified" badge.
  (Ed25519/libsodium: `scripts/sign-manifest.php` keygen/sign/verify; manifests carry
  a detached `trust` signature over the canonical manifest; `lib/hub.php`
  hub_manifest_verified checks it against `data/publishers.json`; signature-backed
  badge in index/connector pages; `verified` flag in `/registry.json`; schema allows
  the `trust` block; `tests/sign_test.php` in the smoke suite. Verified end to end:
  sign→verify, tamper-detection, unknown-publisher rejection.)
- [x] **Discovery**: project the catalog to **MCP tools/list** and an **A2A agent card**
  (`registry.json` already machine-readable) so agents can find connectors.
  (Live: `/mcp.json` and `/a2a.json` return valid JSON.)
- [x] **SEO**: confirm `sitemap.php`/`robots.php` output; link from ifuri.com.
  (Live: `/sitemap.xml` and `/robots.txt` → HTTP 200.)
- [x] Cache `data/` catalog; document the `data/` directory (kept on deploy).
  (JSON endpoints send `Cache-Control: public, max-age=120`; added `data/README.md`
  documenting the manifest→catalog workflow and that `data/` is preserved on deploy.)

## Verify
- connect.ifuri.com/ 200; `/connectors.json`, `/registry.json`, `/search.json` valid JSON;
  `/install?connectors=planfile` returns a runnable script.
- Each connector repo: `make test`, `make smoke`, `make docker-test` green.
