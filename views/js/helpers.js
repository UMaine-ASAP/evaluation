$(document).ready(function() {

	/* =================================== */
	/* Convert DB Timestamps
	/* 
	/* Converts timestamps from DB marked with .humanize-timestamp to human-friendly format
	/* =================================== */

	// convert created and modified dates
	function humanizeServerTime(context)
	{
		if ($(context).html() == '') return;

		var m = moment($(context).html(), "YYYY-MM-DD HH:mm:ss");
		if( m.isValid() )
		{
			$(context).html( m.from(moment()) );
		}
	}
	
	$('.humanize-timestamp').each( function(item) {
		if( ($(this).html() == '0000-00-00 00:00:00') )
		{
			$(this).html('');
		} else {
			humanizeServerTime(this);
		}
	});
});