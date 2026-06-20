# urirun — packaging & releases (repo: tellmesh/urirun, formerly urihandler)

## State
- Python package `urirun` in `adapters/python/` (v1/v2 runtime, CLI `urirun`/`urirun-v1`/`urirun-v2`).
- JS adapter in `adapters/js/`, C adapter in `adapters/c/`.
- CI: `.github/workflows/ci.yml` → `make test`; release workflow exists for
  GitHub Release artifacts.
- Docs say: PyPI optional; install from GitHub; GitHub Release wheels referenced (e.g. v0.3.4) but not auto-built.
- Version mismatch: root `VERSION` and root `package.json` are `0.3.12`;
  `adapters/python/VERSION` and `adapters/python/pyproject.toml` are `0.3.14`;
  `adapters/js/package.json` is `0.1.0`.
- Core package still includes host/app/domain modules (`mesh`, dashboard,
  host DB, planfile, Namecheap, domain monitor). This should be split behind
  optional extras or connector packages.

## Tasks
- [x] **Release workflow** (`release.yml`): on tag `v*` → build wheel + sdist (`adapters/python`),
  attach to GitHub Release (+ `sha256sums.txt`). This is what `get`/app should pin to.
- [ ] **Version source of truth**: one canonical version and a release check that
  fails if Python, root and JS metadata disagree.
- [ ] **Stable public Python API**: expose `urirun.command`, registry helpers and
  connector discovery from `urirun`, not `urirun.v2`.
- [ ] **Core split**: move host/dashboard/mesh/planner/domain integrations out
  of the core release path.
- [ ] **Optional PyPI**: `twine upload` job behind a `PYPI_TOKEN` secret (decision in [00](00-roadmap.md)).
- [ ] **npm package** for `adapters/js` (`urirun` JS) → publish on tag (decision).
- [ ] **C adapter**: ship `urirun.c/.h` as a release asset for firmware reuse.
- [ ] **`urirun[grpc]`** extra verified in CI (optional deps).
- [ ] Keep v1 (param-binding) and v2 (schema-first) both supported; document migration.
- [ ] Make `make test` green in CI after the v1/v2 rename (local is green).

## Verify
- Tag → Release contains `urirun-X.Y.Z-py3-none-any.whl` + `*.tar.gz` + `sha256sums.txt`.
- `pip install <release wheel>` works; `urirun --help`, `urirun scan`, `urirun run`.
