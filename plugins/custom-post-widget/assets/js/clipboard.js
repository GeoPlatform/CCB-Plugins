var clipboard = new Clipboard('.cpw-clipboard');

clipboard.on('success', function(e) {
	console.log(e);
});

clipboard.on('error', function(e) {
	console.log(e);
});
