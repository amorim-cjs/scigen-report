<div id="addPaper">

<?php
session_start();

if (!$_SESSION['loggedin']) header('Location :'.BASE_URL);

if (isset($this->id)){
	echo "New paper has been successfully added";
}

?>

<form class="w3-container" style="max-width:1000px;margin:auto;" action="/papers/update" method="post">
  <ul>

      <label> DOI </label>
      <input class="w3-input" name="doi" placeholder="DOI number" value="<?=$this->doi ?>" readonly >
 
      <label>Authors</label>
	<input class="w3-input"  name="author" placeholder="A. Seeker et al." value="<?=$this->author ?>" readonly>

      <label>Title</label>
	<input class="w3-input" name="title" placeholder="Important study on important stuff in important field" value="<?=$this->title ?>" readonly>
 
      <label>Journal or equivalent (e.g., "Proceedings of Important Conference")</label>
      <input class="w3-input" name="journal"placeholder="International Journal of Research" value="<?=$this->journal ?>"
	  <?php if ($this->autofetch) echo "readonly"; ?>>

      <label>Year</label>
      <input class="w3-input" name="year" placeholder="1999" type="year" value="<?=$this->year ?>"
	  <?php if ($this->autofetch) echo "readonly"; ?>>

      <label>Volume</label>
      <input class="w3-input" name="volume" placeholder="123" type="number" value = "<?=$this->volume?>"
	  <?php if ($this->autofetch) echo "readonly"; ?>>

      <label>Issue</label>
      <input class="w3-input" name="issue" placeholder="4" type="number" value = "<?=$this->issue?>"
	  <?php if ($this->autofetch) echo "readonly"; ?>>

      <label>Page or Paper id</label>
      <input class="w3-input" name="page" placeholder="567" value = "<?=$this->page ?>"
	  <?php if ($this->autofetch) echo "readonly"; ?>>

   <input class="w3-btn w3-theme-d1" type="submit" name="submit" value="Update">
</form>
</div>
