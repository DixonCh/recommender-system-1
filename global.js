function sr(a, b, c){
	fetch('rate.php', {
	  method: 'post',
	  headers: {
	    'Accept': 'application/json',
	    'Content-Type': 'application/json'
	  },
	  credentials: 'include',
	  body: JSON.stringify({rtid: a, fid: b.substr(4), r: c})
	}).then(res => res.text()).then(data => console.log(data));
}