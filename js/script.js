window.addEvent('domready', function(){
				
	$('ajax-req').addEvent('click', function(event){
		event.stop();
		
		var req = new Request({
			method: 'post',
			url: 'image/comment/',
			onRequest: function() {alert('Requête envoyé, merci de patienter...');},
			onSuccess: function() {alert('Le commentaire a bien été changé!!!');}
			onFailure: function() {alert('Le commentaire n\'a pas été changé!!!');}
		});
		
		req.send('id=')
	});

});