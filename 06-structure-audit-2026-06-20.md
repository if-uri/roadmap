# Project structure audit - 2026-06-20

Scope: every local repository under `/home/tom/github/if-uri`.

This audit follows the connector split and the namespace move to
`if-uri/urirun`. Historical generated reports may still mention the old tree,
but active install commands, package dependencies and public documentation
should point at `github.com/if-uri/urirun`.

## Repository map

| Repository | Role | Main remaining work |
| --- | --- | --- |
| `app` | Desktop/web host for operators, route execution and node discovery. | Add GUI connector install from the hub, payload forms for discovered URI routes, and noVNC GUI smoke coverage. |
| `blog` | Editorial/news surface. | Add release notes and connector tutorial posts generated from hub metadata. |
| `connect.ifuri.com` | Connector hub and catalog. | Add compatibility badges, install-bundle endpoints, route schema previews and connector health history. |
| `docs` | Canonical operator/developer documentation. | Keep install snippets, connector releases and roadmap links synchronized with the hub and runtime tags. |
| `examples` | Runnable scenarios and Docker/noVNC labs. | Promote the host+pc1+pc2 connector matrix into one repeatable smoke target. |
| `get` | One-line host/node installers. | Add install bundles and a `doctor` check for LAN, service and connector readiness. |
| `ifuri-com` | Main product site. | Keep the `urirun` section aligned with the `if-uri/urirun` namespace and tested releases. |
| `live` | Live/demo pages. | Add TODO/CHANGELOG and link demos to the tested examples and roadmap tickets. |
| `logo` | Brand assets. | Finish export package, usage rules and checksum/version metadata for sites. |
| `marketing` | Market/SEO/content planning. | Convert expansion ideas into connector-focused landing pages and comparison copy. |
| `meet` | Meeting/scheduling surface. | Add TODO/CHANGELOG and decide whether this stays a site or becomes an app connector demo. |
| `roadmap` | Cross-repo planning and planfile status. | Keep `.planfile` tickets, public roadmap pages and project TODO files aligned. |
| `urirun` | URI runtime core. | Remove remaining compatibility modules after downstream migration; keep only URI -> binding -> adapter -> executor. |
| `urirun-connector-*` | Installable capability packages. | Standardize entry points, Docker matrix tests, hub manifests and route/schema documentation. |

## Runtime boundary

The desired split is:

- `urirun`: parser, command decorator, binding/registry model, policy, adapters
  and execution primitives.
- `urirun-connector-*`: domain integrations such as HTTP checks, browser
  control, Planfile, Namecheap DNS, domain monitoring, SQLite context and time
  tools.
- `app`: host/node discovery, GUI, mesh, dashboard, local database, scheduler
  and operator workflow.
- `connect.ifuri.com`: public catalog, manifests, install snippets and
  machine-readable metadata.
- `examples`: runnable proof that host, pc1, pc2 and external services can use
  the same URI contracts over the network.

Modules that still need migration or removal from `urirun` core after
compatibility users move:

- `namecheap_dns.py` -> `urirun-connector-namecheap-dns`
- `domain_monitor.py` -> `urirun-connector-domain-monitor`
- `planfile_adapter.py` -> `urirun-connector-planfile`
- `host_db.py` -> `urirun-connector-sqlite-context` or app storage
- `host_dashboard.py`, `mesh.py`, `scheduler.py`, `task_planner.py` -> `app`
  or a dedicated host package

## Priority plan

### P0 - trust the install path

- Use `github.com/if-uri/urirun` in active install commands, package
  dependencies and public docs.
- Prove every available connector in Docker with host, pc1 and pc2.
- Remove or explicitly mark old compatibility modules once app/docs/examples no
  longer import them.
- Keep connector releases pinned in hub manifests and docs.

### P1 - make connector usage obvious

- Add `urirun connector list/install/info` or equivalent app commands that
  consume the hub catalog.
- Teach the app GUI to install connector packages, refresh registry and show
  generated payload forms.
- Publish route/schema/security metadata per connector page.
- Add `get.ifuri.com` install bundles and a `doctor` command.

### P2 - improve adoption

- Add blog/tutorial content generated from connector metadata.
- Add SEO/social/LLM metadata for each connector and demo.
- Finish brand export packages and reuse them across all sites.

## New planfile tickets

Created from this audit:

- `IFURI-015` - remove remaining compatibility modules from `urirun` core.
- `IFURI-016` - prove connector installs through a host-node Docker matrix.
- `IFURI-017` - add connector install and route discovery to the app GUI.
- `IFURI-018` - publish per-connector contract pages and compatibility badges.
- `IFURI-019` - add installer bundles and doctor checks for host/node setup.
- `IFURI-020` - fixed: local sprint YAML validation uses `--file-type sprint`,
  and `planfile health check` handles generated ticket buckets without crashing.

## Validated planfile commands

```bash
planfile validate schema .planfile/sprints/current.yaml --file-type sprint
planfile validate schema .planfile/sprints/backlog.yaml --file-type sprint
planfile health check get
```
