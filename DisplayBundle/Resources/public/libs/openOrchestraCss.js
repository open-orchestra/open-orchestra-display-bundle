define(
    'openOrchestraCss',
    function() {
        return {
            load:function(cssFilesArray){
                for (cssFile of cssFilesArray) {
                    var link = document.createElement("link");
                    link.type = "text/css";
                    link.rel = "stylesheet";
                    link.href = cssFile;
                    document.getElementsByTagName("head")[0].appendChild(link);
                }
            }
        };
    }
);
