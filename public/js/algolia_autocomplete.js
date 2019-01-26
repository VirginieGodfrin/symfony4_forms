$(document).ready(function(){
	// select the class js-user-autocomplete, add it autocomplete() jquery plugin
	$('.js-user-autocomplete').autocomplete( { hint: true }, [
		{
			// add a JavaScript object with a source option set to a function() that receives a query argument and a callback cb argument.
			source:function(query, cb){
				cb([
					{value:'foo'},
					{value:'bar'}
				])
			}
		}
	]);
});