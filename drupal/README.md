# Managed file `#states` test recipe — Drupal core issue [#2847425]

A Drupal **recipe** plus a tiny reproduction **module** that make it quick to
manually test and review [#2847425 — *#states not affecting visibility/requirement
of managed_file*](https://www.drupal.org/project/drupal/issues/2847425)
(and the current [MR !7305](https://git.drupalcode.org/project/drupal/-/merge_requests/7305)).

Core already ships a *test-only* module for this (`file_test_states`, added in the
MR), but it is only enabled while the JavaScript test suite runs and lives at
`/file-test-states-form` behind `_access: 'TRUE'`. Reviewers doing a **manual**
pass (e.g. quietone in comment #54) have had trouble reproducing by hand. This
recipe installs an equivalent form on an ordinary site so you can click through
every case, then apply the MR and click through again.

## The bug, in one paragraph

`#states` (Form API's conditional `visible` / `invisible` / `required`) is ignored
on `managed_file` elements. On page load a file field that should be hidden stays
visible, and a field that should be required never gets its marker. Root cause:
the `managed_file` markup did not carry the `data-drupal-states` attribute that
`states.js` needs, and `template_preprocess_file_managed_file()` reset
`$variables['attributes']`. Swapping `#type => managed_file` for `#type => file`
has always worked — that contrast is Scenario 1 below.

## Layout

```text
drupal/
├── recipes/
│   └── managed_file_states_test/
│       └── recipe.yml
└── modules/
    └── managed_file_states_test/
        ├── managed_file_states_test.info.yml
        ├── managed_file_states_test.routing.yml
        ├── managed_file_states_test.links.menu.yml
        └── src/Form/ManagedFileStatesTestForm.php
```

Targets **Drupal 12 / `main`** (`core_version_requirement: ^12`). If your checkout
still reports as `11.x-dev`, widen that key to `^11.2 || ^12` in the `.info.yml`.

## Install on your DDEV `drupal-core` checkout

From your Drupal root (`/Users/mgifford/drupal-core`, where `core/` sits at the
top level):

```bash
# 1. Copy the module and recipe into the checkout.
cp -R /path/to/neighbourhood-solar/drupal/modules/managed_file_states_test \
      modules/custom/managed_file_states_test
cp -R /path/to/neighbourhood-solar/drupal/recipes/managed_file_states_test \
      recipes/managed_file_states_test

# 2. Apply the recipe (installs the file module + this module, grants access).
ddev exec php core/scripts/drupal recipe recipes/managed_file_states_test
ddev drush cache:rebuild
```

Then open <https://drupal-core.ddev.site/managed-file-states-test> (also linked
from the *Tools* menu).

**Prefer no recipe?** Just enable the module:

```bash
ddev drush en managed_file_states_test -y && ddev drush cr
```

## What to look at — Steps to Reproduce, mapped

The form is four fieldsets. Toggle the trigger in each and watch the
`managed_file` elements. Test **twice**: once on plain `main` (you should see the
bug), once with MR !7305 applied (fixed).

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
