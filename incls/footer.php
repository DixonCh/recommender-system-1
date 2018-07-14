<?php if(empty($user)) {?><script>
	var screen = document.querySelector('#tscreen'); var trig = document.querySelectorAll(".c-rating"); var span = document.querySelectorAll(".close")[0]; 
	for(let x=0;x<trig.length;x++) {trig[x].addEventListener('click', function(){document.querySelector(".message").innerHTML = 'Sign up and start rating! <br><a href="register.php"><button class="util-btn">Sign up</button></a>'; screen.style.display = "block"; }); }
    span.onclick = function() {screen.style.display = "none"; }
    window.onclick = function(event) {if (event.target == screen) {screen.style.display = "none"; } }
</script><?php } ?><script src="global.js"></script>
<script src="rating.min.js"></script><script type="text/javascript">var el = document.querySelectorAll('.c-rating');for(let i=0;i<el.length;i++) {var myRating = rating(el[i], s[i], 5, r => sr(el[i].getAttribute("name"), r)); }</script></div>
</body>
</html>