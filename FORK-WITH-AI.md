# Set up your site with an AI assistant

You don't need to know Python, YAML, or Jinja2 to launch a pilot for your
own city or neighbourhood. This page gives you a ready-to-use prompt for an
AI coding assistant (Claude, ChatGPT, GitHub Copilot Workspace, Cursor,
etc.) that can read and edit files in your forked copy of this repo. Fill
in the blanks below, paste the prompt into your assistant, and it will do
the config editing for you.

If you'd rather do it by hand, see [README-build.md](README-build.md) —
this page follows the same steps, just phrased as instructions for an AI
assistant instead of for you directly.

## Before you start

1. Fork this repo on GitHub: click **Fork** at the top of
   [github.com/mgifford/neighbourhood-solar](https://github.com/mgifford/neighbourhood-solar).
2. Give your AI assistant access to your fork — clone it locally and point
   a tool like Claude Code or Cursor at the folder, or use a web-based
   assistant that can read/write files in a GitHub repo.
3. Fill in as much of "Your details" below as you can. Leave anything
   you're unsure of blank — the prompt tells the assistant to flag missing
   details as TODOs rather than invent them.

## Your details

Copy this list and fill in what you know:

```
City / town name:
Province / state:
Country:
Two-letter country code (e.g. CA, US):
Neighbourhood or region label (e.g. "Ottawa ward", "West End"):
Contact email for this project:
Sign-up form URL (Google Form, Airtable, etc.) — leave blank if you haven't made one yet:
GitHub username and repo name of your fork:
Custom domain, if any (leave blank to use the default github.io URL):
Rough number of neighbours already interested (0 if just starting):
Initial group-size target you're aiming for:
Local incentive/rebate programs you know of (name + URL for each, or "not sure yet"):
Local climate/energy plans from your city government, if any (name + URL):
Anything specific about your neighbourhood worth mentioning (optional):
```

## The prompt

Paste everything between the lines below into your AI assistant, with your
answers from "Your details" filled in at the bottom.

```
You are setting up a fork of the "Neighbourhood Solar & Energy Initiative"
static site generator (https://github.com/mgifford/neighbourhood-solar) for
a new city or neighbourhood. This is a Python + Jinja2 static site builder —
no JavaScript frameworks, no database, no build complexity beyond what
already exists. Read README.md and README-build.md in this repo first for
full context before making any changes.

Your task:

1. Confirm you are working in a fork of this repo, not the original
   mgifford/neighbourhood-solar upstream, unless I've explicitly said
   otherwise.

2. Create configs/<city-slug>.yaml as a copy of configs/neighbourhood.yaml.
   Do not delete or overwrite the existing Ottawa reference config — it's
   meant to stay as a working example.

3. Fill in every field in the new config using the details I give you below.
   If a value is missing or I wrote "not sure yet," leave the existing
   placeholder/example value in place and add a comment
   `# TODO: confirm this` directly above it. Do not invent facts, URLs, or
   program names I have not given you or that you have not verified are
   real. Only use web search to help find real local incentive programs if
   I've asked for that help and you have that capability; otherwise leave
   it as a TODO for me.

4. After editing the config, search templates/ and your new config
   (case-insensitive) for the word "Ottawa" to confirm nothing else needs
   localizing. Everything should route through `location.city` and other
   config fields already. If you find hardcoded city references inside
   templates/*.html itself (not the reference config), stop and tell me —
   that's a template bug, not something to silently patch around.

5. Update .github/workflows/deploy.yml (and link-check.yml, if present) to
   point at the new config path instead of configs/neighbourhood.yaml.

6. Run `python build.py --config configs/<city-slug>.yaml --output _site`
   and confirm it completes without errors. Relay any build warnings about
   placeholder values (contact.email, registration.form_url, site.base_url)
   to me in plain language.

7. Do not add JavaScript, CDN dependencies, analytics, or tracking to the
   output — a low-footprint, no-tracking static site is a stated project
   principle. The one existing inline script (the dark-mode theme toggle in
   templates/base.html) is intentional; leave it as-is unless I ask you to
   change it.

8. Do not change the AGPL-3.0 license, the core page structure, or the
   resident-led / vendor-neutral / no-commission principles described in
   README.md. Those are the project's deliberate design choices, not
   implementation details for you to revise.

9. When done, summarize in plain language (no code jargon) what you
   changed and what I still need to do myself — for example: create a
   sign-up form and paste the link into registration.form_url, turn on
   GitHub Pages in repo settings, or double-check incentive program URLs
   are current before publishing.

10. Ask before committing, pushing, or opening a pull request. Don't do
    it silently.

Here are my details for this city:

[paste your filled-in "Your details" list here]
```

## After the assistant finishes

An AI assistant can do the mechanical editing, but you're still the one
responsible for publishing accurate, local information. Before you make the
site public:

- Read every built page in a browser — `_site/index.html`,
  `contractors.html`, `community-leaders.html`, `pamphlet.html`,
  `poster.html`, `outreach.html`
- Verify every incentive program link is current and actually applies in
  your jurisdiction — programs and rules change, and an assistant's answer
  is only as good as what you or it could confirm
- Confirm you're comfortable with your contact email being public
- Turn on GitHub Pages yourself: **Settings → Pages → Source → GitHub
  Actions** — this is a repo setting the assistant typically cannot change
  on your behalf
