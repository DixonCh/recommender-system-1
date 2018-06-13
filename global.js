function sr(a, b){
	fetch('rate.php', {
	  method: 'post',
	  headers: {
	    'Accept': 'application/json',
	    'Content-Type': 'application/json'
	  },
	  body: JSON.stringify({fid: a.substr(4), r: b})
	});
}