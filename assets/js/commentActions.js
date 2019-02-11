function postComment(button, postedByUsername, videoId, replyTo, containerClass) {
    var textarea = $(button).siblings("textarea");
    var commentText = textarea.val();
    textarea.val("");

    if(commentText) {
        $.post("ajax/postComment.php", {commentText: commentText,
                                         postedBy: postedByUsername, 
                                         videoId: videoId, 
                                         replyTo: replyTo})
        .done(function(comment) {

            if(!replyTo) {
                $("." + containerClass).prepend(comment);
            } else {
                $(button).parent().siblings("." + containerClass).append(comment);
            }

            
        });
    } else {
        alert("You must add a comment");
    }

}

function toggleReply(button) {
    var parent      = $(button).closest(".itemContainer");
    var commentForm = parent.find(".commentForm").first();

    commentForm.toggleClass("hidden");
}

function commentInteraction(commentId, button, videoId, isLike) {
    if( isLike ) {
        $.post("ajax/commentInteraction.php", {commentId: commentId, videoId: videoId, isLike: isLike})
        .done(function(numToChange) {
            var likeButton = $(button);
            var disLikeButton = $(button).siblings(".dislikeButton");
    
            likeButton.addClass("active");
            disLikeButton.removeClass("active");
    
            var likesCount = $(button).siblings(".likesCount");

            updateLikesValue(likeButton.find(".text"), numToChange);

            if (numToChange < 0) {
                likeButton.removeClass("active");
                likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png")
            } else {
                likeButton.find("img:first").attr("src","assets/images/icons/thumb-up-active.png")
            }
    
            disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png")
        });
    } else {
        $.post("ajax/commentInteraction.php", {commentId: commentId, videoId: videoId, isLike: isLike})
        .done(function(numToChange) {
            var dislikeButton = $(button);
            var likeButton = $(button).siblings(".likeButton");
    
            dislikeButton.addClass("active");
            likeButton.removeClass("active");
    
            var likesCount = $(button).siblings(".likesCount");

            updateLikesValue(likeButton.find(".text"), numToChange);

            if (numToChange < 0) {
                dislikeButton.removeClass("active");
                dislikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png")
            } else {
                dislikeButton.find("img:first").attr("src","assets/images/icons/thumb-down-active.png")
            }
    
            likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png")
        });
    }
}

function updateLikesValue(element,num) {
    var likesCountValue = element.text() || 0;
    element.text(parseInt(likesCountValue) + parseInt(num));
}

function getReplies(commentId, button, videoId) {
    $.post("ajax/getCommentReplies.php", {commentId: commentId, videoId: videoId})
        .done(function(replies) {
            var replies = $("<div>").addClass("repliesection");
            replies.append(replies);

            $(button).replaceWith(replies);
        });
}