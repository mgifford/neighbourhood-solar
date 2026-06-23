# Neighbourhood Solar & Energy Initiative

**Resident-led. Vendor-neutral. No commission. Fork for your street.**

→ [Live Ottawa pilot site](https://mgifford.github.io/neighbourhood-solar/)

---

## Why this exists

Energy decisions are getting complicated. Solar panels, battery backup, heat pumps, EV chargers, utility rules, financing programs, warranties — and every homeowner is expected to figure it all out alone.

That's inefficient. And it's unfair.

When neighbours coordinate, contractors can reduce their soft costs, homeowners get better information, and the whole process becomes less opaque. This project is a simple, open tool to make that coordination happen — without anyone taking a cut.

No sales commission. No vendor lock-in. No JavaScript. No tracking. Just neighbours helping neighbours make better decisions about home energy.

## What it is

A static website template for community-led neighbourhood solar and home energy upgrade pilots. One YAML config file per city. Fork it, fill in your details, and you have a site for your neighbourhood in under an hour.

**Live pages:**
- [Residents](https://mgifford.github.io/neighbourhood-solar/) — what's in it for homeowners
- [Contractors](https://mgifford.github.io/neighbourhood-solar/contractors.html) — how to participate in group bids
- [Community Leaders](https://mgifford.github.io/neighbourhood-solar/community-leaders.html) — how to run a neighbourhood pilot
- [Printable flyer](https://mgifford.github.io/neighbourhood-solar/pamphlet.html) — half-sheet you can print, cut, and drop in mailboxes
- [Sample email](https://mgifford.github.io/neighbourhood-solar/outreach.html) — a resident outreach template you can send to neighbours

## Proposed toolkit layout

If you want to add the visibility and outreach assets described on the homepage, keep them in predictable folders so volunteers can find and reuse them:

```text
neighbourhood-solar/
├── assets/
│   ├── signs/
│   │   ├── yard-sign.svg
│   │   ├── window-decal.svg
│   │   └── print/
│   └── flyers/
│       ├── block-flyer.md
│       └── block-flyer.pdf
├── templates/
│   └── outreach.html
└── content/
	└── blocks/
		├── cohort-tracker.md
		└── how-it-works.md
```

This keeps the public site simple while giving organizers a clear place for print-ready signs, flyers, and reusable copy.

## How you can help

**I'm a resident interested in solar or batteries** — [register your interest](https://mgifford.github.io/neighbourhood-solar/) for the Ottawa pilot. No purchase commitment.

**I'm a contractor** — [read the contractor page](https://mgifford.github.io/neighbourhood-solar/contractors.html). We're looking for installers willing to quote transparently for groups.

**I want to run this in my city** — fork this repo, copy `configs/neighbourhood.yaml`, update the values for your location, and push to GitHub Pages. See [README-build.md](README-build.md) for step-by-step instructions.

**I'm a developer** — issues and pull requests are welcome. The codebase is small on purpose: Python + Jinja2 + plain HTML/CSS. No framework. No build complexity beyond what's needed.

**I'm a community organizer or councillor** — [read the community leaders page](https://mgifford.github.io/neighbourhood-solar/community-leaders.html) and get in touch.

## The problem this solves

Most homeowners who want solar or a battery don't act because:

- They don't know who to trust
- Quotes are hard to compare
- Incentive programs are confusing
- The upfront cost feels like a leap of faith

A neighbourhood group approach changes that. When 10–20 nearby homes are interested together, contractors can schedule efficiently and offer better terms. Residents can compare proposals side by side. And one person's research benefits the whole street.

This site is the organizing layer that makes that possible.

## Principles

- **Resident-led.** No vendor or contractor runs this process.
- **Transparent.** Proposals are shared with the group, not negotiated in private.
- **No obligation.** Every household decides for themselves. No pressure.
- **Open source.** Everything is forkable. Lessons learned are shared publicly.
- **Low footprint.** No JavaScript, no CDN, no tracking, no cookies. Fast on any connection.

## Technical setup

See [README-build.md](README-build.md) for installation, local build, config reference, and GitHub Actions deployment.

## Licence

MIT. Fork freely. Drop a link back if you find it useful.
