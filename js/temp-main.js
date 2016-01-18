$(function () {

    /* ====== COMMON MODAL ======*/
    $("#js-newlightbox").on('click', function () {
        $('#myModal').modal();
    });

    $(".jsPublishBookBtn").on('click', function () {
    	$('#js-show-friends').hide();
        $('#jsPublishBook').modal();
    });
    
});
