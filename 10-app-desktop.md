# App — ifURI desktop (repo: if-uri/app)

## State
- Python app `ifuri_app` + CLI `ifuri-app` / `ifuri` (`cli.py`).
- Local HTTP runtime (`runtime.py`) + uricore packs (`packages/ifuri-{bridge,chat,page,voice}`).
- urirun is the primary local runtime (urirun-first dispatch; `urirun-info|scan|call|serve`).
- Packaging today: **PyInstaller** binaries via `scripts/build-platform.py` (linux/windows/macos-arm64) → GitHub Release.
- **Tauri scaffold present but unused**: `desktop/src-tauri` (Cargo, `tauri.conf.json`, icons) — not wired into release CI.
- noVNC and GUI Docker compose environments exist, but they are not yet a
  release gate for "operator can see nodes, logs, results and routes".
- CLI audit found a likely execution gap: the `ifuri-app run` path calls
  `dry_run_flow(...)` even when not in dry-run mode. Either wire real execution
  or rename the command as preview-only.

## Decision: shell strategy
- **Now:** keep PyInstaller binaries (works, already in CI) as the "app" download.
- **Next:** make **Tauri** the GUI shell that wraps the local HTTP runtime (small binary, native webview, real signing/auto-update). The Python runtime stays the engine.

## Tasks
- [x] Decide PyInstaller-only vs Tauri-shell — decided "both shells": PyInstaller
  binaries + Tauri bundling enabled (tauri-release CI).
- [ ] Tauri: load the app's web UI (`src/ifuri_app/web/`) against the local runtime; bundle/spawn the Python runtime (sidecar) or require `pip install ifuri`.
- [ ] App icons/splash from brand kit (`if-uri/logo` → `png/icon/*`, `ico/favicon.ico`).
- [x] First-run wizard: `init --scan-lan`, pick/registry urirun, set node endpoint.
  (gui.FirstRunWizard — scan LAN, pick/type node endpoint, save; shown once.)
- [~] Node/daemon mode: ship `systemd/` unit + a Windows service / launchd plist.
  (systemd user units + launchd plist present in `systemd/`; Windows service TODO.)
- [ ] Optional extras packaging: voice (stt/tts), webrtc — keep optional to keep base small.
- [ ] Auto-update channel (Tauri updater or check GitHub Releases API used by ifuri.com).
- [x] Surface `urirun-serve` from the GUI (start/stop local URI HTTP service).
  (Services tab: Start/Stop/Routes for urirun-serve.)
- [x] `make build` / `make run` parity with CI `scripts/build-platform.py`.
  (`make build` and CI both invoke `scripts/build-platform.py`; `make run` runs the CLI.)
- [x] Fix `ifuri-app run` so `--execute`/non-dry mode uses the real URI/flow
  runner, with tests for dry-run and execute branches.
  (cmd_run routes to RuntimeState; tests/test_cli_run.py covers dry-run + execute.)
- [~] Add a GUI smoke that starts app + noVNC and verifies route table, logs,
  generated workflow and result panels. (Headless xvfb GUI smokes cover route
  tables / payload forms / result+log panels; full app+noVNC Docker smoke TODO.)

## Verify
- `ifuri-app --help`; `pytest --ignore=tests/e2e` green (currently 82+).
- Release artifacts land on ifuri.com Download (reads GitHub Releases via `api/releases.php`).
