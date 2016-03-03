require(
    ['jquery'],
	function (OpenOrchestraCss) {
		$(document).ready(function(){
			$('form[name="Contact"').submit(function(event) {
	            event.preventDefault();
	            var data = $(this).serialize();   
	            $.ajax({
	                url: $(this).attr('action'),
	                context: $(this),
	                method: 'POST',
                    data: data,
                    success: function(response){
                    	$(this).html($('form', $(response)).html());
                    }
	            });
            });
        });
    }
);
