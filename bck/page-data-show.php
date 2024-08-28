<?php
/* Template Name: Fetched Commands */
get_header();

// Fetch all stored page IDs and logos
$custom_pages = get_option('custom_pages', []);
$custom_logos = get_option('custom_logos', []);
?>

<div class="fetched-commands container">
    <h2 class="mt-5">Fetched Pages and Logos</h2>

    <!-- Bootstrap Tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pages-tab" data-bs-toggle="tab" data-bs-target="#pages" type="button"
                role="tab" aria-controls="pages" aria-selected="true">Pages</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="logos-tab" data-bs-toggle="tab" data-bs-target="#logos" type="button"
                role="tab" aria-controls="logos" aria-selected="false">Logos</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- Pages Tab -->
        <div class="tab-pane fade show active" id="pages" role="tabpanel" aria-labelledby="pages-tab">
            <div class="fetched-pages mt-3">
                <h3>Created Pages:</h3>
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
        </div>

        <!-- Logos Tab -->
        <div class="tab-pane fade" id="logos" role="tabpanel" aria-labelledby="logos-tab">
            <div class="fetched-logos mt-3">
                <h3>Uploaded Logos:</h3>
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
</div>

<?php if (function_exists('get_field')): ?>
    <?php $favorite_quote = get_field('favorite_quote'); ?>
    <?php if ($favorite_quote): ?>
        <div class="favorite-quote">
            <h3>Favorite Quote:</h3>
            <p><?php echo esc_html($favorite_quote); ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>


<?php
get_footer();
?>