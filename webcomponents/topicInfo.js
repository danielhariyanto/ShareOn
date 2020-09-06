class TopicInfo extends HTMLElement {
    constructor(){
        super();
        this.key = "";
        var t = this;

        $(this).on("change", "#imageUpload input", function(){
          var file=$(this).prop("files")[0];
        	var url= URL.createObjectURL(file);
          $("#imageUpload img").attr("src",url);
        });

        $(this).on('click', '#editSubmit', function() {
            if ($('#editSubmit').hasClass("click")){
              var file=$('#imageUpload input').prop("files")[0];
            var fd = new FormData();
            fd.append("file", file);
            fd.append("key",t.key);
            fd.append('description',$("#descripBox").val());
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/writers/topic_update.php', true);
            xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
            var percentComplete = (e.loaded / e.total) * 100;
            console.log(percentComplete + '% uploaded');
            }
            };
            xhr.onload = function() {
            if (this.status == 200) {
              var d=JSON.parse(this.response);

              $('#editSubmit').text('Edit');
              $('#editSubmit').removeClass("click");
              $('#imageUpload input').remove();
              $('#imageUpload div').addClass("invisible");
              $("#descripBox").val(d.description);
              $('#descripBox').prop("disabled", true);
              $("#imageUpload img").attr("src",d.image);
              $('#descripBox').css("border", "none");
            }
            };
            xhr.send(fd);
            }
            else {
                $('#editSubmit').text('Submit');
                $('#editSubmit').addClass("click");
                $('#imageUpload div').removeClass("invisible");
                $('#imageUpload').append('<input type="file"/>');
                $('#descripBox').prop("disabled", false);
                $('#descripBox').css("border", "rgb(80, 80, 80) solid 0.5px");
            }
        });

        $(this).on("keyup", "#descripBox", function(){
            var el = this;
            var th = $(this);
            setTimeout(function(){
                if (th.val().length < 750) {
                el.style.cssText = 'height:auto; padding:0';
                el.style.cssText = 'height:' + el.scrollHeight + 'px';
                }
            },0);
        });


    }
    connectedCallback() {
        var key = $(this).attr("key");
        this.key = key;
        var t = this;
        $.post("/geters/topicLoad.php", { key: key }, function(d){
            var data = JSON.parse(d);
            t.innerHTML = `<div id="topBar">
                #${data["name"]}
            </div>
            <div id="imageUpload">
            <img src='${(data["image"] == undefined)?"":data["image"]}'/>
            <div class="invisible">+</div>
            </div>
            <textarea id="descripBox" disabled="true" maxlength="1000" name="characters">${(data["description"] == undefined)?"no description":data.description}</textarea>
            <!-- <more-options id="parentTopic">
                #Parent Topic
            </more-options>
            <more-options id="childrenTopic">
                #Child Topic
            </more-options>
            <more-options id="ageRestriction">
                Age Restriction
            </more-options> -->
            <div id="editSubmit" class="btn">Edit</div>
            <div id="report" class="btn">Report</div>`;
        });
        }
}
window.customElements.define("topic-info", TopicInfo);

// class MoreOptions extends HTMLElement {
//     constructor(){
//         super();
//         //var t = this;
//         $(this).on("click", function(){
//             // alert($(this).text());
//       });
//     }
//     connectedCallback() {

//         }
// }
// window.customElements.define("more-options", MoreOptions);
