<?php

namespace templates;

use Inc\Pages\Admin;
use Inc\Utils\WPBadge;

/**
 * Template class that shows all the badges created with
 * that plugin.
 *
 * All the content to show in the front-end is wrapped in the __() function
 * for internationalization purposes
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class BadgesTemp {

    /**
     * Show all the badge that exist, with the opportunity
     * to click on them and see the information.
     */
    public function main() {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
        $badges = WPBadge::getAll();
        ?>
        <div class="wrap">
            <h1><?php _e('All Badges','open-badges-framework'); ?></h1>
            <?php if( sizeof($badges) <= 0 ){ ?>
                <p style="margin-bottom: 0px; margin-top: 0px;"><?php _e('In this section, you will see all the badges available.','open-badges-framework'); ?> <a href="<?php echo admin_url('post-new.php?post_type=open-badge');?>"><?php _e('Add the first one.','open-badges-framework'); ?></a></p>
            <?php } else{ ?>
                <p style="margin-bottom: 0px; margin-top: 0px;"><?php _e('In this section, you can see all the badges available.','open-badges-framework'); ?></p>
            <?php } ?>
        <?php
        if( sizeof($badges) > 0 ){ ?>
            <section class="user-badges-cont">
                <div class="title-badges-cont">
                    <h3><?php _e('Number of Badges : ','open-badges-framework'); ?><?php echo sizeof($badges)?></h3>
                </div>
                <div class="user-badges flex-container">
                    <?php
                    foreach ($badges as $badge) { ?>
                        <div class="badge flex-item">
                            <a class="wrap-link"
                               href="<?php echo admin_url('admin.php?page=' . Admin::PAGE_SINGLE_BADGES, $protocol) . "&badge=$badge->ID&db=0"; ?>">
                                <div class="cont-img-badge">
                                    <img class="circle-img" src="<?php echo WPBadge::getUrlImage($badge->ID); ?>">
                                </div>
                                <div>
                                    <span><?php echo $badge->post_title; ?></span>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </section>
        <?php } ?>
        </div>
        <?php
    }
}
