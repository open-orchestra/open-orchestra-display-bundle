function resizeThumbnails(nbCol) {
    var picturePadding = parseInt($(".gallery-picture").css("border-left-width"));
    var galleryWidth = parseInt($("#gallery").width());
    var pictureWidth = parseInt((galleryWidth / nbCol) - 2*picturePadding);

    $(".gallery-picture").width(pictureWidth + 'px');
    $(".gallery-picture").css("visibility", "visible");
}

$(document).ready(function() {
    alert('/!\\ Pause temporaire pour laisser le temps aux js de charger => A remplacer par une solution de configuration générale des js /!\\')

    resizeThumbnails(galleryNbCols);

    $(".fancybox-thumb").fancybox({
        prevEffect  : 'none',
        nextEffect  : 'none',
        closeBtn    : false,
        helpers : {
            title   : {
                type: 'inside'
            },
            thumbs  : {
                width   : 50,
                height  : 50
            }
        }
    });
});
