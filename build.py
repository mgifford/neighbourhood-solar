#!/usr/bin/env python3
"""
build.py — Neighbourhood Solar static site builder

Usage:
    python build.py                                    # uses configs/neighbourhood.yaml by default
    python build.py --config configs/toronto.yaml
    python build.py --config configs/neighbourhood.yaml --output _site

Reads a YAML config file, renders Jinja2 HTML templates, and copies static
assets into the output directory. No JavaScript, no external dependencies
at runtime. The output is plain HTML and CSS.

Sustainability notes:
  - No client-side JavaScript in the output
  - No external font or CDN requests in the output
  - System font stack used in styles.css
  - No analytics or tracking pixels injected
  - Build dependencies are minimal (see requirements.txt)
"""

import argparse
import io
import os
import re
import shutil
import sys
from pathlib import Path

import qrcode
import qrcode.image.svg
import yaml
from jinja2 import Environment, FileSystemLoader, select_autoescape


# ---------------------------------------------------------------------------
# Argument parsing
# ---------------------------------------------------------------------------

def parse_args():
    parser = argparse.ArgumentParser(description="Build neighbourhood-solar static site.")
    parser.add_argument(
        "--config",
        default="configs/neighbourhood.yaml",
        help="Path to YAML config file (default: configs/neighbourhood.yaml)",
    )
    parser.add_argument(
        "--output",
        default=None,
        help="Output directory. Overrides the output_dir value in the config.",
    )
    parser.add_argument(
        "--templates",
        default="templates",
        help="Path to Jinja2 templates directory (default: templates)",
    )
    return parser.parse_args()


# ---------------------------------------------------------------------------
# Config loading
# ---------------------------------------------------------------------------

def load_config(config_path: str) -> dict:
    path = Path(config_path)
    if not path.exists():
        sys.exit(f"Error: config file not found: {config_path}")
    with open(path, "r", encoding="utf-8") as f:
        config = yaml.safe_load(f)
    if not config:
        sys.exit(f"Error: config file is empty or invalid: {config_path}")
    return config


def validate_config(config: dict):
    """Warn about likely missing values without blocking the build."""
    warnings = []
    email = config.get("contact", {}).get("email", "")
    if not email or "example" in email:
        warnings.append("contact.email is still set to a placeholder.")
    form_url = config.get("registration", {}).get("form_url", "")
    if not form_url:
        warnings.append("registration.form_url is empty. The register section will show a fallback message.")
    base_url = config.get("site", {}).get("base_url", "")
    if not base_url:
        warnings.append("site.base_url is empty. Canonical URLs will be incomplete.")
    if warnings:
        print("\nConfiguration warnings:")
        for w in warnings:
            print(f"  ! {w}")
        print()


# ---------------------------------------------------------------------------
# Jinja2 environment
# ---------------------------------------------------------------------------

def qr_code_filter(url: str) -> str:
    """Generate a compact SVG QR code for the given URL."""
    if not url:
        return ""

    factory = qrcode.image.svg.SvgPathImage
    qr = qrcode.QRCode(
        version=1,
        error_correction=qrcode.constants.ERROR_CORRECT_L,
        box_size=10,
        border=1,
    )
    qr.add_data(url)
    qr.make(fit=True)
    img = qr.make_image(image_factory=factory)

    stream = io.BytesIO()
    img.save(stream)
    svg_string = stream.getvalue().decode('utf-8')

    if svg_string.startswith("<?xml"):
        idx = svg_string.find("<svg")
        if idx != -1:
            svg_string = svg_string[idx:]

    svg_string = svg_string.replace("<svg ", '<svg class="qr-code" ', 1)
    return svg_string


def build_env(templates_dir: str) -> Environment:
    env = Environment(
        loader=FileSystemLoader(templates_dir),
        autoescape=select_autoescape(["html"]),
    )
    # Custom filter: URL-encode a string for use in mailto: subject lines
    env.filters["urlencode"] = lambda s: s.replace(" ", "%20").replace("&", "%26")
    env.filters["qr_code"] = qr_code_filter
    return env


# ---------------------------------------------------------------------------
# Page rendering
# ---------------------------------------------------------------------------

PAGES = [
    {
        "template": "index.html",
        "output": "index.html",
        "current_page": "index",
        "page_file": "index.html",
    },
    {
        "template": "contractors.html",
        "output": "contractors.html",
        "current_page": "contractors",
        "page_file": "contractors.html",
    },
    {
        "template": "community-leaders.html",
        "output": "community-leaders.html",
        "current_page": "community-leaders",
        "page_file": "community-leaders.html",
    },
    {
        "template": "pamphlet.html",
        "output": "pamphlet.html",
        "current_page": "pamphlet",
        "page_file": "pamphlet.html",
    },
    {
        "template": "outreach.html",
        "output": "outreach.html",
        "current_page": "outreach",
        "page_file": "outreach.html",
    },
]


def render_pages(env: Environment, config: dict, output_dir: Path):
    for page in PAGES:
        tmpl = env.get_template(page["template"])
        context = {
            **config,
            "current_page": page["current_page"],
            "page_file": page["page_file"],
        }
        html = tmpl.render(**context)
        out_path = output_dir / page["output"]
        out_path.write_text(html, encoding="utf-8")
        print(f"  built: {out_path}")


# ---------------------------------------------------------------------------
# Static asset copying
# ---------------------------------------------------------------------------

STATIC_FILES = [
    "styles.css",
]


def copy_static(source_root: Path, output_dir: Path):
    for filename in STATIC_FILES:
        src = source_root / filename
        if src.exists():
            shutil.copy2(src, output_dir / filename)
            print(f"  copied: {output_dir / filename}")
        else:
            print(f"  warning: static file not found, skipping: {src}")


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------

def main():
    args = parse_args()

    # Resolve paths relative to the script location
    script_dir = Path(__file__).parent.resolve()
    config_path = script_dir / args.config
    templates_dir = script_dir / args.templates

    print(f"Config:    {config_path}")
    print(f"Templates: {templates_dir}")

    config = load_config(str(config_path))
    validate_config(config)

    # Output directory: CLI arg > config value > default "_site"
    if args.output:
        output_dir = Path(args.output)
    else:
        output_dir = script_dir / config.get("site", {}).get("output_dir", "_site")

    output_dir.mkdir(parents=True, exist_ok=True)
    print(f"Output:    {output_dir}\n")

    env = build_env(str(templates_dir))
    render_pages(env, config, output_dir)
    copy_static(script_dir, output_dir)

    print(f"\nDone. {len(PAGES)} pages built in {output_dir}/")


if __name__ == "__main__":
    main()
