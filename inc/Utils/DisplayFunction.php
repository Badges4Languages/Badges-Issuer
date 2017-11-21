<?php
/**
 * The DisplayFunction Class, contain all the function to show
 * information
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace inc\Utils;

class DisplayFunction {

    /**
     * Displays available FIELD in a select tag. Used in the forms sending badges to students.
     *
     * @author Nicolas TORION
     * @since  0.6.1
     * @since  0.6.3 recreated the function more simply
     * @since  x.x.x
     *
     * @param string $parent permit to display the child taxonomy of the parent taxonomy (category).
     */
    public static function field($p_parent = "") {
        $field = new Fields();

        $selectionContOpen = '<div class="select-field"> <select name="field" id="field"> <option value="Select" selected disabled hidden>Select</option>';
        $selectionContClose = '</select></div>';

        if (!$field->haveChildren()) {
            $languages = $field->main;

            echo $selectionContOpen;

            foreach ($languages as $language) {
                echo '<option value="' . $language->term_id . '">';
                echo $language->name . '</option>';
            }

            echo $selectionContClose;

        } else {
            //If there parent with children
            if ($p_parent === "") {
                // Display the DEFAULT parent
                $parents = $field->sub;
                echo $selectionContOpen;

                foreach ($parents as $parent) {
                    foreach ($parent as $language) {
                        echo '<option value="' . $language->term_id . '">';
                        echo $language->name . '</option>';
                    }
                    break;
                }
                echo $selectionContClose;

            } else if ($p_parent === "all_field") {
                // Display ALL the child
                $parents = $field->sub;

                echo $selectionContOpen;

                foreach ($parents as $parent) {
                    foreach ($parent as $language) {
                        echo '<option value="' . $language->term_id . '">';
                        echo $language->name . '</option>';
                    }
                }
                echo $selectionContClose;

            } else {
                // Display the children of the right PARENT
                $parents = $field->sub;

                echo $selectionContOpen;
                foreach ((array)$parents[$p_parent] as $language) {
                    echo '<option value="' . $language->term_id . '">';
                    echo $language->name . '</option>';
                }

                echo $selectionContClose;

            }

        }
    }


    /**
     * Displays all the parents like a button that permit you
     * to change the visualization of the Fields of education
     *
     * @author Alessandro RICCARDI
     * @since  0.6.3
     *
     */
    public static function display_parents() {
        $parents = get_languages();
        $actual_parent = key($parents);
        $haveCat = false;

        if ($parents = get_parent_categories()) {

            echo '<div class="btns-parent-field">';

            foreach ($parents as $parent) {
                $haveCat = true;
                if ($parent[2] == $actual_parent) {
                    echo '<a class="btn btn-default btn-xs display_parent_categories active" id="' . $parent[2] . '">Display ' . $parent[1] . '</a>';
                } else {
                    echo '<a class="btn btn-default btn-xs display_parent_categories" id="' . $parent[2] . '">Display ' . $parent[1] . '</a>';
                }
            }

            // Display the link to show all the languages
            if ($haveCat) {
                echo '<a class="btn btn-default btn-xs display_parent_categories" id="all_field">Display all Fields</a>';
            }
        }
    }

    /**
     * Displays a message of success.
     *
     * @author Nicolas TORION
     * @since  0.3
     *
     * @param $message The message to display.
     */
    public static function display_success_message($message) {
        ?>
        <div class="message msg-success">
            <?php echo $message; ?>
        </div>
        <?php
    }

    /**
     * Displays a message of error.
     *
     * @author Nicolas TORION
     * @since  0.3
     *
     * @param $message The message to display.
     */
    public static function display_error_message($message) {
        ?>
        <div class="message error">
            <?php echo $message; ?>
        </div>
        <?php
    }

    public static function display_sendBadges_info($message) {
        echo '<div class="lead">' . $message . '</div> <hr class="hr-sb">';
    }

    /**
     * Displays a message indicating that a person is not logged. A link redirecting to the login page is also
     * displayed.
     *
     * @author Nicolas TORION
     * @since  0.6.3
     */
    public static function display_not_logged_message() {
        $settings_id_login_links = get_settings_login_links();
        ?>

        <center>
            <img src="<?php echo plugins_url('../../assets/b4l_logo.png', __FILE__); ?>" width="256px"
                 height="256px"/>
            <br/>
            <h1><?php _e('To get a badge, you need to be logged on the site.', 'open-badge-framework'); ?></h1>
            <br/>
            <a href="<?php echo get_page_link($settings_id_login_links["link_register"]); ?>"
               title="Register"><?php _e('Register', 'open-badge-framework'); ?></a> | <a
                    href="<?php echo get_page_link($settings_id_login_links["link_login"]); ?>"
                    title="Login"><?php _e('Login', 'open-badge-framework'); ?></a>
            <p style="color:red;">
                <?php
                _e('Once connected to the site, go back to your email and click again on the link for receiving your badge.', 'open-badge-framework');
                ?>
            </p>
        </center>
        <?php
    }
}

?>
