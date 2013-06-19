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
			format: 'yyyy-mm-dd'
		});
	}

	if(jQuery('#clock').length > 0)
	{
		updateClock();
		setInterval('updateClock()', 1000);
	}

	$('.action-tooltip').tooltip({
		'trigger': 'hover'
	});
});

function updateClock()
{
  var currentTime = new Date();

  var currentHours = currentTime.getHours();
  var currentMinutes = currentTime.getMinutes();
  var currentSeconds = currentTime.getSeconds();

  // Pad the minutes and seconds with leading zeros, if required
  currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
  currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

  // Choose either "AM" or "PM" as appropriate
  var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

  // Convert the hours component to 12-hour format if needed
  currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

  // Convert an hours component of "0" to "12"
  currentHours = ( currentHours == 0 ) ? 12 : currentHours;

  // Compose the string for display
  var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;

  // Update the time display
  document.getElementById("clock").firstChild.nodeValue = currentTimeString;
}