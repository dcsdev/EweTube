<?php
    class ButtonProvider {

        public static $signInFunction = "notSignedIn()";

        public static function createLink($link) {
            return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
        }
        public static function create($cssClass, $clickFunction, $imgsrc, $text) {
            $image = ($imgsrc == null ) ? "" : "<img src='$imgsrc' >";

            $action = ButtonProvider::createLink($clickFunction);

            return "<button class='$cssClass' onclick='$action' >
                    $image
                    <span class='text'>$text</span>
                    </button>";
        }

        public static function createProfileButton($con, $username) {
            $userObj = new User($con,$username);
            $profilePic = $userObj->getProfilePhotoURL();
            $link = "profile.php?username=" . $userObj->getUsername();

            return "<a href='$link'>

                    <img src='$profilePic' class='profilePicture'>
            
                    </a>";

        }

        public static function createHyperLinkButton($cssClass, $href, $imgsrc, $text) {
            $image = ($imgsrc == null ) ? "" : "<img src='$imgsrc' >";

            return " <a href='$href'>
                        <button class='$cssClass' >
                        $image
                        <span class='text'>$text</span>
                        </button>
                    </a>";
        }

        public static function createEditVideButton($videoID) {
            $href = "editVideo.php?videoId=$videoID";
            $button = ButtonProvider::createHyperLinkButton("edit button",$href,NULL,"EDIT VIDEO");

            return "<div class='editVideoButtonContainer'>
                        $button
                    </div>";
        }

        public static function createSubscriberButton($con,$userToObj,$userLoggedInObj) {

            $userTo = $userToObj->getUsername();
            $userLoggedIn = $userLoggedInObj->getUsername();

            $isSubscribedTo  = $userLoggedInObj->isSubscribedTo($userTo);
            $buttonText      = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";
            $buttonText      .= " " . $userToObj->getSubscriberCount() > 0 ? $userToObj->getSubscriberCount() : "" ;
            $buttonClass     = $isSubscribedTo ? "unsubscribe button" : "subscribe button";
            $action          = "subscribe(\"$userTo\", \"$userLoggedIn\", this)";

            $button = ButtonProvider::create($buttonClass, $action, null, $buttonText);

            return "<div class='subscribeButtonContainer'>
                    $button
                    </div>";
        }

        public static function createUserProfileNavigationButton($con, $username) {
            if (User::isLoggedIn()) {
                return ButtonProvider::createProfileButton($con, $username);
            } else {
                return "<a href='signin.php'>
                            <span class='signInLink'>SIGN IN</span>
                        </a>";
            }
        }
    }
?>