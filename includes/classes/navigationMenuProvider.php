<?php

class NavigationMenuProvider {
    private $con;
    private $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {
        $menuHtml = $this->createNavItem("Home", "assets/images/icons/home.png", "index.php" );
        $menuHtml .= $this->createNavItem("Trending", "assets/images/icons/trending.png", "trending.php" );
        $menuHtml .= $this->createNavItem("Subscriptions", "assets/images/icons/subscriptions.png", "subscriptions.php" );
        $menuHtml .= $this->createNavItem("Liked Videos", "assets/images/icons/thumb-up.png", "likedVideo.php" );

        if (User::isLoggedIn()) {
            $menuHtml .= $this->createNavItem("Settings", "assets/images/icons/settings.png", "settings.php" );
            $menuHtml .= $this->createNavItem("Log Out", "assets/images/icons/logout.png", "logout.php" );
            $menuHtml .= $this->createSubscriptionsSection();
        }
        
        return "<div class='navigationItems'>
                    $menuHtml
                </div>";
    }

    private function createNavItem($text, $icon, $link) {
        return "<div class='navigationItem'>
                <a href='$link'>
                    <img src='$icon'>
                    <span>$text</span>
                </a>
                </div>";
    }

    private function createSubscriptionsSection() {
        $subscriptions = $this->userLoggedInObj->getSubscriptions();

        $html = "<span class='heading'>Subscriptions</span>";

        foreach ($subscriptions as $sub) {
            $username = $sub->getUsername();
            $html .= $this->createNavItem($username, $sub->getProfilePhotoURL(), "profile.php?username=$username");
        }
        return $html;

    }
}

?>