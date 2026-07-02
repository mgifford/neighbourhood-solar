# Contributing to Neighbourhood Solar

Thank you for your interest in contributing to Neighbourhood Solar! This project is a community-driven initiative for resident-led, vendor-neutral energy transition in neighbourhoods.

## Welcome

We're a small project with a big mission. We welcome contributions from developers, community organizers, and anyone who shares our vision of equitable, transparent energy adoption. Contributions can be code, documentation, issues, or just spreading the word.

## Ways to Contribute

### Code Contributions
- Fix bugs in the build script or templates
- Add new config fields or template pages
- Improve documentation or examples
- Add tests for the build process
- Port to new platforms or frameworks

### Non-Code Contributions
- Report bugs or suggest features
- Improve documentation
- Test builds in different environments
- Help with accessibility checks
- Review and improve pull requests

## Getting Started

### Prerequisites

```bash
pip install -r requirements.txt
```

### Local Development

1. Fork and clone the repository
2. Copy and configure the Ottawa config:

   ```bash
   cp configs/neighbourhood.yaml configs/yourcity.yaml
   ```

3. Edit `configs/yourcity.yaml`
4. Build locally:

   ```bash
   python build.py --config configs/yourcity.yaml --output _site
   ```

5. Preview:

   ```bash
   open _site/index.html
   ```

### Testing

No formal tests exist yet, but you can:

- Build with different configs to verify rendering
- Check output in a browser for broken links
- Verify accessibility with automated tools
- Test with `--help` flag to ensure build script works

## Reporting Issues

Please use the issue tracker for:

- Bug reports
- Feature requests
- Documentation improvements
- Build problems

Include:
- Steps to reproduce
- What you expected vs. what happened
- Browser/version information (if applicable)
- Any relevant logs or screenshots

## Making Changes

### Pull Requests

1. Create a topic branch from `main`
2. Commit your changes with descriptive messages
3. Follow the existing code style
4. Ensure your commit messages convey **what and why**, not **how**

### Code Style

- Python: 100 character line limit, follow PEP 8
- Jinja2: Consistent spacing, use `{% %}` tags appropriately
- Markdown: Keep it simple, use descriptive headings
- No trailing whitespace

### File Changes

- Never commit secrets or credentials
- Configurations go in `configs/` directory
- Templates go in `templates/` directory
- No JavaScript in output, no CDN dependencies

## Pull Request Process

1. **Review existing work** - Check if someone is already working on it
2. **Fork and branch** - Create a clean branch from `main`
3. **Implement** - Make your changes
4. **Test** - Build locally and verify
5. **Document** - Update READMEs or add comments
6. **Submit** - Create a pull request
7. **Review** - Respond to feedback, iterate as needed

## Code of Conduct

This project follows the principle: "Be nice to users. Be nice to developers. Be nice to the planet."

- Be respectful and inclusive
- Provide constructive feedback
- Focus on solutions, not personalities
- Help others learn and grow

## Attribution

This project was inspired by our work with neighbourhood energy transition in Ottawa. We're grateful to:

- Our first hosts: ottawa-solar.ca
- Current pilot site maintainers
- Open source contributors (generator attribution in HTML footer)

## Licence

MIT. Fork freely. Drop a link back if you find it useful.
