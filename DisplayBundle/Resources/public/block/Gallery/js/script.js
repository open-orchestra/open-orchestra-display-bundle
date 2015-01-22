function resizeThumbnails(galId, nbCol) {
    if (galId == '') alert('Error : no id defined for gallery');
    var picturePadding = parseInt($(".gallery-picture").css("border-left-width")) + parseInt($(".gallery-picture").css("margin-left"));
    alert(picturePadding);
    var galleryWidth = parseInt($("#" + galId).width());
    var pictureWidth = parseInt((galleryWidth / nbCol) - 2*picturePadding);

    $("#" + galId + " .gallery-picture").width(pictureWidth + 'px');
    $("#" + galId + " .gallery-picture").css("visibility", "visible");
}

$(document).ready(function() {

    alert('/!\\ Pause temporaire pour laisser le temps aux js de charger => A remplacer par une solution de configuration générale des js /!\\')

    for(var galleryId in orchestraGalCol) {
        resizeThumbnails(galleryId, orchestraGalCol[galleryId]);
    }

    $(".fancybox-thumb").fancybox({
        prevEffect  : 'none',
        nextEffect  : 'none',
        closeBtn    : false,
        helpers     : {
            title     : {
                type    : 'inside'
            },
            thumbs    : {
                width   : 50,
                height  : 50
            }
        }
    });
});
