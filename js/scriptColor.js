/*alert('coucou');
 */
 
 $$('test').each(function(el){
 	el.addEvents({
	 	mouseover: function(){
	        alert('mouseover');
	    },
	    click: function(){
	        alert('click');
	    }
 	});
});