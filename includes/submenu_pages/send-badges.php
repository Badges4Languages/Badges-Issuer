<?php
/**
 * Create a submenu page in the administration menu to allow a teacher to send a badge to students.
 *
 * @author     Nicolas TORION
 * @package    badges-issuer-for-wp
 * @subpackage includes/submenu_pages
 * @since      0.3
 */


require_once plugin_dir_path(dirname(__FILE__)) . 'utils/functions.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'utils/class.badge.php';
/**
 * Adds b4l_send_badges_one_student_submenu_page to the admin menu.
 */
add_action('admin_menu', 'send_badges_submenu_page');

/**
 * Creates the submenu page.
 *
 * The capability allows superadmin, admin, editor and author to see this submenu.
 * If you change to change the permissions, use manage_options as capability (for
 * superadmin and admin).
 */
function send_badges_submenu_page() {

    add_submenu_page('edit.php?post_type=badge', 'Send Badges', 'Send Badges', 'capability_send_badge', //capability: 'edit_posts' to give automatically the access to author/editor/admin
        'send-badges', 'send_badges_page_callback');

}

/**
 * Displays the content of the submenu page
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function send_badges_page_callback() {
    global $current_user;
    wp_get_current_user();
    ?>
    <script>
        jQuery(document).ready(function (jQuery) {

        });
    </script>

    <style>
        .tabs-inline li {
            display: inline;
            list-style: none;
        }
    </style>

    <div class="wrap">
        <br><br>
        <h1><i><span class="dashicons dashicons-awards"></span><?php _e('Send Badges', 'badges-issuer-for-wp'); ?></i>
        </h1>
        <h3>Select the possibility to send the badge.</h3>
        <div id="tabs">
            <div id="tabs-elements">
                <div>
                    <h2 class="nav-tab-wrapper">
                        <ul class="tabs-inline">
                            <li><a href="#tabs-1">
                                    <div class="nav-tab nav-tab-active"
                                         id="nav-badge-a"><?php _e('Self', 'badges-issuer-for-wp'); ?></div>
                                </a></li>
                            <?php
                            if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                                ?>
                                <li><a href="#tabs-2">
                                        <div class="nav-tab"
                                             id="nav-badge-b"><?php _e('Issue', 'badges-issuer-for-wp'); ?></div>
                                    </a></li>
                                <?php
                                if (in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                                    ?>
                                    <li><a href="#tabs-3">
                                            <div class="nav-tab"
                                                 id="nav-badge-c"><?php _e('Multiple issue', 'badges-issuer-for-wp'); ?></div>
                                        </a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </h2>
                </div>
            </div>
            <div id="tabs-1">
                <?php tab_self(); ?>
            </div>
            <?php
            if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                ?>
                <div id="tabs-2">
                    <?php tab_issue(); ?>
                </div>
                <?php
                if (in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                    ?>
                    <div id="tabs-3">
                        <?php tab_multiple(); ?>
                    </div>
                    <?php
                }
            } ?>
        </div>
    </div>
    <?php
}

/**
 * The content of the tab for sending a badge to himself.
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function tab_self() {
    apply_css_styles();
    ?>

    <div class="tab-content">
        <form id="badge_form_a" action="" method="post">
            <?php
            global $current_user;
            wp_get_current_user();
            // get all badges that exist
            $badges = get_all_badges();
            ?>
            <div>
                <h3>Field of Education</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select your field of education:</h2></div>
                        <?php
                        $parents = get_languages();
                        $actual_parent = key($parents);
                        display_parents($actual_parent);
                        ?>
                        <div id="field_edu_a"><?php show_all_the_language("", "a"); ?></div>
                    </div>
                </section>
                <h3>Level</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <div id="languages_form_a"><?php display_levels_radio_buttons($badges, "self"); ?></div>
                    </div>
                </section>

                <h3>Kind of badge</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="select_badge">
                            <img src="<?php echo plugins_url('../../assets/default-badge.png', __FILE__); ?>"
                                 width="72px" height="72px"/>
                        </div>
                    </div>
                </section>

                <h3>Language</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Check the language:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="result_languages_description"></div>
                        <div id="result_preview_description"></div>
                    </div>
                </section>

                <h3>Information</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Addition information:</h2></div>
                        <hr class="sep-sendbadge">
                        <label for="comment"><b><?php _e('Comment : ', 'badges-issuer-for-wp'); ?></b></label><br/>
                        <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>4
                    </div>
                </section>
            </div>
        </form>
    </div>


    <?php
    email_engine();
}

/**
 * The content of the tab for sending a badge to someone.
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function tab_issue() {

    apply_css_styles();

    ?>


    <div class="tab-content">
        <form id="badge_form_b" action="" method="post">
            <?php
            global $current_user;
            wp_get_current_user();
            // get all badges that exist
            $badges = get_all_badges();
            ?>
            <div>
                <h3>Field of Education</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select your field of education:</h2></div>
                        <?php
                        $parents = get_languages();
                        $actual_parent = key($parents);
                        display_parents($actual_parent);
                        ?>
                        <div id="field_edu_b"><?php show_all_the_language("", "b"); ?></div>
                    </div>
                </section>
                <h3>Level</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <div id="languages_form_b"><?php display_levels_radio_buttons($badges, "self"); ?></div>
                    </div>
                </section>

                <h3>Kind of badge</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="select_badge">
                            <input type="radio" name="input_badge_name" class="input-badge input-hidden" id="form_a_A1"
                                   value="a1">
                            <label for="form_a_A1">
                                <img id="img-send-badge"
                                     src="<?php echo plugins_url('../../assets/default-badge.png', __FILE__); ?>">
                            </label>
                            <br>
                            <b>A1</b>
                        </div>
                    </div>
                </section>

                <h3>Language</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Check the language:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="result_languages_description"></div>
                        <div id="result_preview_description"></div>
                    </div>
                </section>

                <h3>Class</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Class:</h2></div>
                        <hr class="sep-sendbadge">
                        <?php
                        if (in_array("academy", $current_user->roles) || in_array("teacher", $current_user->roles)) {
                            $class_zero = get_class_teacher($current_user->user_login);
                            echo '<input name="class_teacher" type="hidden" value="' . $class_zero->ID . '"/>';
                        }

                        if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                            echo '<div id="select_class"></div>';
                        }
                        ?>
                    </div>
                </section>

                <h3>Email</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Receiver's mail adress:</h2></div>
                        <hr class="sep-sendbadge">
                        <input class="regular-text try-center" type="text" name="mail" id="mail" class="mail"/>
                    </div>
                </section>

                <h3>Information</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Addition information:</h2></div>
                        <hr class="sep-sendbadge">
                        <label for="comment"><b><?php _e('Comment : ', 'badges-issuer-for-wp'); ?></b></label><br/>
                        <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>
                    </div>
                </section>
            </div>
        </form>
    </div>
    <?php
}

/**
 * The content of the tab for sending a badge to several persons.
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function tab_multiple() {

    apply_css_styles();
    ?>
    <div class="tab-content">
        <br/><br/>
        <h2><?php _e('Send a badge to several persons', 'badges-issuer-for-wp'); ?></h2>

        <form id="badge_form_c" action="" method="post">
            <?php
            global $current_user;
            wp_get_current_user();
            // get all badges that exist
            $badges = get_all_badges();
            ?>
            <br>
            <p>
            <h3>STEP 1: </h3>
            <?php show_all_the_language(); ?>
            </p>

            <br>
            <p>
            <h3>STEP 2: </h3>
            <?php display_levels_radio_buttons($badges, "self"); ?>
            </p>
            <br>
            <h3>STEP 3: </h3>
            <div id="select_badge"><b>Badge*:</b>
                </br></br>
                <img src="<?php echo plugins_url('../../assets/default-badge-thumbnail.png', __FILE__); ?>" width="72px"
                     height="72px">
            </div>
            <br>
            <h3>STEP 4: </h3>
            <div id="result_languages_description"><b>Language of badge description* :</b></div>
            <div id="result_preview_description"></div>

            <h3>STEP 5: </h3>
            <?php
            if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                echo '<div id="select_class"><b>Class*:</b></div>';
                echo '<br />';
            }
            ?>

            <h3>STEP 6: </h3>
            <label for="mail"><b><?php _e('Receivers\' mail adresses* (one mail adress per line) : ', 'badges-issuer-for-wp'); ?></b></label><br/>
            <textarea name="mail" id="mail" class="mail" rows="10" cols="50"></textarea>

            <input type="hidden" name="sender" value="<?php echo $current_user->user_email; ?>"/>
            <br/><br/>
            <h3>STEP 7: </h3>
            <label for="comment"><b><?php _e('Comment : ', 'badges-issuer-for-wp'); ?></b></label><br/>
            <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>

            <input type="submit" id="submit_button_c" class="button-primary"
                   value="<?php _e('Send a badge', 'badges-issuer-for-wp'); ?>"/>
        </form>

    </div>
    <?php
    email_engine();
}

/**
 * This function rappresent the core of email managment
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function email_engine() {

    if (isset($_POST['level']) && isset($_POST['sender']) &&
        isset($_POST['input_badge_name']) && isset($_POST['language']) &&
        isset($_POST['mail']) && isset($_POST['comment']) && isset($_POST['language_description'])) {
        /*
        $url_json_files = content_url('uploads/badges-issuer/json/');
        $path_dir_json_files = plugin_dir_path(dirname(__FILE__)) . '../../../uploads/badges-issuer/json/';

        $badges = get_all_badges();
        $badge_others_items = get_badge($_POST['input_badge_name'], $badges, $_POST['language_description']);
        $certification = get_post_meta($badge_others_items['id'], '_certification', true);

        $mails = $_POST['mail'];
        $mails_list = explode("\n", $mails);

        global $current_user;
        wp_get_current_user();

        $class = null;
        if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
            if (isset($_POST['class_for_student'])) {
                $class = $_POST['class_for_student'];
            } elseif ($_POST['class_zero_teacher']) {
                $class = $_POST['class_zero_teacher'];
            }
        }

        $notsent = array();

        $badge = new Badge($badge_others_items['name'], $_POST['level'], $_POST['language'], $certification, $_POST['comment'], $badge_others_items['description'], $_POST['language_description'], $badge_others_items['image'], $url_json_files, $path_dir_json_files);

        foreach ($mails_list as $mail) {
            $mail = str_replace("\r", "", $mail);

            $badge->create_json_files($mail);

            if (!$badge->send_mail($mail, $class)) {
                $notsent[] = $mail;
            } else {
                if ($_POST['sender'] != "SELF") {
                    $badge->add_student_to_class_zero($mail);
                }

                $badge->add_student_to_class($mail, $class);
                $badge->add_badge_to_user_profile($mail, $_POST['sender'], $class);
            }
        }

        if (sizeof($notsent) > 0) {
            $message = "Badge not sent to these persons : ";
            foreach ($notsent as $notsent_mail) {
                $message = $message . $notsent_mail . " ";
            }
            display_error_message($message);
        } else {
            display_success_message(__("Badge sent to all persons.", 'badges-issuer-for-wp'));
        }
        */
    }
}

add_shortcode('send_badge', 'send_badges_page_callback');
// Adding the shortcode to send the badge to yourself
add_shortcode('send-self', 'tab_self');
// Adding the shortcode to send a badge to a single person
add_shortcode('send-single', 'tab_issue');
// Adding the shortcode to send the badge to several persons
add_shortcode('send-multiple', 'tab_multiple');

?>
