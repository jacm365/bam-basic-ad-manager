(function( $ ) {
	'use strict';
	$(function() {
		/*
		* Main section
		*/

		/*
		* Form
		*/
		$(".bam-ad-form #save").click(function(event) {
			clearAllErrors();
			var error = false;
			var title = $(".bam-ad-form #title");
			var bgcolor = $(".bam-ad-form #bgcolor");
			var type = $(".bam-ad-form #type");
			var remaining_time = $(".bam-ad-form #remaining_time");

			if($.trim(title.val()) === '') {
				addErrorMessage(title);
				error = true;
			}
			if($.trim(bgcolor.val()) === '') {
				addErrorMessage(bgcolor);
				error = true;
			}
			if(type.val() === 'pick' && !validTime($.trim(remaining_time.val()))) {
				addErrorMessage(remaining_time);
				error = true;
			}
			return !error;
		});
		
		$(".bam-ad-form #type").change(function() {
			var type = $(this).val();
			var remaining_time = $(".bam-ad-form #remaining-time");
			(type === 'pick') ? remaining_time.css('display', 'flex') : remaining_time.hide();
		});

		function addErrorMessage(field) {
			field.after('<small class="error-message">This field is required.</small>');
		}

		function validTime(time) {
			return (time.search(/^\d{2}:\d{2}:\d{2}$/) != -1);
		}

		function clearAllErrors() {
			$(".bam-ad-form .error-message").each(function() {
				$(this).remove();
			});
		}
	});

})( jQuery );
