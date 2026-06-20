# TODO

## Roadmap site work

- [ ] Keep the 2026-06-20 project-structure audit in the manifest nav and
      regenerate `90-planfile.md` whenever `.planfile` tickets change.
- [ ] Track IFURI-015..IFURI-019 through the roadmap until core extraction,
      connector matrix testing, app GUI install, connector contract pages and
      installer doctor checks are verified.
- [x] Track IFURI-008 until installed connectors can expose entry-point
      bindings and generate a registry without manual JSON merging.
- [x] Track IFURI-021 until built-in `error://` diagnostics can be queried from
      CLI, registry routes and direct per-error URI addresses.
- [x] Track IFURI-020 until `.planfile/sprints/*.yaml` has a supported
      validation/health-check path. (`--file-type sprint`, health bucket fix)
- [ ] Review active namespace references monthly; public install commands should
      use `github.com/if-uri/urirun`, while old `tellmesh/*` mentions should be
      historical only.
- [x] Publish the 2026-06-20 cross-repo analysis as a first-class roadmap page.
      (in the manifest nav and linked from 00-roadmap)
- [x] Keep `/home/tom/github/if-uri/.planfile` as the execution backlog for
      app, get, ifuri-com, urirun and connector work. (surfaced via the
      generated `90-planfile.md` status page)
- [x] Add a roadmap item for the four-computer noVNC URI flow in
      `if-uri/examples/11-novnc_lan_flow`. (Demos table in 00-roadmap)
- [x] Add a roadmap item for connector install/discovery from `connect.ifuri.com`.
      (Demos table + ticket IFURI-008)
- [x] Add a roadmap item for `get.ifuri.com` host and node installers.
      (Demos table)
- [x] Add status fields to roadmap entries so the site can show planned,
      in-progress, verified and released work. (status column + `90-planfile.md`)
- [x] Link roadmap entries to the related repository, docs page and demo command.
      (Repo/Docs/Demo columns in the Demos table)
- [x] Keep the roadmap aligned with the cross-repository work summary.
      (linked from the Status board and the planfile page source)
- [x] Add a generated summary of current planfile tickets to the roadmap site.
      (`scripts/build_planfile_page.py` -> `90-planfile.md`, in the nav)

## Related resources

- Runtime: https://github.com/if-uri/urirun
- App/host: https://github.com/if-uri/app
- Examples: https://github.com/if-uri/examples
- Connector hub: https://connect.ifuri.com
- Installer: https://get.ifuri.com
- Work summary: https://github.com/if-uri/docs/blob/main/work-summary-2026-06-20.md
