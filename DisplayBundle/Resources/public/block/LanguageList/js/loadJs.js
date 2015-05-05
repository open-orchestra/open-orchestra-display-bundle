requirejs(
    ['/bundles/openorchestradisplay/libs/jquery-2.0.2.min.js'],
    function () {

        $(document).ready(function(){
            $('#language_choice').change(function(){
                window.location = '/' + $('#language_choice option:selected').attr('value')
            });
        });

    }
);
