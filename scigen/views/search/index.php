<script>
function hintDOI(){
	// This function doesn't work publicly
	var xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status === 200){
			var hint = document.getElementById("DOIhint");
			hint.innerHTML = "Try: " + this.responseText + "<br><br>";
			hint.style.display = "inline";
			} 
		};
		xhr.onerror = function(){
			console.log("Couldn\'t retrive doi hint.");
		};
	xhr.open("GET", "/search/suggestion", true);
	xhr.send();
	}

</script>

<div class="fullscreen" >
    <form action="/search/search" method="get" style="padding-bottom:50px;">
<h1 id="title" style="height:100px;">Research reproducibility for all</h1>
      <input required type="text" class="w3-input w3-border" name="doi" placeholder="Search for a paper DOI"
	style="max-width:750px; margin:auto;"> <br>

<div class="w3-container">
<span id="DOIhint" class="w3-animate-opacity" >&nbsp; Try: <a href="<?=BASE_URL . 'reviews/get?doi=10.1063/1.1823034'?>">10.1063/1.1823034</a><br><br></span>
</div>
      <input class="w3-button w3-white w3-large" type="submit" name="submit" value="Can you reproduce it?">
    </form>
</div>
