requirejs(
    ['../../../libs/jquery-2.0.2.min'],
    function () {

        $(document).ready(function(){
            $('#language_choice').change(function(){
                window.location = '/' + $('#language_choice option:selected').attr('value')
            });
        });

    }
);
