# ifURI / urirun — roadmap

Cross-repo plan for the ifURI ecosystem. One file per area; check items as done.

Latest audits: [05-analysis-2026-06-20.md](05-analysis-2026-06-20.md)
and [06-structure-audit-2026-06-20.md](06-structure-audit-2026-06-20.md).
Execution planning now lives in the root `/home/tom/github/if-uri/.planfile`
so tasks can cover all repos together.

## Where we are (done)
- **Sites live on Plesk** with repeatable deploy (`scripts/deploy-plesk.sh` + `make deploy`):
  ifuri.com, examples.ifuri.com, docs.ifuri.com, logo.ifuri.com, get.ifuri.com, connect.ifuri.com.
- **Brand**: unified palette (indigo/emerald/slate) + theme-aware logo; copy buttons on code; PWA, SEO (sitemap/robots), 404, a11y, contact+privacy.
- **urirun**: moved under `if-uri/urirun`; v1/v2 runtime; integrated into the app
  (`ifuri-app urirun-info|scan|call|serve`, urirun-first dispatch).
- **App CI**: `ci.yml` (pytest 3.10–3.13 + wheel) and `build-release.yml`
  (PyInstaller binaries for linux/windows/macos-arm64 → GitHub Release).
- **Connectors**: browser-control, http-check, time-tools, planfile,
  domain-monitor, namecheap-dns and sqlite-context have local Makefiles, tests
  and Docker smoke environments; the next proof is a shared host-node matrix.

## What's next (by priority)
- **P0** — make install/release/test deterministic: keep active install paths on
  `if-uri/urirun`, prove every connector in Docker, remove compatibility modules
  from core after downstream migration, and keep release pins synchronized. See
  [05](05-analysis-2026-06-20.md), [06](06-structure-audit-2026-06-20.md),
  [40](40-urirun.md), [50](50-cicd.md).
- **P1** — simplify the runtime boundary: keep `urirun` core small, move
  app/host/domain integrations into app packages or connector packages, and
  standardize `@urirun.command` discovery. See [30](30-connect-connectors.md),
  [40](40-urirun.md), tickets IFURI-015 and IFURI-016.
- **P1** — desktop packaging decision + Tauri shell, node installer parity:
  [10-app-desktop](10-app-desktop.md), [20-get-node](20-get-node.md).
- **P2** — broaden reuse / integrations to other platforms: [60-reuse](60-reuse.md).

## Status board
Live ticket status — planned, in-progress, verified, released — is generated
from the shared execution backlog and published as
[90-planfile.md](90-planfile.md). Tickets cover app, get, ifuri-com, urirun and
connector work together, aligned with the
[work summary](https://github.com/if-uri/docs/blob/main/work-summary-2026-06-20.md).

## Demos and shipped features
Each entry links its repository, docs page and a demo command.

| Area | Status | Repo | Docs | Demo |
| --- | --- | --- | --- | --- |
| noVNC LAN flow (four computers) | released | [examples/11-novnc_lan_flow](https://github.com/if-uri/examples/tree/main/11-novnc_lan_flow) | [docs.ifuri.com/novnc-demo.html](https://docs.ifuri.com/novnc-demo.html) | `make test-full` |
| Connector install / discovery | in-progress | [if-uri/connect.ifuri.com](https://github.com/if-uri/connect.ifuri.com) | [docs.ifuri.com/connectors.html](https://docs.ifuri.com/connectors.html) | `connect.ifuri.com/install?connectors=http-check` |
| get.ifuri.com node installer | released | [if-uri/get](https://github.com/if-uri/get) | [docs.ifuri.com/host-node-lan.html](https://docs.ifuri.com/host-node-lan.html) | `get.ifuri.com/node.sh` |

## Files
- [10-app-desktop.md](10-app-desktop.md) — ifURI desktop app (packaging, Tauri, updates)
- [05-analysis-2026-06-20.md](05-analysis-2026-06-20.md) — current cross-repo findings
- [06-structure-audit-2026-06-20.md](06-structure-audit-2026-06-20.md) — repository map and next refactor/test priorities
- [20-get-node.md](20-get-node.md) — get.ifuri.com node installer
- [30-connect-connectors.md](30-connect-connectors.md) — connect hub + connectors
- [40-urirun.md](40-urirun.md) — urirun packaging & releases
- [50-cicd.md](50-cicd.md) — CI/CD for multiplatform builds + integrations
- [60-reuse.md](60-reuse.md) — where ifURI/urirun can be re-used
- [90-planfile.md](90-planfile.md) — generated ticket status from the execution backlog

## Open decisions (need owner sign-off)
1. Desktop shell: **PyInstaller (now)** vs **Tauri (next)** vs both. → [10](10-app-desktop.md)
2. urirun distribution: GitHub Release wheels only, or also **PyPI + npm**. → [40](40-urirun.md)
3. Code signing budget: Apple Developer ID ($99/yr) + Windows cert (EV/OV). → [50](50-cicd.md)
4. Site deploy: keep manual `make deploy`, or **GitHub Actions over SSH** (secret key). → [50](50-cicd.md)
