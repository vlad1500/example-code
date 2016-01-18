<link href="<?=$this->config->item('css_url');?>/filter.css" rel="stylesheet" />
<link href="<?=$this->config->item('js_url');?>/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
<link rel="stylesheet" href="/mobile_files/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="/mobile_files/css/jquery.fileupload.css">
<link rel="stylesheet" href="/mobile_files/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="/mobile_files/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="/mobile_files/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<style>
.plupload_container{
    min-height:100px;
}
#uploader_dropbox {
  display: none;
}
#uploader_buttons div {
  margin:0;
  padding:5px;
}
.plupload_filelist_footer {
  height: 100%;
}
#addFromPhone {
  max-width: 700px;
}
#form_filter_data {
  display:none;
}

</style>

<!--Content for the Filter Page-->
<form id="fileupload" action="/" method="POST" enctype="multipart/form-data">
    <div id="filter_content">
        <div>
            <div class='panel-container'>
                <div id="tabs1-computer">
                        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                        <div class="row fileupload-buttonbar">
                            <div class="col-lg-7">
                                <!-- The fileinput-button span is used to style the file input field as button -->
                                <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus">
                </i>
                <span>
                  Take Picture...
                </span>
                                <input type="file" accept="image/*;capture=camera" name="files[]" />
                                <input type="hidden" name="book_id" id="book_input_id" value="" />
                                </span>
                                <button type="submit" class="btn btn-primary start">
                                    <i class="glyphicon glyphicon-upload">
                </i>
                                    <span>
                  Start upload
                </span>
                                </button>
                                <button type="reset" class="btn btn-warning cancel">
                                    <i class="glyphicon glyphicon-ban-circle">
                </i>
                                    <span>
                  Cancel upload
                </span>
                                </button>
                                <button type="button" class="btn btn-danger delete">
                                    <i class="glyphicon glyphicon-trash">
                </i>
                                    <span>
                  Delete
                </span>
                                </button>
                                <input type="checkbox" class="toggle">
                                <!-- The global file processing state -->
                                <span class="fileupload-process">
              </span>
                            </div>
                            <!-- The global progress state -->
                            <div class="col-lg-5 fileupload-progress fade">
                                <!-- The global progress bar -->
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-success" style="width:0%;">
                                    </div>
                                </div>
                                <!-- The extended global progress state -->
                                <div class="progress-extended">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <!-- The table listing the files available for upload/download -->
                        <table role="presentation" class="table table-striped">
                            <tbody class="files">
                            </tbody>
                        </table>

                </div>
            </div>
            <div id="button_edit_right" class="text-right clearfix popup">
                <input id="filter_next_unique" type="button" class="btn btn-orange margin-right-sm filter_next_pro ui-btn-inline" data-mini="true" value="Next" name="submit" />
                <input id="filter_close" onclick="hideoverlay()" type="button" class="btn btn-orange ui-btn-inline" data-mini="true" value="Close" name="submit" />
                <input type="hidden" id="book_info_id" name="book_info_id" />
            </div>
        </div>
        <!-- End of #tabs-->
    </div>
</form>
 

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/skins/tango/skin.css" />
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script type="text/javascript">
head.js(
    '//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js',
    //'/mobile_files/js/vendor/jquery.ui.widget.js',
    '/mobile_files/js/tmpl.min.js',
    '/mobile_files/js/load-image.min.js',
    '/mobile_files/js/canvas-to-blob.min.js',
    '/mobile_files/js/jquery.blueimp-gallery.min.js',
    '/mobile_files/js/jquery.iframe-transport.js',
    '/mobile_files/js/jquery.fileupload.js',
    '/mobile_files/js/jquery.fileupload-process.js',
    '/mobile_files/js/jquery.fileupload-image.js',
    '/mobile_files/js/jquery.fileupload-audio.js',
    '/mobile_files/js/jquery.fileupload-video.js',
    '/mobile_files/js/jquery.fileupload-validate.js',
    '/mobile_files/js/jquery.fileupload-ui.js',
    '/mobile_files/js/main.js'
);

head.ready(function () {
    var wW = $(window).width();
    var wH = $(window).height();
    var book_id = $("body").attr("book_info_id");
    $("#addFromPhone-popup").css({
        "top": "0",
        "left": "0",
        //"width": wW + "px",
        //"height": wH + "px"
    });
    $("#book_input_id").val(book_id);
    $("#fileupload").show();

    $('#filter_next_unique').unbind("click").on('click', function () {
        console.log("next clicked");
        var book_id = $("body").attr("book_info_id");
        $("#fileupload #book_info_id").val(book_id);
        console.log("click book id: " + $("#fileupload #book_info_id").val());
        $(this).append('<div class="ajax_loader"></div>');
        var form_data = $('#fileupload').serialize();
        var ulink = '/filter/save_book_filter_cover_unique';

        $.ajax({
            cache: false,
            url: ulink,
            type: 'post',
            data: form_data,
            success: function (res) {
                $('.ajax_loader').remove();
                var _obj = $.parseJSON(res);

                if (_obj.status != 0) {
                    alert(_obj.msg);
                } else {
                    console.log('in else');
                    /*$('#main_inner_uploder_pop').html('');
								$('#main_inner_uploder_pop').css('display','none');
                                $('#main_inner').html(_obj.data);
                                $('#fb_data').fadeIn(); //Fade in the active ID content
                                $.ajax({
                                    cache   : false,
                                    url     : '../../../filter/createBookCover',
                                    type    : 'post',
                                    data    : { 'book_info_id' : $.cookie('hardcover_book_info_id') },
                                    success : function(res){}
                                });*/
                    //   var pathname = window.location.pathname;
                    //window.location.href = pathname;
                    $('#main_inner_uploder_pop').html('').remove();
                    $('#main_inner_overlay').css('display', 'none');
                    var link = '/main/get_last_insert_images';
                    $.ajax({
                        cache: false,
                        url: link,
                        type: 'post',
                        success: function (res) {
                            var _obj = $.parseJSON(res);

                            $('#last_inset_div ul#cvv_data').html(_obj.data);
                            $('#last_inset_div_a').click();
                            jQuery('#app_loader23').fadeOut(100);
                            window.location = window.location.pathname;
                        }
                    });
                };
            },
            error: function () {}
        });
        return false;
    });
    // Client side form validation
    $('form').submit(function (e) {
        var uploader = $('#uploader').plupload('getUploader');

        // Files in queue upload them first
        if (uploader.files.length > 0) {
            // When all files are uploaded submit form
            uploader.bind('StateChanged', function () {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $('form')[0].submit();
                }
            });
            $('#filter_next_unique').css('background', '#b3b3b3');
            uploader.start();
            $('#filter_next_unique').css('background', '#FF9839');
        } else
            alert('You must at least upload one file.');

        return false;
    });
});
</script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<script type="text/javascript">
function hideoverlay(){
	jQuery("#addFromPhone-popup").css('display','none');
	jQuery("#fileupload").fadeOut(100);
}

function get_album_photos(alb_id) {
    if ($("#id_" + alb_id).is(":checked")) {

        $.ajax({
            url: "../../../main/get_album_photos",
            type: "post",
            data: 'alb_id=' + alb_id,
            success: function (res) {
                if (res != '') {
                    $('#album_photo_raw_data').append(res);
                } else {}
            }
        });
    } else {
        $(".cla_" + alb_id).remove();
    }
}
</script>