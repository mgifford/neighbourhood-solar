# Neighbourhood Solar & Energy Initiative

A static site template for community-led neighbourhood solar and home energy upgrade pilots.

Built to be forked. One YAML config file per city. No JavaScript in the output. No CDN dependencies. No tracking.

## Pages

| File | Audience |
|---|---|
| `index.html` | Residents |
| `contractors.html` | Solar and home energy contractors |
| `community-leaders.html` | Neighbourhood and community leaders |

## How it works

A Python build script reads a YAML config file and renders Jinja2 HTML templates into a `_site/` directory. GitHub Actions deploys `_site/` to GitHub Pages on every push to `main`.

No client-side JavaScript. No external fonts. No analytics. No cookies. Output is plain HTML and CSS.

## Repository structure

```
neighbourhood-solar/
├── configs/
│   └── neighbourhood.yaml   # Default configuration (Ottawa pilot)
├── templates/
│   ├── base.html            # Shared layout: head, nav, footer
│   ├── index.html           # Residents page
│   ├── contractors.html     # Contractors page
│   └── community-leaders.html
├── styles.css               # Shared stylesheet
├── build.py                 # Build script
├── requirements.txt
└── .github/
    └── workflows/
        └── deploy.yml       # GitHub Actions: build + deploy to Pages
```

## Quick start — Ottawa

```bash
git clone https://github.com/mgifford/neighbourhood-solar
cd neighbourhood-solar
pip install -r requirements.txt
python build.py
# output is in _site/
```

Open `_site/index.html` in a browser to preview.

## Adapting for another city

This is meant to be forked. Everything visitor-facing is driven by one YAML
config file — you should not need to touch the Jinja2 templates to launch a
pilot for your own street or town.

### 1. Fork and clone

On GitHub, click **Fork** on [mgifford/neighbourhood-solar](https://github.com/mgifford/neighbourhood-solar),
then clone your fork:

```bash
git clone https://github.com/YOUR-USERNAME/neighbourhood-solar
cd neighbourhood-solar
pip install -r requirements.txt
```

### 2. Copy the config

```bash
cp configs/neighbourhood.yaml configs/yourcity.yaml
```

Keep the original Ottawa config in place — it's a working reference example
you can compare against.

### 3. Edit every field in `configs/yourcity.yaml`

Go through the file top to bottom. It's organized into commented sections;
at minimum, fill in:

| Section | Field | Notes |
|---|---|---|
| `site` | `title`, `short_title`, `tagline`, `description` | Rewrite for your city |
| `site` | `base_url` | Your GitHub Pages URL (or custom domain), no trailing slash |
| `site` | `github_url` | Your fork's URL, or leave blank to hide the footer link |
| `location` | `city`, `province_or_state`, `country`, `country_code`, `region_label` | These fill in throughout the page copy automatically |
| `contact` | `email` | Shown on all pages — do not leave the placeholder |
| `registration` | `form_url` | A Google Form, Airtable, or similar sign-up link |
| `programs` | full list | Replace with the incentive programs that apply in your jurisdiction |
| `contractors` | leave `[]` | Only add entries once a contractor has consented to being listed |
| `cohort` | thresholds, progress numbers | Your real (or initial estimated) sign-up numbers |
| `content` | `local_context`, `local_climate_note`, `disclaimer` | Optional, but worth localizing — otherwise generic fallback text is used |
| `city_plans` | list of local climate/energy plans | Optional; leave the list empty to hide the section |
| `pamphlet` | headline, worries, steps, `map_svg` | Controls the printable flyer/poster — see below |
| `maintainer` | your name/org | Not rendered publicly, just for your own reference |

**Watch for `pamphlet.map_svg`:** the default config includes a hand-drawn
SVG map of Ottawa's Centretown neighbourhood. It's optional — either replace
it with your own neighbourhood's streets, or delete the `map_svg` key
entirely to omit the map from the printable pamphlet and poster.

### 4. Check for anything you missed

Search your new config and the `templates/` directory for leftover
references to Ottawa or any other placeholder city name:

```bash
grep -rni "ottawa" configs/yourcity.yaml templates/
```

The templates pull city name, plan names, and program names entirely from
config, so this should only turn up the unmodified `configs/neighbourhood.yaml`
reference file — not your own config or the templates themselves. If it does
turn up template text, please open an issue; that indicates a page wasn't
fully localized to `location.city`.

### 5. Build and preview locally

```bash
python build.py --config configs/yourcity.yaml --output _site
```

Open `_site/index.html`, `_site/contractors.html`, `_site/community-leaders.html`,
`_site/pamphlet.html`, `_site/poster.html`, and `_site/outreach.html` in a
browser and read through them. The build script will also print warnings if
`contact.email`, `registration.form_url`, or `site.base_url` still look like
placeholders.

### 6. Point GitHub Actions at your config

Edit `.github/workflows/deploy.yml` (and `.github/workflows/link-check.yml`,
if you want the weekly broken-link check too) and change:

```yaml
run: python build.py --config configs/neighbourhood.yaml --output _site
```

to:

```yaml
run: python build.py --config configs/yourcity.yaml --output _site
```

### 7. Turn on GitHub Pages

In your fork's repo: **Settings → Pages → Source**, choose **GitHub Actions**.
Push to `main` and the workflow builds and deploys automatically. Your site
will be live at `https://YOUR-USERNAME.github.io/neighbourhood-solar/` (or
your renamed repo).

Using a custom domain instead? Add a `CNAME` file to the repo root with your
domain, point its DNS at GitHub Pages, and update `site.base_url` in your
config to match — otherwise canonical URLs and Open Graph tags will point to
the wrong place.

### 8. Deploy anywhere else (optional)

Nothing here is GitHub-specific. `_site/` after a build is a plain static
directory — you can host it on Netlify, Cloudflare Pages, S3, or any web
server that serves static files.

## Config reference

See `configs/neighbourhood.yaml` for a fully commented example. Key fields:

| Field | Required | Description |
|---|---|---|
| `site.title` | Yes | Full site title including city name |
| `site.base_url` | Yes | Canonical base URL, no trailing slash |
| `location.city` | Yes | Used throughout prose and page titles |
| `contact.email` | Yes | Shown on all three pages |
| `registration.form_url` | Recommended | Google Form, Airtable, etc. |
| `registration.spreadsheet_url` | Optional | Public anonymized sign-up list |
| `programs` | Recommended | Incentive programs for your jurisdiction |
| `contractors` | Optional | Only list after consent is confirmed |
| `content.local_context` | Optional | Override the "why this matters" paragraph |
| `content.local_climate_note` | Optional | Override the climate paragraph on community-leaders page |

## Deployment via GitHub Pages

1. Push the repository to GitHub.
2. In repo Settings → Pages → Source, choose **GitHub Actions**.
3. Push to `main`. The workflow builds and deploys automatically.

## Sustainability

This site is designed to have a low environmental footprint:

- System font stack — no web font requests
- No JavaScript in the output
- No CDN dependencies in the output
- No analytics or tracking
- No cookies
- Plain HTML and CSS — fast on slow connections
- Static hosting — no server compute per request

## Accessibility

- `lang` attribute set from config locale
- Skip navigation link on every page
- `aria-current="page"` on active nav link
- Semantic HTML throughout
- No JavaScript required for any content

## Principles

This project is resident-led, vendor-neutral, and commission-free. See `community-leaders.html` for the full principles statement.

## Licence

GNU Affero General Public License v3.0 (AGPL v3.0). Fork freely, modify, and distribute under the condition that your modifications remain open source.
