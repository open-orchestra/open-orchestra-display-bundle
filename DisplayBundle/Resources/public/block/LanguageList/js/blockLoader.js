require(
    ['jquery'],
    function (jquery) {

        $(document).ready(function(){
            $('#language_choice').change(function(){
                window.location = '/' + $('#language_choice option:selected').attr('value')
            });
        });

    }
);
