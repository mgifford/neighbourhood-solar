<?php

declare(strict_types=1);

namespace Drupal\managed_file_states_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Reproduction harness for issue #2847425.
 *
 * "#states not affecting visibility/requirement of managed_file"
 * https://www.drupal.org/project/drupal/issues/2847425
 *
 * Unlike core's test-only module (file_test_states, added in MR !7305, reachable
 * only at /file-test-states-form when running JS tests), this module can be
 * enabled on an ordinary site so a reviewer can click through every failing
 * case by hand. Each scenario below is annotated with the behaviour to expect
 * before and after the MR, and with the code path in
 * \Drupal\file\Element\ManagedFile::processManagedFile() that it exercises.
 *
 * How to read the results:
 * - WITHOUT the MR applied, the managed_file elements ignore #states: they stay
 *   visible on load and never gain the required marker.
 * - WITH the MR applied, they behave like any other element — except that
 *   "required" only paints the red asterisk; it does NOT enforce server-side
 *   validation. That is documented as "works as designed" (#65, #88); the
 *   server-side gap is the separate follow-up #3513308.
 */
class ManagedFileStatesTestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'managed_file_states_test_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $upload_location = 'public://managed-file-states-test';

    $form['intro'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('Reproduction harness for <a href="@url">issue #2847425 — #states not affecting visibility/requirement of managed_file</a>. Toggle each trigger and watch the managed_file elements below it. Without the fix these ignore #states; with the fix they behave like any other element.', [
        '@url' => 'https://www.drupal.org/project/drupal/issues/2847425',
      ]),
    ];

    // ------------------------------------------------------------------
    // Scenario 1 — the canonical example from the issue summary.
    // A select controls an "audio" managed_file that should be both visible
    // and required only when "audio" is chosen. A plain #type => file element
    // with identical #states is shown alongside as the control: the summary
    // notes that "simply changing managed_file to file brings the expected
    // behavior", so this pair makes the difference obvious.
    // Exercises: the $element['upload']['#states'] branch (no file uploaded).
    // ------------------------------------------------------------------
    $form['scenario_1'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Scenario 1 — canonical example (select → managed_file)'),
    ];
    $form['scenario_1']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Content type'),
      '#options' => [
        'video' => $this->t('Video'),
        'audio' => $this->t('Audio'),
        'post' => $this->t('Text'),
      ],
      '#empty_option' => $this->t('- Select -'),
    ];
    $form['scenario_1']['audio'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Audio file (managed_file — the buggy element)'),
      '#description' => $this->t('Expected: hidden until "Audio" is selected, then shown and marked required. Buggy behaviour: always visible, never marked required.'),
      '#upload_location' => $upload_location,
      '#states' => [
        'visible' => [':input[name="type"]' => ['value' => 'audio']],
        'required' => [':input[name="type"]' => ['value' => 'audio']],
      ],
    ];
    $form['scenario_1']['audio_control'] = [
      '#type' => 'file',
      '#title' => $this->t('Audio file (plain file — the control)'),
      '#description' => $this->t('Identical #states on a plain #type => file element. This one already works, which is the contrast the issue summary describes.'),
      '#states' => [
        'visible' => [':input[name="type"]' => ['value' => 'audio']],
        'required' => [':input[name="type"]' => ['value' => 'audio']],
      ],
    ];

    // ------------------------------------------------------------------
    // Scenario 2 — mirrors core's file_test_states module (MR !7305), so the
    // manual result can be compared to the automated FileManagedStateTest.
    // A single checkbox drives three managed_file elements.
    // ------------------------------------------------------------------
    $form['scenario_2'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Scenario 2 — checkbox toggle (mirror of core JS test)'),
    ];
    $form['scenario_2']['toggle'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Toggle fields'),
    ];
    $form['scenario_2']['managed_file_initially_visible'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Managed file — initially visible'),
      '#description' => $this->t('Visible while the checkbox is unchecked; should hide when checked.'),
      '#upload_location' => $upload_location,
      '#states' => [
        'visible' => [':input[name="toggle"]' => ['checked' => FALSE]],
      ],
    ];
    $form['scenario_2']['managed_file_initially_hidden'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Managed file — initially hidden'),
      '#description' => $this->t('Hidden until the checkbox is checked.'),
      '#upload_location' => $upload_location,
      '#states' => [
        'visible' => [':input[name="toggle"]' => ['checked' => TRUE]],
      ],
    ];
    $form['scenario_2']['managed_file_initially_optional'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Managed file — initially optional'),
      '#description' => $this->t('Gains the required marker when the checkbox is checked (cosmetic only — no server-side enforcement, per #3513308).'),
      '#upload_location' => $upload_location,
      '#states' => [
        'required' => [':input[name="toggle"]' => ['checked' => TRUE]],
      ],
    ];

    // ------------------------------------------------------------------
    // Scenario 3 — the fieldset regression (#74) and its accessibility impact
    // (#75, WCAG 3.3.1). states.js walks up to the closest
    // .js-form-item / .js-form-wrapper, which for a managed_file inside a
    // details/fieldset resolves to the WHOLE wrapper — so the entire fieldset
    // is set to display:none, hiding sibling content from everyone, including
    // screen reader users. This is the case not covered by the core JS test,
    // and the one most worth eyeballing in review.
    // ------------------------------------------------------------------
    $form['scenario_3'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Scenario 3 — managed_file inside a details element (fieldset regression)'),
      '#description' => $this->t('Watch whether toggling hides only the file element or collapses this entire section. Collapsing the whole wrapper with display:none removes it from the accessibility tree — the concern raised in comments #74/#75.'),
    ];
    $form['scenario_3']['show_details'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show the attachment inside the details element below'),
    ];
    $form['scenario_3']['details'] = [
      '#type' => 'details',
      '#title' => $this->t('Attachment details'),
      '#open' => TRUE,
    ];
    $form['scenario_3']['details']['always_here'] = [
      '#type' => 'textfield',
      '#title' => $this->t('This field should always stay visible'),
      '#description' => $this->t('If this field disappears when you toggle the checkbox, the whole fieldset was hidden — the bug in #74.'),
    ];
    $form['scenario_3']['details']['attachment'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Attachment (managed_file with #states, inside details)'),
      '#upload_location' => $upload_location,
      '#states' => [
        'visible' => [':input[name="show_details"]' => ['checked' => TRUE]],
      ],
    ];

    // ------------------------------------------------------------------
    // Scenario 4 — the "file already uploaded" branch. Once a file is present,
    // processManagedFile() has no 'upload' input to attach #states to, so the
    // MR instead moves #states onto the 'fids' element and repoints the label's
    // for="" at it (#39, #96). To review this branch: upload a file here, then
    // toggle — the states must still apply with the file present.
    // ------------------------------------------------------------------
    $form['scenario_4'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Scenario 4 — states after a file is already uploaded'),
      '#description' => $this->t('Upload a file first, then toggle the checkbox. This exercises the code path where #states is attached to the "fids" element and the label\'s for="" is repointed. Confirm the label still associates correctly (inspect the for/id pair) and that the required marker toggles.'),
    ];
    $form['scenario_4']['toggle_uploaded'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Require / show the uploaded file'),
      '#default_value' => TRUE,
    ];
    $form['scenario_4']['uploaded'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('File (upload one, then toggle)'),
      '#upload_location' => $upload_location,
      '#states' => [
        'visible' => [':input[name="toggle_uploaded"]' => ['checked' => TRUE]],
        'required' => [':input[name="toggle_uploaded"]' => ['checked' => TRUE]],
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Report submitted values so the "required is cosmetic only" behaviour is
    // observable: the form submits even when a #states-required file is empty.
    $this->messenger()->addStatus($this->t('Form submitted. Note: any file left empty despite a #states "required" marker still passed submission — #states "required" is cosmetic and does not enforce server-side validation (see follow-up #3513308).'));
  }

}
