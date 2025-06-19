<?php

class EF_Excel_Importer_Field extends EF_Field_Base {

    public function __construct($id, $name, $description = '') {
        parent::__construct($id, $name, $description);
        add_filter('upload_mimes', array($this, 'upload_mimes'));
        add_action('wp_ajax_ef_excel_import', array($this, 'ef_excel_import'));
        add_action('wp_ajax_nopriv_ef_excel_import', array($this, 'ef_excel_import'));
    }

    public $type = 'excel-importer';

    public function display() {
        $defaults = array(
            'section_prefix' => 'section_prefix_',
            'class'          => 'ef-section ef-excel-importer',
            'style'          => '',
            'id_prefix'      => 'id_prefix',
            'selector'       => '',
            'button_text'    => 'Import'
        );

        $args = wp_parse_args($this->args, $defaults);
        extract($args);
        ?>
        <div id="ef-importer-<?php echo $this->id; ?>" class="import-success"></div>
        <section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
            <div class="ajax-loader"></div>
            <div class="import-excel-section">
                <label for="import-excel-performers">Import Performers (view <a href="<?php echo get_template_directory_uri(); ?>/misc/excel/performers.xlsx" target="_blank">example</a>)</label>
                <p class="message"></p>
                <input id="" class="ef-upload-button button" type="button" value="Select" />
                <input type="hidden" class="ef-upload-button-hidden" value="" />
                <input id="" class="ef-import-button button" type="button" value="Import" data-type="performer"/>
            </div>
            <div class="import-excel-section">
                <label for="import-excel-sessions">Import Sessions (view <a href="<?php echo get_template_directory_uri(); ?>/misc/excel/sessions.xlsx" target="_blank">example</a>)</label>
                <p class="message"></p>
                <input id="" class="ef-upload-button button" type="button" value="Select" />
                <input type="hidden" class="ef-upload-button-hidden" value="" />
                <input id="" class="ef-import-button button" type="button" value="Import" data-type="session" />
            </div>
            <div class="import-excel-section">
                <label for="import-excel-pois">Import Points of Interest (view <a href="<?php echo get_template_directory_uri(); ?>/misc/excel/pois.xlsx" target="_blank">example</a>)</label>
                <p class="message"></p>
                <input id="" class="ef-upload-button button" type="button" value="Select" />
                <input type="hidden" class="ef-upload-button-hidden" value="" />
                <input id="" class="ef-import-button button" type="button" value="Import" data-type="poi"/>
            </div>
            <div class="import-excel-section">
                <label for="import-excel-sponsors">Import Sponsors (view <a href="<?php echo get_template_directory_uri(); ?>/misc/excel/sponsors.xlsx" target="_blank">example</a>)</label>
                <p class="message"></p>
                <input id="" class="ef-upload-button button" type="button" value="Select" />
                <input type="hidden" class="ef-upload-button-hidden" value="" />
                <input id="" class="ef-import-button button" type="button" value="Import" data-type="sponsor" />
            </div>
        </section>
        <script>
            jQuery(document).ready(function () {
                jQuery('.ef-excel-importer .ef-upload-button').click(function (e) {
                    var custom_uploader;
                    var that = this;
                    e.preventDefault();
                    if (custom_uploader) {
                        custom_uploader.open();
                        return;
                    }
                    // Add the uploader as a wp.media object
                    custom_uploader = wp.media.frames.file_frame = wp.media({
                        title: 'Select File',
                        button: {
                            text: 'Select File'
                        },
                        multiple: false
                    });

                    // Use the upload image as the text field value
                    custom_uploader.on('select', jQuery.proxy(function () {
                        attachment = custom_uploader.state().get('selection').first().toJSON();
                        jQuery(this).next('.ef-upload-button-hidden').val(attachment.id);
                        jQuery(this).next('.ef-upload-button-hidden').trigger('change');
                    }, that));

                    //Open the uploader dialog
                    custom_uploader.open();
                });
                jQuery('.ef-excel-importer .ef-import-button').click(function (e) {
                    var control = this;
                    e.preventDefault();
                    jQuery(this).closest('section').find('.message').html('');
                    jQuery(this).closest('section').find('.ajax-loader').show();
                    jQuery.post(ajaxurl, {
                        action: 'ef_excel_import',
                        data: {
                            type: jQuery(this).attr('data-type'),
                            file: jQuery(this).prev('.ef-upload-button-hidden').val()
                        }
                    }, function (status) {
                        var message;
                        if (status.ret == 0) {
                            message = 'Error: Excel data NOT imported';
                        } else {
                            message = 'Excel data imported successfully';
                        }
                        jQuery(control).closest('.import-excel-section').find('.message').html(message);
                        jQuery('.ajax-loader').hide();
                    }
                    );
                });
            });
        </script>
        <?php
    }

    public function upload_mimes($existing_mimes) {
        $existing_mimes['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        return $existing_mimes;
    }

    public function ef_excel_import() {
        $ret  = array(
            'result' => 0
        );
        $data = $_POST['data'];
        if (!empty($data) && !empty($data['type']) && !empty($data['file'])) {
            $xlsx = new SimpleXLSX(get_attached_file($data['file']), false);
            if ($xlsx->success()) {
                $sheets = $xlsx->sheetNames();
                $rows   = null;
                foreach ($sheets as $key => $sheetName) {
                    $rows = $xlsx->rows($key);
                    break;
                }
                if (!empty($rows) && count($rows) > 1) {
                    $i = 0;
                    foreach ($rows as $row) {
                        if ($i++ == 0) {
                            continue;
                        }
                        switch ($data['type']) {
                            case 'sponsor':
                                if (!empty($row[0])) {
                                    $id = wp_insert_post(array(
                                        'post_content' => $row[1],
                                        'post_title'   => $row[0],
                                        'post_type'    => 'sponsor',
                                        'post_status'  => 'publish'
                                    ));
                                    if (!is_wp_error($id) && $id != 0) {
                                        update_post_meta($id, 'sponsor_link', $row[2]);
                                        $categories = $row[3];
                                        if (!empty($categories)) {
                                            $terms_array = array();
                                            $cat_array   = explode('|', $categories);
                                            if (!empty($cat_array)) {
                                                foreach ($cat_array as $cat_name) {
                                                    $term = get_term_by('name', $cat_name, 'sponsor-tier');
                                                    if ($term === false) {
                                                        list($term_id, $term_taxonomy_id) = wp_insert_term($cat_name, 'sponsor-tier');
                                                        $terms_array[] = $term_id;
                                                    } else {
                                                        $terms_array[] = $term->term_id;
                                                    }
                                                }
                                            }
                                            if (!empty($terms_array)) {
                                                wp_set_object_terms($id, $terms_array, 'sponsor-tier');
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'poi':
                                if (!empty($row[0])) {
                                    $id = wp_insert_post(array(
                                        'post_title'  => $row[0],
                                        'post_type'   => 'poi',
                                        'post_status' => 'publish'
                                    ));
                                    if (!is_wp_error($id) && $id != 0) {
                                        
                                        if (!empty($row[1])) {                                         
                                            
                                            $categories = $row[2];
                                            if (!empty($categories)) {
                                                $terms_array = array();
                                                $cat_array   = explode('|', $categories);
                                                if (!empty($cat_array)) {
                                                    foreach ($cat_array as $cat_name) {
                                                        $term = get_term_by('name', $cat_name, 'poi-group');
                                                        if ($term === false) {
                                                            list($term_id, $term_taxonomy_id) = wp_insert_term($cat_name, 'poi-group');
                                                            $terms_array[] = $term_id;
                                                        } else {
                                                            $terms_array[] = $term->term_id;
                                                        }
                                                    }
                                                }
                                                if (!empty($terms_array)) {
                                                    wp_set_object_terms($id, $terms_array, 'poi-group');
                                                }
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'performer':
                                if (!empty($row[0])) {
                                    $id = wp_insert_post(array(
                                        'post_content' => $row[1],
                                        'post_title'   => $row[0],
                                        'post_type'    => 'speaker',
                                        'post_status'  => 'publish'
                                    ));
                                    if (!is_wp_error($id) && $id != 0) {
                                        if (strtolower($row[2]) == 'yes') {
                                            update_post_meta($id, 'speaker_keynote', 1);
                                        } else {
                                            update_post_meta($id, 'speaker_keynote', 0);
                                        }
                                    }
                                }
                                break;
                            case 'session':
                                if (!empty($row[0])) {
                                    $id = wp_insert_post(array(
                                        'post_content' => $row[1],
                                        'post_title'   => $row[0],
                                        'post_type'    => 'session',
                                        'post_status'  => 'publish'
                                    ));
                                    if (!is_wp_error($id) && $id != 0) {
                                        if (strtolower($row[2]) == 'yes') {
                                            update_post_meta($id, 'session_home', 1);
                                        } else {
                                            update_post_meta($id, 'session_home', 0);
                                        }
                                        update_post_meta($id, 'session_date', strtotime($row[3]));
                                        update_post_meta($id, 'session_time', $row[4]);
                                        update_post_meta($id, 'session_end_time', $row[5]);
                                        update_post_meta($id, 'session_registration_title', $row[6]);
                                        update_post_meta($id, 'session_registration_text', $row[7]);
                                        update_post_meta($id, 'session_registration_code', $row[8]);
                                        // locations
                                        $locations = $row[9];
                                        if (!empty($locations)) {
                                            $terms_array = array();
                                            $cat_array   = explode('|', $locations);
                                            if (!empty($cat_array)) {
                                                foreach ($cat_array as $cat_name) {
                                                    $term = get_term_by('name', $cat_name, 'session-location');
                                                    if ($term === false) {
                                                        list($term_id, $term_taxonomy_id) = wp_insert_term($cat_name, 'session-location');
                                                        $terms_array[] = $term_id;
                                                    } else {
                                                        $terms_array[] = $term->term_id;
                                                    }
                                                }
                                            }
                                            if (!empty($terms_array)) {
                                                wp_set_object_terms($id, $terms_array, 'session-location');
                                            }
                                        }
                                        // tracks
                                        $tracks = $row[10];
                                        if (!empty($tracks)) {
                                            $terms_array = array();
                                            $cat_array   = explode('|', $tracks);
                                            if (!empty($cat_array)) {
                                                foreach ($cat_array as $cat_name) {
                                                    $term = get_term_by('name', $cat_name, 'session-track');
                                                    if ($term === false) {
                                                        list($term_id, $term_taxonomy_id) = wp_insert_term($cat_name, 'session-track');
                                                        $terms_array[] = $term_id;
                                                    } else {
                                                        $terms_array[] = $term->term_id;
                                                    }
                                                }
                                            }
                                            if (!empty($terms_array)) {
                                                wp_set_object_terms($id, $terms_array, 'session-track');
                                            }
                                        }
                                        // performers
                                        $performers = $row[11];
                                        if (!empty($performers)) {
                                            $speakers_array = array();
                                            $speak_array    = explode('|', $performers);
                                            if (!empty($speak_array)) {
                                                foreach ($speak_array as $speak_name) {
                                                    $speaker = get_page_by_title($speak_name, 'OBJECT', 'speaker');
                                                    if ($speaker) {
                                                        $speakers_array[] = $speaker->ID;
                                                    }
                                                }
                                            }
                                            if (!empty($speakers_array)) {
                                                update_post_meta($id, 'session_speakers_list', $speakers_array);
                                            }
                                        }
                                    }
                                }
                                break;
                        }
                    }
                    $ret['result'] = 1;
                }
            }
        }

        echo json_encode($ret);
        die;
    }

}
