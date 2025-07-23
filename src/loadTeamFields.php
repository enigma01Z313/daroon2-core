<?php
// 1) Hook in: add metabox & enqueue scripts
add_action('add_meta_boxes', 'daroon2_add_team_metabox');
add_action('admin_enqueue_scripts', 'daroon2_enqueue_admin_assets');
add_action('save_post_team', 'daroon2_save_team_details', 10, 2);

/**
 * Register the Team Details metabox.
 */
function daroon2_add_team_metabox()
{
  add_meta_box(
    'daroon2_team_details',
    'Team Details',
    'daroon2_render_team_metabox',
    'team',
    'normal',
    'high'
  );
}

/**
 * Enqueue jQuery for the repeater UI.
 */
function daroon2_enqueue_admin_assets($hook)
{
  if ($hook !== 'post.php' && $hook !== 'post-new.php') {
    return;
  }
  global $post;
  if ($post->post_type !== 'team') {
    return;
  }
  // jQuery is bundled with WP
  wp_enqueue_media(); // Add media uploader scripts
  wp_enqueue_script('daroon2-team-admin', get_template_directory_uri() . '/assets/js/admin.js', ['jquery'], '1.0', true);
}

/**
 * Render the metabox HTML.
 */
function daroon2_render_team_metabox($post)
{
  wp_nonce_field('daroon2_team_details_save', 'daroon2_team_details_nonce');

  // Fetch existing values (or fallback)
  $overview    = get_post_meta($post->ID, 'team_overview', true);
  $experiences = get_post_meta($post->ID, 'team_experience', true);
  $educations  = get_post_meta($post->ID, 'team_education', true);
  $audio_url   = get_post_meta($post->ID, 'team_audio', true);
  $approach    = get_post_meta($post->ID, 'team_approach', true);
  $reviews     = get_post_meta($post->ID, 'team_reviews', true);
  $background  = get_post_meta($post->ID, 'team_background', true);
  $languages   = get_post_meta($post->ID, 'team_languages', true);
  $settings    = get_post_meta($post->ID, 'team_settings', true);
  $custom_style = get_post_meta($post->ID, 'custom_style', true);
  $bookly_staff_list = daroon2_get_bookly_staff();
  $booky_service_list = daroon2_get_bookly_services();
  $has_second_service = isset($settings['team_settings_service_id_2']) && $settings['team_settings_service_id_2']!='' ? true : false;

  // Ensure arrays
  if (! is_array($experiences)) $experiences = [];
  if (! is_array($educations)) $educations  = [];
  if (! is_array($approach)) $approach    = [];
  if (! is_array($reviews)) $reviews     = [];


?>
  <style>
    .daroon2-repeater .row {
      display: flex;
      gap: 8px;
      margin-bottom: 8px;
    }

    .daroon2-repeater .row input {
      flex: 1;
    }

    .daroon2-repeater .remove-row {
      cursor: pointer;
      color: #a00;
    }

    .daroon2-repeater .add-row {
      margin-top: 8px;
      cursor: pointer;
      color: #060;
    }

    #daroon2-team-details {
      display: flex
    }

    #daroon2-team-details nav {
      background-color: #eee
    }

    #daroon2-team-details nav ul {
      margin: 0;
      width: 200px
    }

    #daroon2-team-details nav ul li {
      border-bottom: 1px solid #000;
      padding: 12px 20px;
      cursor: pointer;
      margin-bottom: 0;
      font-weight: 600;
    }

    #daroon2-team-details nav ul li.active {
      background-color: #2271b1;
      color: #fff
    }

    #daroon2-team-details aside {
      flex-grow: 1;
      padding: 16px;
    }

    .d-none {
      display: none
    }

    .audio-upload-wrap {
      padding: 10px;
      background: #f9f9f9;
      border: 1px solid #ddd;
    }

    .audio-upload-wrap .preview-audio {
      margin: 10px 0;
    }

    .audio-upload-wrap .remove-audio {
      color: #a00;
      cursor: pointer;
      margin-left: 10px;
    }

    #item-20>div:not(:last-child) {
      margin-bottom: 16px;
    }

    #item-1:not(.d-none) {
      display: flex;
      gap: 16px;
    }

    #item-1 aside:nth-child(2) {
      width: 2px;
      background-color: #ddd;
      max-width: 2px;
      padding: 0;
    }
  </style>
  <section id="daroon2-team-details">
    <nav>
      <ul>
        <li data-for="item-2" class="active">Overview</li>
        <li data-for="item-1">Background</li>
        <li data-for="item-3">Experience & Research</li>
        <li data-for="item-4">Education</li>
        <li data-for="item-5">Audio</li>
        <li data-for="item-6">Approach</li>
        <li data-for="item-7">Reviews</li>
        <li data-for="item-20">Settings</li>
        <li data-for="item-21">Custom Style</li>
      </ul>
    </nav>
    <aside>
      <div id="item-2">
        <textarea id="team_overview" name="team_overview" rows="4" style="width:100%;"><?php echo esc_textarea($overview); ?></textarea>
      </div>
      <div id="item-1" class="d-none">
        <aside>
          <h3>Therapist Background</h3>
          <div class="daroon2-repeater" data-group="team_background">
            <?php foreach ($background as $idx => $bg) : ?>
              <div class="row">
                <input type="text" name="team_background[]" placeholder="Title" value="<?php echo esc_attr($bg); ?>" />
                <span class="remove-row">✕</span>
              </div>
            <?php endforeach; ?>
            <div class="row">
              <input type="text" name="team_background[]" placeholder="Title" />
              <span class="remove-row">✕</span>
            </div>
            <div class="add-row" data-group="team_background">+ Add Background Item</div>
          </div>
        </aside>
        <aside></aside>
        <aside>
          <h3>Languages</h3>
          <div class="daroon2-repeater" data-group="team_languages">
            <?php foreach ($languages as $idx => $lang) : ?>
              <div class="row">
                <input type="text" name="team_languages[]" placeholder="Language" value="<?php echo esc_attr($lang); ?>" />
                <span class="remove-row">✕</span>
              </div>
            <?php endforeach; ?>
            <div class="row">
              <input type="text" name="team_languages[]" placeholder="Language" />
              <span class="remove-row">✕</span>
            </div>
            <div class="add-row" data-group="team_languages">+ Add Language</div>
          </div>
        </aside>
      </div>
      <div id="item-3" class="d-none">
        <!-- Experience & Research -->
        <div class="daroon2-repeater" data-group="team_experience">
          <div class="row">
            <span style="flex-grow: 1">Title</span>
            <span style="flex-grow: 1">Description</span>
            <span style="flex-grow: 1">Date</span><span></span>
          </div>
          <?php foreach ($experiences as $idx => $exp) : ?>
            <div class="row">
              <input type="text" name="team_experience[<?php echo $idx; ?>][title]" placeholder="Title" value="<?php echo esc_attr($exp['title']); ?>" />
              <input type="text" name="team_experience[<?php echo $idx; ?>][description]" placeholder="Description" value="<?php echo esc_attr($exp['description']); ?>" />
              <input type="text" name="team_experience[<?php echo $idx; ?>][date]" placeholder="Date" value="<?php echo esc_attr($exp['date']); ?>" />
              <span class="remove-row">✕</span>
            </div>
          <?php endforeach; ?>
          <div class="row">
            <input type="text" name="team_experience[<?php echo $idx + 1; ?>][title]" placeholder="Title" />
            <input type="text" name="team_experience[<?php echo $idx + 1; ?>][description]" placeholder="Description" />
            <input type="text" name="team_experience[<?php echo $idx + 1; ?>][date]" placeholder="Date" />
            <span class="remove-row">✕</span>
          </div>
          <div class="add-row" data-group="team_experience">+ Add Experience</div>
        </div>
      </div>
      <div id="item-4" class="d-none">
        <!-- Education -->
        <div class="daroon2-repeater" data-group="team_education">
          <div class="row">
            <span style="flex-grow: 1">Title</span>
            <span style="flex-grow: 1">Description</span>
            <span style="flex-grow: 1">Date</span><span></span>
          </div>
          <?php foreach ($educations as $idx => $ed) : ?>
            <div class="row">
              <input type="text" name="team_education[<?php echo $idx; ?>][title]" placeholder="Title" value="<?php echo esc_attr($ed['title']); ?>" />
              <input type="text" name="team_education[<?php echo $idx; ?>][description]" placeholder="Description" value="<?php echo esc_attr($ed['description']); ?>" />
              <input type="text" name="team_education[<?php echo $idx; ?>][date]" placeholder="Date" value="<?php echo esc_attr($ed['date']); ?>" />
              <span class="remove-row">✕</span>
            </div>
          <?php endforeach; ?>
          <div class="row">
            <input type="text" name="team_education[<?php echo $idx + 1; ?>][title]" placeholder="Title" />
            <input type="text" name="team_education[<?php echo $idx + 1; ?>][description]" placeholder="Description" />
            <input type="text" name="team_education[<?php echo $idx + 1; ?>][date]" placeholder="Date" />
            <span class="remove-row">✕</span>
          </div>
          <div class="add-row" data-group="team_education">+ Add Education</div>
        </div>
      </div>
      <div id="item-5" class="d-none">
        <div class="audio-upload-wrap">
          <input type="hidden" name="team_audio" id="team_audio" value="<?php echo esc_attr($audio_url); ?>" />
          <button type="button" class="button" id="upload_audio_button">Upload Audio</button>
          <?php if ($audio_url): ?>
            <div class="preview-audio">
              <audio controls src="<?php echo esc_url($audio_url); ?>"></audio>
              <span class="remove-audio">✕ Remove</span>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div id="item-6" class="d-none">
        <div class="daroon2-repeater" data-group="team_approach">
          <?php foreach ($approach as $idx => $ap) : ?>
            <div class="row">
              <textarea name="team_approach[<?php echo $idx; ?>]" placeholder="Description" style="width:100%;"><?php echo $ap; ?></textarea>
              <span class="remove-row">✕</span>
            </div>
          <?php endforeach; ?>
          <div class="row">
            <textarea name="team_approach[<?php echo $idx + 1; ?>]" placeholder="Description" style="width:100%;" /></textarea>
            <span class="remove-row">✕</span>
          </div>
          <div class="add-row" data-group="team_approach">+ Add Approach Item</div>
        </div>
      </div>
      <div id="item-7" class="d-none">
        <div class="daroon2-repeater" data-group="team_reviews">
          <div class="row">
            <span style="flex-grow: 1">Title</span>
            <span style="flex-grow: 1">Name</span>
            <span style="flex-grow: 1">Date</span><span></span>
          </div>
          <?php foreach ($reviews as $idx => $ed) : ?>
            <div class="row">
              <div style="display:flex; flex-wrap:wrap; flex-grow: 1">
                <input type="text" name="team_reviews[<?php echo $idx; ?>][title]" placeholder="Title" value="<?php echo esc_attr($ed['title']); ?>" />
                <input type="text" name="team_reviews[<?php echo $idx; ?>][name]" placeholder="Name" value="<?php echo esc_attr($ed['name']); ?>" />
                <input type="text" name="team_reviews[<?php echo $idx; ?>][date]" placeholder="Date" value="<?php echo esc_attr($ed['date']); ?>" />
                <textarea style="margin-top: 8px; margin-bottom: 20px; width: 100%;" name="team_reviews[<?php echo $idx; ?>][description]" placeholder="Description" style="width:100%;"><?php echo $ed['description']; ?></textarea>
              </div>
              <span class="remove-row">✕</span>
            </div>
          <?php endforeach; ?>
          <div class="row">
            <div style="display:flex; flex-wrap:wrap; flex-grow: 1">
              <input type="text" name="team_reviews[<?php echo $idx + 1; ?>][title]" placeholder="Title" />
              <input type="text" name="team_reviews[<?php echo $idx + 1; ?>][name]" placeholder="Name" />
              <input type="text" name="team_reviews[<?php echo $idx + 1; ?>][date]" placeholder="Date" />
              <textarea style="margin-top: 8px; margin-bottom: 20px; width: 100%;" name="team_reviews[<?php echo $idx + 1; ?>][description]" placeholder="Description" style="width:100%;" /></textarea>
            </div>
            <span class="remove-row">✕</span>
          </div>
          <div class="add-row" data-group="team_reviews">+ Add Review</div>
        </div>
      </div>
      <div id="item-20" class="d-none">
        <div class="row">
          <span style="flex-grow: 1">
            <label style="display:flex; align-items:center; gap:8px;">
              <span>Staff Member</span>

              <select name="team_settings_staff_member_id" id="team_settings_staff_member_id">
                <option value="">Select Staff Member</option>
                <?php foreach ($bookly_staff_list as $staff) : ?>
                  <option value="<?= $staff['id']; ?>" <?php echo isset($settings['team_settings_staff_member_id']) && $settings['team_settings_staff_member_id'] == $staff['id'] ? 'selected' : ''; ?>>
                    <?php echo esc_html($staff['full_name']); ?> (<?php echo esc_html($staff['email']); ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
          </span>
        </div>

        <div class="row"> 
          <span style="flex-grow: 1">
            <label style="display:flex; align-items:center; gap:8px;">
              <span>Number of articles published</span>
              <input type="number" name="team_settings_articles" id="team_settings_articles" value="<?php echo esc_attr($settings['team_settings_articles'] ?? 0); ?>" />
            </label>
          </span>
        </div>


        <div class="row">
          <span style="flex-grow: 1">
            <label style="display:flex; align-items:center; gap:8px;">
              <span>Select service</span>

              <select name="team_settings_service_id" id="team_settings_service_id">
                <option value="">Select Service</option>
                <?php foreach ($booky_service_list as $service) : ?>
                  <option value="<?= $service->id; ?>" <?php echo isset($settings['team_settings_service_id']) && $settings['team_settings_service_id'] == $service->id ? 'selected' : ''; ?>>
                    <?php echo esc_html($service->title); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
          </span>
        </div>

        <div class="row">
          <span style="flex-grow: 1">
            <label style="display:flex; align-items:center; gap:8px;">
              <span>Select second service</span>

              <input type="checkbox" id="team_settings_service_id_2_checkbox" <?php echo $has_second_service ? 'checked' : ''; ?>>
              <select name="team_settings_service_id_2" id="team_settings_service_id_2" class="<?php echo $has_second_service ? '' : 'd-none'; ?>">
                <option value="">Select Service</option>
                <?php foreach ($booky_service_list as $service) : ?>
                  <option value="<?= $service->id; ?>" <?php echo isset($settings['team_settings_service_id_2']) && $settings['team_settings_service_id_2'] == $service->id ? 'selected' : ''; ?>>
                    <?php echo esc_html($service->title); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
          </span>
        </div>

        <div class="row">
          <span style="flex-grow: 1">
            <label style="display:flex; align-items:center; gap:8px;">
              <span>Password Protected</span>
              <input type="text" name="team_settings_simyatech_password" value="<?php echo esc_attr($settings['simyatech_password'] ?? ''); ?>" />
            </label>
          </span>
        </div>

        <div class="row">
          <span style="flex-grow: 1">
            <label style="display:flex; align-items:center; gap:8px;">
              <input type="checkbox" name="team_settings_is_private" <?php checked($settings['is_private'] ?? 0); ?> />
              <span>Set Private</span>
            </label>
          </span>
        </div>
      </div>
      <div id="item-21" class="d-none">
        <textarea id="custom_style" name="custom_style" rows="4" style="width:100%;"><?php echo esc_textarea($custom_style); ?></textarea>
      </div>
    </aside>
  </section>

  <script>
    jQuery(document).ready(function($) {
      $('#team_settings_service_id_2_checkbox').click(function() {
        if ($(this).is(':checked')) {
          $('#team_settings_service_id_2').show();
        } else {
          $('#team_settings_service_id_2').val('');
          $('#team_settings_service_id_2').trigger('change');
          $('#team_settings_service_id_2').hide();
        }
      });
    });
  </script>
<?php
}

/**
 * Save the Team Details when the post is saved.
 */
function daroon2_save_team_details($post_id, $post)
{
  // Verify nonce
  if (
    empty($_POST['daroon2_team_details_nonce']) ||
    ! wp_verify_nonce($_POST['daroon2_team_details_nonce'], 'daroon2_team_details_save')
  ) {
    return;
  }
  // Autosave or no permission
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (! current_user_can('edit_post', $post_id)) return;

  //background
  $background = [];
  if (! empty($_POST['team_background']) && is_array($_POST['team_background'])) {
    foreach ($_POST['team_background'] as $row) {
      if (empty($row)) continue;

      $background[] = sanitize_text_field($row);
    }
  }
  update_post_meta($post_id, 'team_background', $background);

  //languages
  $languages = [];
  if (! empty($_POST['team_languages']) && is_array($_POST['team_languages'])) {
    foreach ($_POST['team_languages'] as $row) {
      if (empty($row)) continue;

      $languages[] = sanitize_text_field($row);
    }
  }
  update_post_meta($post_id, 'team_languages', $languages);

  // Overview
  $overview = sanitize_textarea_field($_POST['team_overview'] ?? '');
  update_post_meta($post_id, 'team_overview', $overview);

  // Audio
  $audio_url = esc_url_raw($_POST['team_audio'] ?? '');
  update_post_meta($post_id, 'team_audio', $audio_url);

  // Experience & Research
  $exps = [];
  if (! empty($_POST['team_experience']) && is_array($_POST['team_experience'])) {
    foreach ($_POST['team_experience'] as $row) {
      if (empty($row['title']) && empty($row['description']) && empty($row['date'])) continue;

      $exps[] = [
        'title'       => sanitize_text_field($row['title'] ?? ''),
        'description' => sanitize_text_field($row['description'] ?? ''),
        'date'        => sanitize_text_field($row['date'] ?? ''),
      ];
    }
  }
  update_post_meta($post_id, 'team_experience', $exps);

  // Education
  $eds = [];
  if (! empty($_POST['team_education']) && is_array($_POST['team_education'])) {
    foreach ($_POST['team_education'] as $row) {
      if (empty($row['title']) && empty($row['description']) && empty($row['date'])) continue;

      $eds[] = [
        'title'       => sanitize_text_field($row['title'] ?? ''),
        'description' => sanitize_text_field($row['description'] ?? ''),
        'date'        => sanitize_text_field($row['date'] ?? ''),
      ];
    }
  }
  update_post_meta($post_id, 'team_education', $eds);

  $approaches = [];
  if (! empty($_POST['team_approach']) && is_array($_POST['team_approach'])) {
    foreach ($_POST['team_approach'] as $row) {
      if (empty($row)) continue;
      $approaches[] = sanitize_textarea_field($row);
    }
  }
  update_post_meta($post_id, 'team_approach', $approaches);

  $reviews = [];
  if (! empty($_POST['team_reviews']) && is_array($_POST['team_reviews'])) {
    foreach ($_POST['team_reviews'] as $row) {
      if (empty($row['title']) && empty($row['name']) && empty($row['date']) && empty($row['date'])) continue;

      $reviews[] = [
        'title'       => sanitize_text_field($row['title'] ?? ''),
        'name'        => sanitize_text_field($row['name'] ?? ''),
        'date'        => sanitize_text_field($row['date'] ?? ''),
        'description' => sanitize_textarea_field($row['description'] ?? ''),
      ];
    }
  }
  update_post_meta($post_id, 'team_reviews', $reviews);

  $custom_style = sanitize_textarea_field($_POST['custom_style'] ?? '.points.timeSec:not(.default){display: none !important}');
  update_post_meta($post_id, 'custom_style', $custom_style);

  $settings = [];

  $settings['is_private'] = isset($_POST['team_settings_is_private']) ? 1 : 0;
  $settings['simyatech_password'] = sanitize_text_field($_POST['team_settings_simyatech_password'] ?? '');
  $settings['team_settings_staff_member_id'] = isset($_POST['team_settings_staff_member_id']) && ! empty($_POST['team_settings_staff_member_id']) ? sanitize_text_field($_POST['team_settings_staff_member_id']) : "";
  $settings['team_settings_service_id'] = isset($_POST['team_settings_service_id']) && ! empty($_POST['team_settings_service_id']) ? sanitize_text_field($_POST['team_settings_service_id']) : "61";
  $settings['team_settings_service_id_2'] = isset($_POST['team_settings_service_id_2']) && ! empty($_POST['team_settings_service_id_2']) ? sanitize_text_field($_POST['team_settings_service_id_2']) : "";
  $settings['team_settings_articles'] = isset($_POST['team_settings_articles']) && ! empty($_POST['team_settings_articles']) ? sanitize_text_field($_POST['team_settings_articles']) : 0;

  update_post_meta($post_id, 'team_settings', $settings);
}
?>