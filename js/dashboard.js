$("#post-typeCreator").click(function () {
    $("body").append(`
        <div class= 'editor_shadow'>
            <div id='add_popup'>
                <div id='row-wrapper' style='text-align: center; white-space:nowrap;'>
                    <span class="input_wrapper">
                        <label for='post_name'>
                            <p>Name</p>
                        </label>
                        <input type='text' id='post_name' placeholder='Title of your post-type' name='post_name' required>
                    </span>
                    <span class="input_wrapper">
                        <label for='post-type_icon'>
                            <p>Upload icon</p>
                        </label>
                        <input type='file' id='post-type_icon' name='post-type_icon' accept='image/*'>
                    </span>
                </div>
                <label for='post_desc'>
                    <p>Description</p>
                </label>
                <textarea id='post_desc' placeholder='Describe your post-type' name='post_desc' style="width: 495px; height: 195px; margin: 0 auto"></textarea>
                <input type='submit' id= 'submitPostType' value="Submit" style="display:block; margin: 0px auto;">
            </div>
        </div>`);
});
$(".cancelPost").click(function () {
    $("body").append(`
        <div class= 'deletion_overlay'>
            <div id='add_popup'>
                <div style="text-align: center;">Are you sure you want to delete this post-type?</div>
                <br>
                <div style="text-align: center;">
                <input type='button' value='yes' id='`+ $(this).attr('id') + `' class='yes'>
                <a href="http://localhost/tool_bubble/bubbles_dashboard.php">
                    <input type='button' value='no'>
                </a>
                </div>
            </div>
        </div>`);
});

$(document).on("click", "#submitPostType", function () {
    var jsonInfo = {};
    jsonInfo.name = $("#post_name").val();
    jsonInfo.desc = $("#post_desc").val();
    if (jsonInfo.name !== "" && jsonInfo.desc !== "") {
        var file = $("#post-type_icon").prop("files")[0];
        var fd = new FormData();
        fd.append("file", file);
        fd.append("name", jsonInfo.name);
        fd.append("desc", jsonInfo.desc);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'newPostCreator.php', true);
        xhr.onload = function () {
            window.location.href = "http://localhost/tool_bubble/bubble_developer.php?tool_bubble=" + this.response;

        };
        xhr.send(fd);
        $(".editor_shadow").remove();
    }
    else {
        alert("Some of the fields were left empty.")
    }
});

$(document).on("click", ".yes", function () {
    var jsonInfo = {};
    jsonInfo.postID = $(this).attr('id');
    $.post("postDeletor.php", jsonInfo, function (d) {
        $(".deletion_overlay").remove();
        $(".imageBox[key=" + jsonInfo.postID + "]").remove();
    });

});
