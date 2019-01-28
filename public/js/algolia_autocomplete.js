$(document).ready(function(){
	// the loop 
	$('.js-user-autocomplete').each(function() {
		// the url
		var autocompleteUrl = $(this).data('autocomplete-url');
		// select the class js-user-autocomplete, add it autocomplete() jquery plugin
		$(this).autocomplete( { hint: true }, [
			{
				// add a JavaScript object with a source option set to a function() that receives a query argument and a callback cb argument.
				source:function(query, cb){
					// the ajax call and his callback(then)
					$.ajax({
						// filetring the users
						// The source function is passed a query argument: that's equal to whatever 
						// is typed into the input box at that moment. Let's use that! Add a '?query='+query
						url: autocompleteUrl+'?query='+query
					}).then(function(data){
						// because in getUsersApi controller we return a users Key
						cb(data.users);
					});
				},
				// the algolia attribute power (read the doc)
				displayKey: 'email',
				debounce: 500 // only request every 1/2 second
			}
		])

    });
});