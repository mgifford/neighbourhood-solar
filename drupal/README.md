# Managed file `#states` test harness — Drupal core issue [#2847425]

> **Note:** this is **Drupal core** material that only lives in this repo as a
> transport. It has nothing to do with the neighbourhood-solar project — copy
> the files into a Drupal checkout (below) and delete the branch afterward.

A reproduction **module** laid out exactly like core's own test modules
(`core/modules/file/tests/modules/…`), plus an optional **recipe**, for manually
testing and reviewing [#2847425 — *#states not affecting visibility/requirement
of managed_file*](https://www.drupal.org/project/drupal/issues/2847425) and
[MR !7305](https://git.drupalcode.org/project/drupal/-/merge_requests/7305).

The MR already ships a *test-only* module (`file_test_states`) that drives one
automated JavaScript test. This `file_managed_states_test` module is its manual
companion: enable it, open one page, and click through every failing case —
including the ones the JS test does not cover.

## The bug, in one paragraph

`#states` (Form API's conditional `visible` / `invisible` / `required`) is ignored
on `managed_file` elements. On page load a file field that should be hidden stays
visible, and a field that should be required never gets its marker. Root cause:
the `managed_file` markup did not carry the `data-drupal-states` attribute that
`states.js` needs, and `template_preprocess_file_managed_file()` reset
`$variables['attributes']`. Swapping `#type => managed_file` for `#type => file`
has always worked — that contrast is Scenario 1 below.

## Layout (mirrors a Drupal core checkout)

```text
drupal/
├── core/modules/file/tests/modules/file_managed_states_test/
│   ├── file_managed_states_test.info.yml          (version: VERSION, package: Testing)
│   ├── file_managed_states_test.routing.yml       (/file-managed-states-test, _access: TRUE)
│   └── src/Form/FileManagedStatesTestForm.php
└── recipes/managed_file_states_test/
    └── recipe.yml
```

Because it lives under `core/`, the `.info.yml` follows the core test-module
convention: `version: VERSION`, `package: Testing`, and **no**
`core_version_requirement` (core supplies the version). That also sidesteps the
version-pinning problem from earlier attempts.

## Install on your DDEV `drupal-core` checkout

The paths in `drupal/` mirror the checkout, so this is a straight overlay copy.
Run from your Mac (paste the block as-is — no inline `#` comments, which zsh
would treat as commands):

```bash
cp -R /tmp/ns-recipe/drupal/core/modules/file/tests/modules/file_managed_states_test \
      /Users/mgifford/drupal-core/core/modules/file/tests/modules/
cp -R /tmp/ns-recipe/drupal/recipes/managed_file_states_test \
      /Users/mgifford/drupal-core/recipes/
cd /Users/mgifford/drupal-core
ddev exec php core/scripts/dr recipe recipes/managed_file_states_test
ddev drush cr
```

(`/tmp/ns-recipe` is the temp clone of this branch; adjust if you put it
elsewhere.) Then open <https://drupal-core.ddev.site/file-managed-states-test>.

**Prefer no recipe?** Once the module directory is copied in, just enable it:

```bash
ddev drush en file_managed_states_test -y && ddev drush cr
```

## What to look at — Steps to Reproduce, mapped

Four fieldsets. Toggle the trigger in each and watch the `managed_file` elements.
Test **twice**: once on plain `main` (you should see the bug), once with MR !7305
applied (fixed).

| Scenario | Trigger | Buggy behaviour (no MR) | Fixed behaviour (MR) |
|---|---|---|---|
| **1. Canonical** (select → `audio` managed_file, from the issue summary) | Select **Audio** | File stays visible on load; no required marker. The plain `#type => file` control beside it already behaves. | File hidden until *Audio*, then shown + marked required. |
| **2. Checkbox toggle** (mirrors core's `file_test_states` JS test) | Check *Toggle fields* | Initially-visible/hidden/optional files ignore the checkbox. | Visibility and the required marker follow the checkbox. |
| **3. Inside a `details`** (comments #74/#75) | Check *Show the attachment* | Whole fieldset (including the "always visible" text field) is set `display:none`. | Only the file element toggles; the rest of the fieldset stays. |
| **4. File already uploaded** (comments #39/#96) | Upload a file, then toggle | — | Exercises the `fids` branch: `#states` moves to the `fids` element and the label `for=""` is repointed. Confirm the label/id still match. |

Scenario 3 is the accessibility case worth dwelling on: hiding an entire fieldset
with `display:none` removes it from the accessibility tree for everyone, screen
reader users included (WCAG 3.3.1, tag `wcag331`).

## Code-review notes for MR !7305

Files the MR touches, and what to verify against this harness:

- **`core/modules/file/src/Element/ManagedFile.php`** (`processManagedFile()`) —
  builds an `Attribute` for the AJAX wrapper and attaches `data-drupal-states`.
  Two branches to confirm on the running form:
  - no file yet → `#states` copied onto `$element['upload']` → **Scenarios 1–3**;
  - file present → `#states` copied onto `$element['fids']` and `$element['#id']`
    aliased to the `fids` id so the label's `for=""` resolves → **Scenario 4**.
    Inspect the rendered `<label for>` / input `id` pair here.
- **`core/modules/file/src/Hook/FileThemeHooks.php`** (`preprocessManagedFile()`) —
  the fix is `$variables['attributes'] ??= [];` instead of `= []`. Check nothing
  else in that preprocess still clobbers attributes set upstream.
- **Tests** — `file_test_states` module + `FileManagedStateTest` (JS) + the
  `FormTest` disabled-element count bump (44 → 45). Note the JS test covers
  Scenario 2 only; **the fieldset case (Scenario 3) has no automated coverage** —
  the harness is how you check it. Worth flagging on the MR whether that case
  deserves a test.

Two behaviours that are **not** bugs in the MR, so don't file them as review
blockers:

- `#states` `required` only paints the asterisk; it does **not** enforce
  server-side validation (agreed "works as designed", comments #65/#88). The
  server-side gap is the separate follow-up
  [#3513308](https://www.drupal.org/project/drupal/issues/3513308). Scenario 2's
  optional field and the submit message make this observable.
- The `main` rebase churn in the MR history (thousands of merge commits) is
  noise from long-running branch maintenance, not part of the change.

[#2847425]: https://www.drupal.org/project/drupal/issues/2847425
