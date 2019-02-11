function subscribe(subscribingTo, subscribingFrom, button) {
    if(subscribingTo == subscribingFrom) {
        alert("You Cannot Subscribe To Yourself");
        return;
    }

    $.post("ajax/subscribe.php", {subscribingFrom: subscribingFrom, subscribingTo: subscribingTo})
    .done(function(subscriberCount) {
        if(subscriberCount != null) {
            $(button).toggleClass("subscribe unsubscribe");
            var buttonText = $(button).hasClass("subscribe") ? "SUBSCRIBE" : "SUBSCRIBED ";
            if(subscriberCount > 0) {
                $(button).text(buttonText + subscriberCount);
            } else {
                $(button).text(buttonText);
            }

        } else {
            alert("Cannot Subscribe");
        }
    });
}