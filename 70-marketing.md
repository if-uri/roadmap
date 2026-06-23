# Marketing & content surfaces (repos: if-uri/marketing, meet, live, blog)

## State

The product had no marketing/content layer in this roadmap. As of 2026‑06,
a content + funnel stack is built and (partly) deployed on Plesk, separate from
the engineering repos:

- **calendar.ifuri.com** (`if-uri/calendar`) — webinar scheduler:
  events as `RRRR-MM/*.json`, iCalendar feed, SMTP mailer, liteLLM drafting,
  Meet/Teams/Jitsi links, booking. **Basic Auth** (operational data, non‑public).
- **meet.ifuri.com** (`if-uri/meet`) — Jitsi redirect with public/private rooms
  (HMAC‑signed links), interstitial (5 s + destination server picker), booking
  form, multi‑server health check. Public.
- **live.ifuri.com** (`if-uri/live`) — live‑stream page: YouTube embed or
  meet.ifuri.com webinar, schedule (17 streams Jul–Aug 2026), countdown, `.ics`,
  optional **YouTube Data API** auto‑LIVE (quota‑safe). Channel: `@prototypowanie_pl`.
- **blog.ifuri.com** (`if-uri/blog`) — local markdown originals → WordPress via
  REST API (`sync.php`). 8 pillar/SEO drafts ready. **In progress** (needs WP key).

All four are self‑contained **PHP 8, no composer**, deployed via
`scripts/deploy-plesk.sh` and wired into the ecosystem `deploy.sh`
(`REPOS=… marketing meet live`). Only `.env` reaches production.

The funnel: **content → live/webinar → meet booking → consultation → 24h
delivery (prototypowanie.pl)**. Plan + ICP + runbooks live in
`if-uri/marketing/docs/` (EKOSYSTEM, EXAMPLES, RUNBOOK‑WEBINAR, KONFIGURACJA‑API,
SPECYFIKACJA‑PROJEKTU‑I‑KLIENCI, WYZWANIA‑I‑ZADANIA).

## Tasks

- [x] Build the four surfaces (calendar, meet redirect, live page, blog sync).
- [x] Deploy marketing/meet/live to Plesk; add to ecosystem `deploy.sh`.
- [x] Connector usage docs (`docs/EXAMPLES.md`): 18 connectors e2e + flow YAML.
- [x] YouTube auto‑LIVE code (activates when `YOUTUBE_API_KEY` is set).
- [ ] **Go live with content**: WordPress Application Password → `php blog/sync.php`
      (8 drafts), publish.
- [ ] **SMTP** in `calendar/.env` + `meet/.env` → invitations, reminders, booking
      notifications actually send.
- [ ] **Redeploy** meet (interstitial) + live (auto‑LIVE) to production.
- [ ] **Reminders cron**: `calendar/cli/notify.php --window=60`.
- [ ] **Reliable webinars**: own Jitsi (Docker) or JaaS — public meet.jit.si needs
      login to start a meeting. See `if-uri/meet`.
- [ ] **Analytics** (privacy‑first: Plausible/Umami) across the four domains.
- [ ] **Dogfooding**: monitor ifuri.com/marketing/meet/live with the `http-check`
      connector + triage flow (http-check → llm → planfile → mqtt). Marketing == proof.
- [ ] **Backups** of server‑side data (`marketing/RRRR-MM/`, `meet/data/requests/`).

## Open decisions (need owner sign‑off)

1. **Deploy parity**: keep manual `make deploy`, or fold the four marketing
   domains into the same **GitHub Actions over SSH** pipeline as the product sites
   (see [50-cicd](50-cicd.md)).
2. **Video backend**: self‑hosted Jitsi vs JaaS vs keep public meet.jit.si.
3. **Blog content cadence + ownership**: who reviews drafts before publish.

## Dependencies

- Reuse of connectors here doubles as a demo (`60-reuse`): the marketing stack is
  a real consumer of `http-check`, `planfile`, `mqtt`, `llm`.
- Distribution decision for urirun (PyPI/npm, [40-urirun](40-urirun.md)) changes
  install commands shown in blog articles and `docs/EXAMPLES.md`.
