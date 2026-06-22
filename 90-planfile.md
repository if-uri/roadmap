# Planfile tickets

Generated from the shared execution backlog at `if-uri/.planfile`. Status comes from each ticket's execution state. This page is regenerated - edit tickets in the planfile, not here.

Tickets: 24 (planned: 15 · released: 9). Last generated 2026-06-22.

Status values: **planned** (queued/not started), **in-progress** (running), **verified** (acceptance checks passed), **released** (execution done).

## Backlog

| Ticket | Status | Summary | Blocked by |
|---|---|---|---|
| IFURI-008 | released | After installing connectors, urirun should discover entry points, generate bindings/registry and expose them… | - |
| IFURI-009 | planned | Create or consolidate a Docker smoke that starts host plus two nodes, installs connectors, generates registry… | - |
| IFURI-010 | planned | The desktop app should make it obvious what devices, processes, connectors and URI commands are available, an… | - |
| IFURI-011 | planned | The main public site should have the same local commands and workflow gates as get/connect/docs: smoke, docke… | - |
| IFURI-012 | planned | Move the important real-world integrations into connector packages and catalog pages so users can install cap… | - |
| IFURI-013 | planned | Improve connector discovery for humans, search engines and LLM agents with dedicated pages, route/schema exam… | - |
| IFURI-015 | planned | Finish the split between urirun core, connectors and app/host | - |
| IFURI-016 | planned | Run every available connector in a repeatable Docker environment with host, pc1 and pc2 | - |
| IFURI-017 | planned | The desktop/web operator UI should consume the connector hub catalog, install selected connector packages, re… | - |
| IFURI-018 | planned | Each connector page should show install snippets, supported URI routes, payload schemas, Docker smoke command… | - |
| IFURI-019 | planned | get.ifuri.com should provide one-line host/node setup plus optional bundles such as desktop, headless node an… | - |
| IFURI-020 | released | planfile ticket list and the roadmap generator can read .planfile/sprints, but planfile validate schema expec… | - |
| IFURI-021 | released | Make runtime failures searchable and actionable through built-in error:// codes, CLI commands, registry bindi… | - |
| IFURI-022 | planned | Activate the content/funnel stack: set WordPress Application Password and run blog/sync.php (8 drafts), confi… | - |
| IFURI-023 | planned | Use our own connectors to monitor our own sites: http-check on ifuri.com/marketing/meet/live, llm assessment,… | - |
| IFURI-024 | planned | Public meet.jit.si requires login to START a meeting | - |
| IFURI-025 | planned | Add privacy-first analytics (Plausible/Umami) across marketing/meet/live/blog and back up server-side data (m… | - |

## Sprint 1

| Ticket | Status | Summary | Blocked by |
|---|---|---|---|
| IFURI-001 | released | Make one version source of truth for urirun | - |
| IFURI-002 | released | Runnable get.ifuri.com smoke scripts still point to ../../tellmesh/urihandler/adapters/python | - |
| IFURI-003 | released | Enforce existing Makefile smoke targets in GitHub Actions | - |
| IFURI-004 | released | Audit found ifuri-app run calls dry_run_flow in both branches | - |
| IFURI-005 | released | Normalize ignore files and local environment handling across connector repos so venv, .venv, pytest cache, bu… | - |
| IFURI-006 | released | Expose stable top-level decorator and registry helpers | - |
| IFURI-007 | planned | Keep urirun as URI -> binding -> adapter -> executor core | - |

## Source

Tickets live in `if-uri/.planfile/sprints/` and cover app, get, ifuri-com, urirun and connector work together. See the [cross-repo analysis](05-analysis-2026-06-20.md) and the [work summary](https://github.com/if-uri/docs/blob/main/work-summary-2026-06-20.md).
