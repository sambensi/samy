window.addEvent('domready', function() {

/********Affichage du tool tips qui fonction avec mootool et sa class Tips qui va afficher le title et le rel, nous avons choisi de présenter le nom de l'image et le commentaire*/
    var SimpleTip = new Tips($$('.simple_tip'), {
        showDelay: 0,
        hideDelay: 0,
        offsets: {x: 4, y: 4},
        fixed: false
    });
/**************************************************/
/**********fonction pour l'annotation, nous récupérons le background mis en place et nous vérifions sa couleur simplement
 *s'il est blanc il deviendra vert, s'il est vert il deviendra rouge et s'il est rouge il redeviendra blanc.
 * L'implémentation de cette fonction n'est pas complete, nous voulions la réaliser comme elle est demandée dans le tp mais manque de temps 
 * et de connaissance de la technologie nous n'avons pas réussi.
 */

	var changeCol = function(e){
		var idEnCours = this.get('id');
		var style = this.getStyle('background-color');
		
		if( style == '#ffffff' ){
			this.setStyles({
		    	border: '1px solid green',
	    		backgroundColor: 'green',
			})
		}
		if( style == 'green' ){
			this.setStyles({
		    	border: '1px solid red',
	    		backgroundColor: 'red',
			})
		}
		if( style == 'red' ){
			this.setStyles({
		    	border: '1px solid #ffffff',
	    		backgroundColor: '#ffffff',
			})
		}
	
	};
	/************Pour chque div (qui prend donc une image) nous ajoutons l'evenement click qui va lancer la fonction de changement de couleur*************/
	$$('div').each(function(el){
		
		
		el.addEvents({
		 	click: changeCol
		 	});
	});
/**************************************************/
/**********fonction pour l'edition d'une image sans rechargement de la page**************/

	

});

