/**
 * placements.js
 * @author Dirk Wenzel 
 */


$('.jqAjax').click(function(e)  {
	var uid = $(this).find('.uid').html();
	var storagePid = '11';

	$.ajax({
		async: 'true',
		url: 'index.php',      
		type: 'POST',
		data: {
			eID: "placementsAjax",  
			request: {
				pluginName:  'Placements',
				controller:  'Position',
				action:      'ajaxList',
				arguments: {
					'uid': uid,
					'storagePid': storagePid
				}
			}
		},
		dataType: "json",      
		success: function(result) {
			console.log(result);
		},
		error: function(error) {
			console.log(error);               
		}
	});
});
