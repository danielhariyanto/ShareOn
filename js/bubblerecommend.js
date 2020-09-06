$(document).on('click',"#recommendbubble", function() {
	$.get("/geters/recommendations.php",function(data){
		$( "body" ).append(data);
		$.magnificPopup.open({
			items: {
		    src: $(data),
		    type: 'inline'
			},
			closeBtnInside: false,
			callbacks: {

				afterClose: function() {
					$( "#recommendbox" ).remove();
				}
			}
		});
	});
});
