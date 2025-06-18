<?php
// Get Theme Name
$theme_name                   = strtolower(EF_Framework_Helper::get_theme_name());
$themes_request               = wp_remote_get('http://www.showthemes.com/?action=get_themes_ads');
$themes_featured_text_request = wp_remote_get('http://www.showthemes.com/?action=get_themes_featured_text');

if (!empty($themes_request) && !empty($themes_request['body'])) {
    $themes = json_decode($themes_request['body']);
} else {
    $themes = array();
}
if (!empty($themes_featured_text_request) && !empty($themes_featured_text_request['body'])) {
    $themes_featured_text = json_decode($themes_featured_text_request['body']);
} else {
    $themes_featured_text = '';
}
?>
<div class="wrap">
    <div class="custom-wrapper">
        <div class="ef-theme-otherthemes">
            <div class="logo">
                <img src="http://www.showthemes.com/ads/logo.png" alt="Showthemes" />
            </div>
            <h3 class="description">Find out about our other themes</h3>
            <div class="featured themes">
                <?php if (!empty($themes->themes)) { ?>
                    <div class="image-container">
                        <a href="<?php echo $themes->themes[0]->url; ?>" target="_blank">
                            <img width="370" height="176" src="<?php echo $themes->themes[0]->picture; ?>" alt="<?php echo $themes->themes[0]->name; ?>" />
                        </a>
                    </div>
                    <div class="theme-details active">
                        <span class="theme-name"><?php echo $themes->themes[0]->name; ?></span>
                        <a class="button" target="_blank" href="http://www.showthemes.com/my-account/?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd<?php //echo $themes->themes[0]->url; ?>">Buy Now</a>
                    </div>
                <?php } ?>
            </div>
            <div class="featured-text">
                <?php echo $themes_featured_text; ?>
            </div>
            <ul class="themes">
                <?php
                if (!empty($themes->themes)) {
                    array_shift($themes->themes);
                    foreach ($themes->themes as $theme) {
                        ?>
                        <li class="<?php echo str_replace(' ', '-', strtolower($theme->name)); ?>" data-theme="<?php echo strtolower($theme->name); ?>">
                            <div class="image-container">
                                <a href="<?php echo $theme->url; ?>" target="_blank">
                                    <img width="370" height="176" src="<?php echo $theme->picture; ?>" alt="<?php echo $theme->name; ?>" />
                                </a>
                            </div>
                            <div class="theme-details active">
                                <span class="theme-name"><?php echo $theme->name; ?></span>
                                <a class="button" target="_blank" href="<?php echo $theme->url; ?>">Buy Now</a>
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div><!-- end of .ef-theme-otherthemes -->
    </div><!-- end of .custom-wrapper -->
</div><!-- end of #wrap -->
<script type="text/javascript">
    jQuery(function () {
        jQuery('.ef-theme-otherthemes .themes li[data-theme=<?php echo $theme_name; ?>]').hide();
    });
</script>