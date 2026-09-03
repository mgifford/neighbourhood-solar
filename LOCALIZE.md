# Localizing Neighbourhood Solar for your community

This repository ships as the **Ottawa pilot**. It is built so another
community can fork it and adapt it without adding regional variants to this
repository or converting it to a new framework. Everything a visitor sees is
driven by one YAML config file (`configs/neighbourhood.yaml`) and a small set
of Jinja2 templates.

Do **not** add speculative pages for other cities to this repository. To run a
pilot elsewhere, fork the repo and localize your own copy using the checklist
below.

> **Verify before you publish.** Every program, rule, incentive and utility
> claim below must be confirmed against a **primary source** for your own
> jurisdiction — a government, utility or program website — not copied from
> the Ottawa config or from an AI assistant's memory. Programs open, close and
> change eligibility often.

## Where localized values live

Almost everything is centralized in `configs/neighbourhood.yaml`, so you rarely
need to touch the templates:

| What | Config location |
|---|---|
| Community / region name | `location.*`, `site.title` |
| Electricity utility name and links | `utility.*` |
| Program names and links | `programs`, `programs_note`, `programs_last_verified` |
| Contact address | `contact.email`, `contact.mailto_subjects` |
| Registration URL | `registration.form_url` |
| Last-verified date | `programs_last_verified` |
| Positioning, independence, privacy text | `content.positioning`, `content.independence`, `content.privacy_note` |
| Climate-plan links | `city_plans` |
| Cohort targets and tracker | `cohort.*` |
| Currency / spelling | prose in config and templates |
| Printable URLs and QR codes | generated from `registration.form_url` and `site.base_url` |
| Social-sharing metadata | `site.title`, `site.description`, `site.base_url` |

## Localization checklist

Work through every item. Leave a value as a TODO rather than inventing one.

- [ ] **Community and region name** — `location.city`,
      `location.province_or_state`, `location.country`,
      `location.country_code`, `location.region_label`, and `site.title` /
      `site.short_title`.
- [ ] **Electricity utility** — `utility.name` and the DER / net-metering /
      connection / demand-flexibility links under `utility.*`. Remove any link
      your utility does not offer.
- [ ] **Net-metering or grid-connection rules** — confirm what your utility
      requires. Grid-connected systems normally need utility approval, an
      individual assessment, and a contract per home. Correct the "Working
      with [utility]" wording if your rules differ.
- [ ] **Local permits and electrical approvals** — confirm the electrical
      safety authority and permit process in your jurisdiction, and reflect it
      in the utility section wording.
- [ ] **Current rebates, loans and financing** — replace every entry in
      `programs` with programs that actually apply where you are. Set
      `programs_last_verified` to the date you checked them and keep
      `programs_note` accurate.
- [ ] **Climate-plan links** — replace `city_plans` with your municipality's
      current plans, or leave the list empty to hide the section.
- [ ] **Registration form** — create your own form and set
      `registration.form_url`. Confirm what the form actually collects matches
      `content.privacy_note` (see Privacy below).
- [ ] **Contact information** — set `contact.email` and the per-audience
      `mailto_subjects`. Confirm you are comfortable with the address being
      public.
- [ ] **Privacy wording** — edit `content.privacy_note` so it matches how you
      really handle submissions. Collect only what is needed to establish
      interest and approximate location; do not publish individual locations;
      do not share contact details with contractors without explicit consent;
      and provide a route to withdraw or delete data.
- [ ] **Cohort targets** — set `cohort.thresholds`, `cohort.progress_target`
      and `cohort.progress_label`. Keep `cohort.progress_is_live: false` until
      the numbers reflect **real** registrations; when they do, set it to
      `true`, update `cohort.progress_registered`, and set
      `cohort.progress_updated` to the date. While it is `false` the homepage
      tracker is clearly labelled "Example".
- [ ] **Currency and spelling** — use your local currency and spelling
      conventions consistently (this pilot uses Canadian spelling, e.g.
      "neighbours").
- [ ] **Printable URLs and QR codes** — the flyer and poster show a readable
      URL and a QR code generated from `registration.form_url` and
      `site.base_url`. Confirm the printed URL is correct; the QR code must
      never be the only way to reach the form.
- [ ] **Social-sharing metadata** — set `site.title`, `site.description` and
      `site.base_url` so link previews and canonical URLs are correct.
- [ ] **Neighbourhood map (optional)** — replace or remove
      `pamphlet.map_svg`; the Ottawa default draws Centretown.

## Build and check

```bash
pip install -r requirements.txt
python build.py --config configs/yourcity.yaml --output _site
```

Then read every built page (`index.html`, `contractors.html`,
`community-leaders.html`, `pamphlet.html`, `poster.html`, `outreach.html`),
check the flyer and poster in print preview, and confirm no Ottawa-specific
text remains. See [README-build.md](README-build.md) for full build and
deployment steps, and [FORK-WITH-AI.md](FORK-WITH-AI.md) if you want an AI
assistant to do the mechanical editing.

## What not to change

- Keep the site **resident-led, vendor-neutral, and commission-free**.
- Do not imply endorsement by your city, utility, or any contractor unless a
  real, documented partnership exists.
- Do not add analytics, advertising, or tracking scripts.
- Do not promise specific discounts, whole-home backup, or guaranteed savings.
