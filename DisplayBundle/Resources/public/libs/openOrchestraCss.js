define(
    'openOrchestraCss',
    function() {
        return {
            load:function(cssFilesArray){
                cssFilesArray.forEach(function (cssFile) {
                    var link = document.createElement("link");
                    link.type = "text/css";
                    link.rel = "stylesheet";
                    link.href = cssFile;
                    document.getElementsByTagName("head")[0].appendChild(link);
                });
            }
        };
    }
);
