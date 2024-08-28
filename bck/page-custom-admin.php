<?php
/* Template Name: Custom Admin Page */
get_header();

require_once (ABSPATH . 'wp-admin/includes/file.php');
require_once (ABSPATH . 'wp-admin/includes/media.php');
require_once (ABSPATH . 'wp-admin/includes/image.php');

if (isset($_POST['submit'])) {
    // Handle logo upload
    if (!empty($_FILES['custom_logo']['name'])) {
        $uploaded = media_handle_upload('custom_logo', 0);
        if (is_wp_error($uploaded)) {
            echo 'Error uploading logo.';
        } else {
            $custom_logos = get_option('custom_logos', []);
            $custom_logos[] = wp_get_attachment_url($uploaded);
            update_option('custom_logos', $custom_logos);
        }
    }

    // Handle page creation
    if (!empty($_POST['new_page_title']) && !empty($_POST['new_page_content'])) {
        $new_page = array(
            'post_title' => sanitize_text_field($_POST['new_page_title']),
            'post_content' => wp_kses_post($_POST['new_page_content']),
            'post_status' => 'publish',
            'post_type' => 'page',
        );

        $page_id = wp_insert_post($new_page);

        if (is_wp_error($page_id)) {
            echo 'Error creating page.';
        } else {
            $custom_pages = get_option('custom_pages', []);
            $custom_pages[] = $page_id;
            update_option('custom_pages', $custom_pages);

            echo 'Page created successfully. <a href="' . get_permalink($page_id) . '">View Page</a>';
        }
    }
}

// Retrieve the stored logos and pages
$custom_logos = get_option('custom_logos', []);
$custom_pages = get_option('custom_pages', []);
?>

<div class="custom-admin-page container mt-5">
    <h2>Custom Admin Page</h2>

    <!-- Bootstrap Tabs -->
    <ul class="nav nav-tabs" id="adminTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="page-tab" data-bs-toggle="tab" data-bs-target="#page" type="button"
                role="tab" aria-controls="page" aria-selected="true">Add Page</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="logo-tab" data-bs-toggle="tab" data-bs-target="#logo" type="button" role="tab"
                aria-controls="logo" aria-selected="false">Upload Logo</button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabContent">
        <!-- Add Page Tab -->
        <div class="tab-pane fade show active" id="page" role="tabpanel" aria-labelledby="page-tab">
            <form method="post" enctype="multipart/form-data" id="custom-admin-form" class="mt-3">
                <label for="new_page_title">New Page Title:</label>
                <input type="text" name="new_page_title" id="new_page_title" class="form-control">
                <br>
                <label for="new_page_content">New Page Content:</label>
                <textarea name="new_page_content" id="new_page_content" class="form-control"></textarea>
                <br>
                <input type="submit" name="submit" value="Save Page" class="btn btn-primary">
            </form>
        </div>

        <!-- Upload Logo Tab -->
        <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
            <form method="post" enctype="multipart/form-data" id="custom-admin-form" class="mt-3">
                <label for="custom_logo">Upload Logo:</label>
                <input type="file" name="custom_logo" id="custom_logo" class="form-control">
                <br>
                <input type="submit" name="submit" value="Upload Logo" class="btn btn-primary">
            </form>
        </div>
    </div>

    <div id="live-preview" class="mt-5">
        <h2>Live Preview</h2>
        <div id="logo-preview" class="mb-3"></div>
        <div id="page-preview">
            <h3 id="preview-title"></h3>
            <p id="preview-content"></p>
        </div>
    </div>

    <div class="created-content mt-5">
        <h2>Created Pages and Logos</h2>

        <div class="created-pages mb-3">
            <h3>Pages:</h3>
            <ul>
                <?php if (!empty($custom_pages)): ?>
                    <?php foreach ($custom_pages as $page_id): ?>
                        <li><a href="<?php echo get_permalink($page_id); ?>"><?php echo get_the_title($page_id); ?></a></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No pages have been created yet.</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="created-logos">
            <h3>Logos:</h3>
            <?php if (!empty($custom_logos)): ?>
                <?php foreach ($custom_logos as $logo_url): ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Uploaded Logo" style="max-width: 200px;">
                <?php endforeach; ?>
            <?php else: ?>
                <p>No logos have been uploaded yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('custom_logo').addEventListener('change', function (event) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('logo-preview').innerHTML = '<img src="' + e.target.result + '" alt="Logo Preview">';
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    document.getElementById('new_page_title').addEventListener('input', function () {
        document.getElementById('preview-title').innerText = this.value;
    });

    document.getElementById('new_page_content').addEventListener('input', function () {
        document.getElementById('preview-content').innerText = this.value;
    });
</script>

<?php
get_footer();
?>