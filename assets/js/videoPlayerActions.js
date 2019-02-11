function likeVideo(button, videoId) {
    console.log("Like Video");
    $.post("ajax/likeVideo.php", {videoId: videoId})
    .done(function(data) {
        var likeButton      = $(button);
        var disLikeButton   = $(button).siblings(".dislikeButton");

        likeButton.addClass("active");
        disLikeButton.removeClass("active");

        var result = JSON.parse(data);
        console.log(result);
        updateLikesValue(likeButton.find(".text"), result.likes);
        updateLikesValue(disLikeButton.find(".text"), result.dislikes);

        if (result.likes < 0) {
            likeButton.removeClass("active");
            likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png")
        } else {
            likeButton.find("img:first").attr("src","assets/images/icons/thumb-up-active.png")
        }

        disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png")
    });
}

function dislikeVideo(button, videoId) {
    $.post("ajax/dislikeVideo.php", {videoId: videoId})
    .done(function(data) {
        var disLikeButton   = $(button);
        var likeButton      = $(button).siblings(".likeButton");

        disLikeButton.addClass("active");
        likeButton.removeClass("active");

        var result = JSON.parse(data);
        console.log(result);
        updateLikesValue(likeButton.find(".text"), result.likes);
        updateLikesValue(disLikeButton.find(".text"), result.dislikes);

        if (result.dislikes < 0) {
            disLikeButton.removeClass("active");
            disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png")
        } else {
            disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down-active.png")
        }

        likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png")
    });
}

function updateLikesValue(element,num) {
    var likesCountValue = element.text() || 0;
    element.text(parseInt(likesCountValue) + parseInt(num));
}
