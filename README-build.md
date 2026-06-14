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

1. Copy the Ottawa config:

   ```bash
   cp configs/neighbourhood.yaml configs/yourcity.yaml
   ```

2. Edit `configs/yourcity.yaml`. At minimum, set:
   - `site.title`, `site.base_url`
   - `location.city`, `location.province_or_state`
   - `contact.email`
   - `registration.form_url`
   - `programs` — replace with programs relevant to your jurisdiction

3. Build:

   ```bash
   python build.py --config configs/yourcity.yaml --output _site
   ```

4. Deploy to your own GitHub Pages repo, or any static host.

### To deploy a different city via GitHub Actions

Edit `.github/workflows/deploy.yml` and change:

```yaml
run: python build.py --config configs/neighbourhood.yaml --output _site
```

to:

```yaml
run: python build.py --config configs/yourcity.yaml --output _site
```

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

MIT. Fork freely.
