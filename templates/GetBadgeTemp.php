<?php

namespace templates;

use Inc\Base\BaseController;
use Inc\Utils\WPUser;
use Inc\Pages\Admin;
use Inc\Utils\Badge;
use Inc\Utils\JsonManagement;
use Inc\Utils\WPBadge;

/**
 * Template for the Get Badge page.
 * In this class are defined all the function that permit
 * to follow all the step to get a badge.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
final class GetBadgeTemp extends BaseController {
    const START = 0;
    const ERROR_JSON = 1;
    const ERROR_LINK = 2;
    const GOT = 3;
    const PREVIEW = 4;

    private $badgeDB = null;

    private $badgeWP = null;
    private $fieldWP = null;
    private $levelWP = null;

    /**
     * Singleton function to get the instance of the class.
     *
     * @return null|GetBadgeTemp
     */
    public static function getInstance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new Self();
        }
        return $inst;
    }

    /**
     * In this function, for the first thing are check the parameters in
     * the url string and then based on the kind of return it will show
     * the right view.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function main() {
        $res = $this->loadParm();

        switch ($res) {
            case self::START:
                $this->getStartingStep();
                break;
            case self::GOT:
                $this->showBadgeGot();
                break;
            case self::PREVIEW:
                $this->showMessage(self::PREVIEW);
                break;
            case self::ERROR_JSON:
                $this->showMessage(self::ERROR_JSON);
                break;
            case self::ERROR_LINK:
                $this->showMessage(self::ERROR_LINK);
                break;
        }
    }

    /**
     * Check the parameters if they're right and then load the
     * information in variables.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return string   START when we can start with the procedure;
     *                  GOT if is already got the badge;
     *                  PREVIEW if the page is only a preview to show that works;
     *                  ERROR_JSON if the json is not stored in the server;
     *                  ERROR_LINK if the link have problems.
     */
    private function loadParm() {

        if (isset($_GET['v']) && !empty($_GET['v'])) {
            $this->badgeDB = new Badge();
            $this->badgeDB->retrieveBadge($_GET['v']);
            if ($this->badgeDB->creationDate) {
                $this->badgeWP = WPBadge::get($this->badgeDB->idBadge);
                $this->fieldWP = get_term($this->badgeDB->idField, Admin::TAX_FIELDS);
                $this->levelWP = get_term($this->badgeDB->idLevel, Admin::TAX_LEVELS);

                if (!$this->badgeDB->gotMozillaDate) {
                    //Everything OK
                    return self::START;
                } else {
                    //Badge already GOT
                    return self::GOT;
                }
            } else {
                return self::ERROR_LINK;
            }

        } else {
            //Formation of the link not for get the badge
            if (isset($_GET['preview']) && $_GET['preview'] == 1) {
                // Preview mode
                return self::PREVIEW;
            } else {
                // Broken link
                return self::ERROR_LINK;
            }
        }
    }

    /**
     * Show the starting step to get the badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function getStartingStep() {
        $this->obf_header();
        ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
                <div class="container inner">
                    <div class="cont-title">New badge</div>
                </div>
            </header>

            <main role="main" class="inner cover">
                <div class="container">
                    <h1 class="badge-title-obf cover-heading">
                        <strong><?php echo $this->badgeWP->post_title; ?></strong>
                    </h1>
                    <h5 class="badge-field">Field of education: <strong><?php echo $this->fieldWP->name; ?></strong> -
                        Level:
                        <strong><?php echo $this->levelWP->name; ?></strong></h5>
                    <p class="lead">
                        <?php echo $this->badgeWP->post_content; ?>
                    </p>
                    <div class="logo-badge-cont">
                        <img src="<?php echo WPBadge::getUrlImage($this->badgeWP->ID); ?>" height="100px"
                             width="100px">
                    </div>
                </div>
            </main>

            <footer class="mastfoot">
                <!--<p><?php echo JsonManagement::getJsonUrl($this->idDbBadge); ?></p>-->
                <div class="inner">
                    <p class="lead">
                        <button id="gb-continue" class="btn btn-lg btn-secondary" type="submit">Continue</button>
                    </p>
                </div>
            </footer>

        </div>

        <?php
        $this->obf_footer();
    }

    /**
     * Show login step.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param string $email that the user need to use for the login
     */
    public function showTheLoginContent($email) { ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
            </header>

            <main role="main" class="inner cover">
                <div class="container cont-form">
                    <form method="post" id="gb-form-login" class="gb-form">
                        <h2 class="form-signin-heading">Please sign in</h2>
                        <label for="inputEmail" class="sr-only">Email address / Username</label>
                        <input type="text" readonly class="form-control" id="staticEmail"
                               value="<?php echo $email; ?>">
                        <label for="inputPassword" class="sr-only">Password</label>
                        <input type="password" id="inputPassword" class="form-control" placeholder="Password"
                               required="">
                        <div class="checkbox">
                            <label>
                                <input id="inputRemember" type="checkbox" value="remember-me"> Remember me
                            </label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in
                        </button>
                    </form>
                </div>
            </main>

            <footer class="mastfoot">
                <div class="inner">
                    <div id="gb-resp-login">

                    </div>
                </div>
            </footer>

        </div>

        <?php
    }

    /**
     * Show register page step.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param string $email that the user need to use for the registration
     */
    public function showRegisterPage($email) { ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
                <div class="inner">
                    <div class="cont-title">Registration</div>
                </div>
            </header>

            <main role="main" class="inner cover registration">
                <div class="container">
                    <form id="gb-form-registration" id="needs-validation" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="reg-first-name" placeholder="First name"
                                       required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="reg-last-name" placeholder="Last name"
                                       required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="text" readonly class="form-control" id="reg-email"
                                       value="<?php echo $email; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="reg-user-name" placeholder="Username"
                                       required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="reg-pass" placeholder="Password"
                                       required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="reg-repeat-pass"
                                       placeholder="Repeat password" required>
                            </div>
                        </div>
                        <div class="cont-btn-form-reg">
                            <div id="gb-resp-register">

                            </div>
                            <input type="submit" id="submit-form" style="display: none;"/>
                        </div>
                    </form>
                </div>
            </main>

            <footer class="mastfoot">
                <div class="inner">
                    <label id="lbl-submit-form" class="btn btn-primary btn-lg" for="submit-form"
                           tabindex="0">Register</label>
                </div>
            </footer>
        </div>

        <?php
    }

    /**
     * Show Mozilla Open Badge step.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param $gotPartial true if he got the badge but without certification from Mozilla Open Badge |
     *                    False if he need to take also the partial.
     */
    public function showMozillaOpenBadges($gotPartial = false) { ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
                <div class="inner container">
                    <div class="ob-menu">
                        <span class="ob-cont-title">Mozilla Open Badges</span>
                        <span class="ob-user-info">
                                <?php echo get_avatar(WPUser::getCurrentUser()->ID); ?>
                                <?php echo WPUser::getCurrentUser()->user_login; ?>
                            </span>
                    </div>
                </div>
            </header>

            <main role="main" class="inner cover">
                <div class="container">
                    <div class="jumbotron jumbotron-fluid jumb-obm">
                        <p class="lead">
                            Mozilla Open Badges give you the opportunity to store your badge in its platform to permit
                            to
                            show your progress with all the community.
                            <br><br>
                            If you don’t have an Open Badge account, please click
                            <a href="https://backpack.openbadges.org/backpack/signup" target="_blank"
                               style="font-size: 25px;">here</a>
                            and create a new account with the same email address of the registration of this website and
                            then <strong>get the badge</strong>.

                        </p>
                        <div class="cont-btn-standar">
                            <button id="gb-ob-get-badge" class="btn btn-lg btn-primary" type="submit">Get the Badge
                            </button>
                        </div>
                        <div id="gb-ob-response">
                        </div>
                    </div>
                    <?php
                    if (!$gotPartial) {
                        ?>
                        <button id="gb-get-standard" class="btn-link" type="submit">
                            or skip the process and get anyway the Badge
                        </button>
                        <?php
                    } else { ?>
                        <a class="btn-link" href="<?php echo get_bloginfo('url'); ?>"
                           role="button">or go to the home page</a>
                        <?php
                    }
                    ?>
                </div>
            </main>

            <footer class="mastfoot">
                <div class="inner">
                    <div class="logo-open-badges">
                        <img src="<?php echo $this->plugin_url; ?>/assets/images/open-badges-mz-logo.png">
                    </div>
                </div>
            </footer>

        </div>

        <?php
    }

    /**
     * Show Conclusions step.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function showConclusionsStep() {
        ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
                <div class="inner container">
                    <div class="cont-title">Congratulations!</div>
                </div>
            </header>

            <main role="main" class="inner cover">
                <div class="container">
                    <h1 class="cong-title-obf cover-heading">
                        <?php echo WPUser::getCurrentUser()->first_name . ", "; ?>you just added a new badge!
                    </h1>
                    <div class="container cont-button-redirect">
                        <div class="row justify-content-around">
                            <div class="col-3">
                                <a class="btn btn-redirect" href="<?php echo get_bloginfo('url'); ?>"
                                   role="button">Home</a>
                            </div>
                        </div>
                    </div>
                    <div class="container">
            </main>

            <footer class="mastfoot">
                <div class="inner">

                </div>
            </footer>
        </div>
        <?php
    }

    /**
     * Show Badge Got step to inform that you're already took the badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    private function showBadgeGot() {
        $this->obf_header()
        ?>
        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div id="gb-wrap" class="cover-container">

                    <header class="masthead clearfix">
                        <?php echo $this->getInfoHeader(); ?>
                        <div class="inner container">
                        </div>
                    </header>

                    <main role="main" class="inner cover">
                        <div class="container">
                            <div class="logo-badge-got-cont">
                                <img src="<?php echo get_the_post_thumbnail_url($this->badgeWP->ID) ?>" height="100px"
                                     width="100px">
                            </div>

                            <h4 class="">
                                <strong><?php echo $this->badgeWP->post_title; ?></strong>
                            </h4>
                            <h5 class="badge-field">Field: <strong><?php echo $this->fieldWP->name; ?></strong> -
                                Level:
                                <strong><?php echo $this->levelWP->name; ?></strong></h5>
                            <h2 class="badge-got-title">
                                Badge already got!
                            </h2>

                        </div>
                    </main>

                    <footer class="mastfoot">
                        <div class="inner">
                            <div id="gb-resp-login"></div>
                        </div>
                    </footer>

                </div>

            </div>
        </div>
        <?php
        $this->obf_footer();
    }

    /**
     * Show the error that we discovered in the loadParm() function.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param const $error contain the kind of error
     */
    private function showMessage($error) {
        $this->obf_header()
        ?>
        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div id="gb-wrap" class="cover-container">

                    <header class="masthead clearfix">
                        <?php echo $this->getInfoHeader(); ?>
                        <div class="inner container">
                        </div>
                    </header>

                    <main role="main" class="inner cover">
                        <div class="container">
                            <?php
                            if ($error == self::PREVIEW) { ?>
                                <h1>GET BADGE PAGE PREVIEW</h1>
                                <p class="lead">
                                    This page is set as a default page that permit to get the badge.
                                </p>
                                <?php
                            } elseif ($error == self::ERROR_JSON) { ?>
                                <h1>BADGE ERROR</h1>
                                <p class="lead">
                                    Your badge is not anymore stored in our server.
                                </p>
                                <?php
                            } else if ($error == self::ERROR_LINK) {
                                ?>
                                <h1>URL ERROR</h1>
                                <p class="lead">
                                    There's something wrong with the link, ask to the help desk to fix the
                                    problem!
                                </p>
                                <?php
                            }
                            ?>
                        </div>
                    </main>

                    <footer class="mastfoot">
                        <div class="inner">
                            <div id="gb-resp-login"></div>
                        </div>
                    </footer>

                </div>

            </div>
        </div>
        <?php
        $this->obf_footer();
    }

    /**
     * Contain the header of the page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    private function obf_header() {
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Get the Badge</title>
            <script src="https://backpack.openbadges.org/issuer.js"></script>

            <?php wp_head(); ?>
        </head>
        <body>
        <div class="container-wrap">
        <div  class="site-wrapper">
        <div class="site-wrapper-inner center-text">
        <?php
    }

    /**
     * Contain the footer of the page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    private function obf_footer() { ?>
        </div>
        </div>
        </div>
        <?php wp_footer(); ?>
        </body>
        </html>

        <?php
    }

    /**
     * Contain the info of the website that are show in the top of the page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    function getInfoHeader() {
        ?>
        <div class="info-header-obf">
            <div class="container">
                <a href="<?php echo get_bloginfo('url'); ?>"><?php
                    $options = get_option(SettingsTemp::OPTION_NAME);
                    echo $options[SettingsTemp::FI_SITE_NAME_FIELD];
                    ?>
                </a>
            </div>
        </div>
        <?php
    }
}