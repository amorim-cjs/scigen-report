<body>
<div>
<form class="w3-container w3-theme-l4 "  style="max-width:1000px;margin:auto;padding-bottom:50px;" action="register" method="post">
  <ul>
   
      <label>DOI:</label>
      <input class="w3-input" name="doi" value=<?= $_POST['doi'] ?> readonly>

      <label>Username:</label>
      <input class="w3-input" name="username" value="<?=$_POST['username']?>" readonly > 
<br>
      <label> Can you reproduce the results?   </label> 
      <?php if ($_POST['reproducible'] == "Y"):?> <label class="w3-tag w3-green"> Yes </label><?php endif; ?>
      <?php if ($_POST['reproducible'] == "K"):?> <label class="w3-tag w3-green"> Yes, partially </label><?php endif; ?>
      <?php if ($_POST['reproducible'] == "T"):?> <label class="w3-tag w3-green"> Yes, but it is tricky </label><?php endif; ?>
      <?php if ($_POST['reproducible'] == "P"):?> <label class="w3-tag w3-red"> No, but it seems possible </label><?php endif; ?>
      <?php if ($_POST['reproducible'] == "N"):?> <label class="w3-tag w3-red"> No </label><?php endif; ?>
<br><br>
      <input name="reproducible" type="hidden" value=<?=$_POST['reproducible']?>>

      <label>Reproduced p-value (optional):</label>
      <input class="w3-input" name="pvalue" value="<?=$_POST['pvalue']?>" readonly>

      <label>Reproduced correlation (optional):</label>
      <input class="w3-input" name="corr" value= "<?=$_POST['corr']?>" readonly>

      <label> Estimated accuacy:</label>
      <input class="w3-input" name="acc" value= "<?=$_POST['acc']?>" readonly>

      <label>List of missing parameters (if any)</label>
      <input class="w3-input" name="missing_param" value="<?=$_POST['missing_param']?>" readonly>

      <label>Comments:</label>
      <textarea class="w3-input" name="review" readonly><?=$_POST['review']?></textarea>

<?php if (!$this->exist && FALSE): //currently deactivated?>
      <label>If you want, we can send an email to the corresponding author in your behalf, letting them know about your reproducibility efforts. Just inform the author's email below, and we take care of the rest! <br>(leave it blank if you don't want to inform the author).</label>
      <input class="w3-input" type="email" name="author_email" maxlength="120" value="<?= $_POST['author_email'] ?>" placeholder="author@reproducible.edu">
<?php endif; if (isset($_POST['edit'])) :?>
	<input type="hidden" name="exist" value="true">
<?php endif; ?>
      <input  class="w3-btn w3-theme-d3" type="submit" name="correct"  value="Correct review">
      <input  class="w3-btn w3-theme-d3" type="submit" name=<?=isset($_POST['edit']) || $this->exist ? "edit": "submit"?>  value="Register review"
      onclick="return confirm('NOTE: You take full responsibility by your report. You also assure you have reproduced or tried to reproduce the reported results within reasonable tolerance. Reproducibility is a major pillar of science. USE WITH CARE!')">
<?php if (isset($_POST['edit'])) :?>
      <input  type="hidden" name="review_id" value=<?=$_POST['review_id']?>>
      <input  class="w3-btn w3-theme-d1" type="submit" name="delete" value="Delete review"
	onclick="return confirm('Are you sure you want to delete this review?')">
<?php else :?>
      <input  class="w3-btn w3-theme-l2" type="submit" name="cancel" value="Discard review">
<?php endif;?>

  </ul>
</form>
</div>
</body>
