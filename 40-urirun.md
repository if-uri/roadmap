# urirun — packaging & releases (repo: if-uri/urirun, formerly urihandler)

## State
- Python package `urirun` in `adapters/python/` (v1/v2 runtime, CLI `urirun`/`urirun-v1`/`urirun-v2`).
- JS adapter in `adapters/js/`, C adapter in `adapters/c/`.
- CI: `.github/workflows/ci.yml` → `make test`; release workflow exists for
  GitHub Release artifacts.
- Docs say: PyPI optional; install from GitHub; GitHub Release wheels referenced.
- Version metadata is aligned at `0.4.0` across all five root/Python/JS files,
  guarded by `make version-check` and bumped in one move with `make release-bump`.
  (Tag/publish of 0.4.0 is gated on a green suite — see `test_compat` below.)
- Connector authoring SDKs exist in 11 languages (Python, JS, Go, PHP, Ruby, Perl,
  Bash, Rust, TypeScript, Java, C#), kept in lockstep by `make conformance`.
- `urirun adopt-pack` gives an existing package a URI surface from its manifest,
  `[tool.urirun]`/`package.json`, or CLI entry points — see [adopt-as-uri](../docs/adopt-as-uri.md).
- Routing is param-aware: concrete URIs resolve templated mid-path `{param}` segments.
- Core split landed (IFURI-007). A bare `import urirun` loads no host/node/domain
  modules — the legacy `mesh`/`host_dashboard`/`host_db`/`domain_monitor`/
  `planfile_adapter`/`scheduler`/`task_planner`/`host_integrations` names are
  back-compat shims, `namecheap_dns` was removed, and the real implementations
  live behind lazy imports in `urirun.host` / `urirun.node`. The shared host/node
  backend (host_db, planfile, domain_monitor — reused by sqlite-context etc.) is
  intentionally kept in core; Namecheap/domain-monitor/planfile **bindings** are
  owned by their connector packages.
- `urirun.v2` no longer stores the host/domain binding implementations
  directly. It delegates through lazy compatibility wrappers to
  `urirun.host_integrations`, and a minimal-import test prevents eager loading
  of host/dashboard/domain/planfile/transport modules.

## Tasks
- [x] **Release workflow** (`release.yml`): on tag `v*` → build wheel + sdist (`adapters/python`),
  attach to GitHub Release (+ `sha256sums.txt`). This is what `get`/app should pin to.
- [x] **Version source of truth**: one canonical version and a release check that
  fails if Python, root and JS metadata disagree.
- [x] **Stable public Python API**: expose `urirun.command`, registry helpers and
  connector discovery from `urirun`, not `urirun.v2`.
- [x] **Built-in diagnostics**: standardize `error://` runtime failures with
  stable codes, categories, fix hints, CLI search/info/recent commands,
  registry-ready bindings and planfile ticket conversion.
- [x] **Compatibility migration guard**: add `urirun compat list/check` so
  downstream projects can see which legacy modules move to connectors or the
  app layer before the files are physically removed from core.
- [x] **Adopt-pack**: adopt any package as URI with least change — zero-change
  CLI→URI, a capability-manifest bridge, and `[tool.urirun]`/`package.json` config;
  first-class `urirun adopt-pack`; installed packs discovered without importing them.
- [x] **Param-aware routing**: templated mid-path `{param}` segments resolve from a
  concrete URI and bind to the handler (exact matches win).
- [x] **Multi-language SDKs**: 9 new languages beside Python/JS, verified by a
  data-driven conformance check (structural + functional execution pass).
- [x] **Release bump tooling**: `make release-bump V=X.Y.Z` reconciles all five
  version files and opens a CHANGELOG section.
- [x] **Core split (IFURI-007)**: runtime split into `urirun.runtime.*` with
  `host`/`connector` subpackages and back-compat shims landed; the compat suite
  (`test_compat` — 8 tests, green) reports `ok` with namecheap extracted and the
  host/node backend kept, and the planfile/domain-monitor/namecheap connector
  packages own their bindings. Verified by `test_minimal_imports.py`,
  `test_core_import_smoke.py` and the full suite (1733 passed).
- [ ] **Optional PyPI**: `twine upload` job behind a `PYPI_TOKEN` secret (decision in [00](00-roadmap.md)).
- [ ] **npm package** for `adapters/js` (`urirun` JS) → publish on tag (decision).
- [ ] **C adapter**: ship `urirun.c/.h` as a release asset for firmware reuse.
- [ ] **`urirun[grpc]`** extra verified in CI (optional deps).
- [ ] Keep v1 (param-binding) and v2 (schema-first) both supported; document migration.
- [x] Make `make test` green locally after the v1/v2 rename.

## Verify
- Tag → Release contains `urirun-X.Y.Z-py3-none-any.whl` + `*.tar.gz` + `sha256sums.txt`.
- `pip install <release wheel>` works; `urirun --help`, `urirun scan`, `urirun run`.
