function sr(b, c){
	fetch('rate.php', {
	  method: 'post',
	  headers: {
	    'Accept': 'application/json',
	    'Content-Type': 'application/json'
	  },
	  credentials: 'include',
	  body: JSON.stringify({fid: b.substr(4), r: c})
	});
}