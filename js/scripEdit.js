window.addEvent('domready', function() {
	$('btnEdit').addEvent('click', function(){
		var t1 = document.getElementById('edit').getAttribute('data-ident');
		var t2 = document.getElementById('edit').getAttribute('data-comment')
		var t3 = document.getElementById('comment').value;
		
		var request = new Request.JSON({
			method : 'post',
			url : 'image/editPost/',
			data : {
				id : t1
			},
			
			onRequest: function(){
				alert('Modification du commentaire de l\'image d\'ID:' + t1 + '\n de ' + t2 + ' en ' + t3);
			},
			
			onSuccess: function(){
				alert('Modification réussie :D');
			},
			
			onFailure: function(){
				alert('Échec de la modification :(');
			}
		}).send();
	});
});

