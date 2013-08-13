jQuery(function(){
	if(jQuery('.datepicker').length > 0 )
	{
		jQuery('.datepicker').parent().datetimepicker({
			maskInput: true,           // disables the text input mask
			pickTime:false,
			format: 'yyyy-MM-dd'
		});
	}

	if(jQuery('.datetimepicker').length > 0 )
	{
		jQuery('.datetimepicker').parent().datetimepicker({
			maskInput: true,           // disables the text input mask
	    	format: 'yyyy-MM-dd hh:mm:ss',
	    	pickTime:true,
	    	pick12HourFormat: true
	    	// pickSeconds: false
		});
	}

	if(jQuery('.pickadate').length > 0 )
	{
		jQuery('.pickadate').pickadate({
			format: 'yyyy-mm-dd',
			selectYears: true,
			selectMonths: true
		});
	}

	$('.action-tooltip').tooltip({
		'trigger': 'hover'
	});

	$(".form-toolbar.form-inline select").on("change", function(){
		$(this).closest('form').find("#submit").trigger("click");
	});
});
