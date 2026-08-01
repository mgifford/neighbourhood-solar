# Outreach playbook

How to go from "nobody knows this exists" to a cohort large enough to
approach contractors. See [outreach.html](../../templates/outreach.html)
for the single sample email that's on the public site — this doc covers
the rest of the recruitment process around that email.

## Before you send anything

- Pick a starting boundary — a street, a few blocks, a ward. Small and
  specific beats "the whole city." Neighbours trust a message that names
  their actual street.
- Set a first-stage target (the Ottawa config uses 5 / 10 / 20 household
  thresholds — see `cohort.thresholds` in `configs/neighbourhood.yaml`).
  A visible, small target makes it easy for people to see progress.
- Have your sign-up mechanism ready first (a Google Form, a reply-to
  email — see `registration.form_url` in the config). Don't send outreach
  before there's somewhere for a "yes" to go.

## Channels, roughly in order of effort-to-reach ratio

1. **Direct outreach to people you already know locally.** Slowest to
   scale, highest conversion. Start here — your first 3-5 households are
   what make the community page and outreach email credible ("7 of your
   neighbours have already registered" vs. "0").
2. **Printed flyers / door hangers.** Use [pamphlet.html](../../templates/pamphlet.html)
   or [poster.html](../../templates/poster.html) from the site. Physical
   mail still reaches people who don't check community Facebook groups or
   Nextdoor.
3. **Neighbourhood email lists / community associations.** Many Ottawa
   wards have a community association newsletter or listserv. Ask
   permission before posting — a cold post to a list you're not known on
   reads as spam.
4. **Local social media groups** (Nextdoor, neighbourhood Facebook
   groups, local subreddits). Good for reach, weak for conversion — treat
   replies here as "interested, needs a follow-up," not a firm sign-up.
5. **Councillor or community association newsletter mention.** Slow to
   arrange but lends credibility. Worth an email to your city councillor
   once you have a handful of households already signed up — "here's
   what's already happening in the ward" is an easier ask than "please
   help me start something from zero."

## What to say, and what not to say

- Lead with the collective-bargaining logic (group size → better pricing
  and warranties), not with climate messaging alone. Cost savings and
  "strength in numbers" recruit a broader group than an environmental
  pitch does on its own.
- Be explicit, every time, that registering is not a purchase commitment.
  This is the single most common objection — people worry that signing
  up obligates them to buy something.
- Don't promise specific discount percentages before you have vendor
  quotes in hand. The Ottawa config's `cohort.opening_ask` and
  `cohort.expected_compromise` values are negotiating targets, not
  guarantees — don't repeat them to residents as if they're confirmed.
- Don't name a specific contractor before the group has actually
  selected one. Early outreach should stay vendor-neutral even if you
  personally have a contractor in mind — naming one early can look like
  you're steering the group toward a preferred vendor.

## Tracking sign-ups

- Keep a simple running list (name, address or street, email, date
  registered). A spreadsheet is enough at this scale.
- If you want to show public momentum without exposing personal data,
  publish an anonymized count or street-level summary — see
  `registration.spreadsheet_url` in the config for a place to link a
  public, anonymized version.
- Re-engage people who registered but went quiet once you hit a
  threshold — a "we hit 10 households, here's what happens next" email
  converts fence-sitters better than repeated general outreach.

## When you've hit your first threshold

Move to scheduling the info session — see
[meeting-agendas.md](meeting-agendas.md#info-session-agenda).
