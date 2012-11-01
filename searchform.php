
<form class="form-search" role="search" method="get" action="<?=home_url( '/' )?>">
	<label for="s">Search:</label><br />
	<input type="text" class="input-large" value="<?=htmlentities($_GET['s'])?>" name="s" placeholder="Enter your search term here...">
	<button type="submit" class="btn">Search</button>
</form>