#!/usr/bin/env python3
"""Generate 90-planfile.md from the shared execution backlog.

Reads the planfile sprints at if-uri/.planfile/sprints/*.yaml and renders a
roadmap page that summarizes every ticket with its status and blockers. Status
comes from each ticket's execution state, so the roadmap reflects real work.

    python3 scripts/build_planfile_page.py

The page is regenerated; edit tickets in the planfile, not in the markdown.
"""
import os
import glob
import datetime
import yaml

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PLANFILE = os.environ.get("PLANFILE_DIR", os.path.join(ROOT, "..", ".planfile"))
OUT = os.path.join(ROOT, "90-planfile.md")

# execution.state / explicit status -> public status label
STATUS = {
    "done": "released",
    "verified": "verified",
    "running": "in-progress",
    "in_progress": "in-progress",
    "queued": "planned",
    "pending": "planned",
    "todo": "planned",
}


def short(text, limit=110):
    text = " ".join((text or "").split())
    head = text.split(". ")[0].rstrip(".")
    if len(head) > limit:
        head = head[: limit - 1].rstrip() + "…"
    return head or "(no description)"


def ticket_status(t):
    state = ((t.get("execution") or {}).get("state")) or t.get("status") or "planned"
    return STATUS.get(str(state).lower(), "planned")


def main():
    files = sorted(glob.glob(os.path.join(PLANFILE, "sprints", "*.yaml")))
    sprints = []
    counts = {}
    for f in files:
        data = yaml.safe_load(open(f, encoding="utf-8")) or {}
        sp = data.get("sprint") or {}
        tickets = sp.get("tickets") or {}
        rows = []
        for tid in sorted(tickets):
            t = tickets[tid] or {}
            st = ticket_status(t)
            counts[st] = counts.get(st, 0) + 1
            blocked = ", ".join(t.get("blocked_by") or []) or "-"
            rows.append((tid, st, short(t.get("description", "")), blocked))
        sprints.append((sp.get("name") or sp.get("id") or os.path.basename(f), rows))

    today = os.environ.get("PLANFILE_DATE", datetime.date.today().isoformat())
    total = sum(counts.values())
    legend = " · ".join(f"{k}: {v}" for k, v in sorted(counts.items()))

    out = []
    out.append("# Planfile tickets")
    out.append("")
    out.append(
        "Generated from the shared execution backlog at `if-uri/.planfile`. Status "
        "comes from each ticket's execution state. This page is regenerated - edit "
        "tickets in the planfile, not here."
    )
    out.append("")
    out.append(f"Tickets: {total} ({legend}). Last generated {today}.")
    out.append("")
    out.append(
        "Status values: **planned** (queued/not started), **in-progress** "
        "(running), **verified** (acceptance checks passed), **released** "
        "(execution done)."
    )
    for name, rows in sprints:
        out.append("")
        out.append(f"## {name}")
        out.append("")
        if not rows:
            out.append("No tickets.")
            continue
        out.append("| Ticket | Status | Summary | Blocked by |")
        out.append("|---|---|---|---|")
        for tid, st, summ, blocked in rows:
            out.append(f"| {tid} | {st} | {summ} | {blocked} |")
    out.append("")
    out.append("## Source")
    out.append("")
    out.append(
        "Tickets live in `if-uri/.planfile/sprints/` and cover app, get, "
        "ifuri-com, urirun and connector work together. See the "
        "[cross-repo analysis](05-analysis-2026-06-20.md) and the "
        "[work summary](https://github.com/if-uri/docs/blob/main/work-summary-2026-06-20.md)."
    )
    out.append("")
    open(OUT, "w", encoding="utf-8").write("\n".join(out))
    print(f"wrote {OUT} ({total} tickets across {len(sprints)} sprints)")


if __name__ == "__main__":
    main()
