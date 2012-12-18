/*alert('coucou');
 */
var color=new Array('white','green','red');

var test = function(e){
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

window.addEvent('domready', function(){

	$$('div').each(function(el){
		
		
		el.addEvents({
		 	click: test
		 	});
	});
	
}); 
