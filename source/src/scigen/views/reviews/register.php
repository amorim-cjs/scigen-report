<body>
<div>
<?php
if (isset($this->id)){
	echo "User has been successfully linked to a paper review";
	header("Location: ".BASE_URL."reviews/get?doi=". $_POST['doi']);
}
if (isset($_POST['edit'])){
	$this->exist = true;
}
?>

<form class="w3-container w3-theme-l4 "  style="max-width:1000px;margin:auto;padding-bottom:50px" action="confirm" method="post">
<label class="w3-text-red w3-large"><?=$this->message?></label> 
 <ul>
  <label>DOI</label>
      <input class="w3-input" name="doi" placeholder="DOI" value=<?= $_POST['doi']?> readonly>
      <input name="username" type="hidden" value=<?= $_SESSION['username']?>>
      <br><label> Can you reproduce this paper's the result(s)?</label>
      <p><input class="w3-radio" type="radio" name="reproducible" value="Y" 
	<?php if($_POST['reproducible'] == 'Y'):?> checked <?php endif;?>> Yes, within margin, all of it as written</p>
      <p><input class="w3-radio" type="radio" name="reproducible" value="T"
      <?php if($_POST['reproducible'] == 'T'):?> checked <?php endif;?>> Yes, within margin, but extra information was needed (please clarify)</p>
      <p><input class="w3-radio" type="radio" name="reproducible" value="K" 
	<?php if($_POST['reproducible'] == 'K'):?> checked <?php endif;?>> Yes, but only partially (please specify)</p>
      <p><input class="w3-radio" type="radio" name="reproducible" value="P"
      <?php if($_POST['reproducible'] == 'P'):?> checked <?php endif;?>> No, but it seems possible with extra information (please explain)</p>
      <p><input class="w3-radio" type="radio" name="reproducible" value="N"
      <?php if($_POST['reproducible'] == 'N'):?> checked <?php endif;?>> No, results were different or I/we couldn't realize paper's procedure </p><br><br>

      <label> If this paper reports a <b>p-value</b>, what value did you obtain? (optional, use main result if many or last in the abstract)</label>
      <input class="w3-input" name="pvalue" type="number" min="0" max="1" step="0.0001" <?php if (isset($_POST['pvalue'])) echo 'value="'. $_POST['pvalue'] .'"';?> placeholder="0.057">

      <label> If this paper reports a <b>correlation</b>, what value did you obtain? (optional, use main result if many or last in the abstract)</label>
      <input class="w3-input" name="corr" type="number" min="-1" max="1" step="0.0001" <?php if (isset($_POST['corr'])) echo 'value="'. $_POST['corr'].'"' ;?> placeholder="-0.0874">

      <label> Can you estimate a correlation or ratio between your results and the orignal publication (optional, estimated accuracy)</label>
      <input class="w3-input" name="acc" type="number" min="0" max="1" step="0.0001" <?php if (isset($_POST['acc'])) echo 'value="'. $_POST['acc'] . '"'; ?> placeholder="0.986">

      <label> If there are missing parameters for reproduction of results, which ones? (Leave blank if none, max. 250 characters)</label>
      <input class="w3-input" name="missing_param" maxlength="250" value="<?= $_POST['missing_param'] ?>" placeholder="E.g.: Parameter 'alpha' is said to be between 100 and 1000, but which value is used in fig. 2 is not clear">

      <label> If there is a data/code, please add it here.</label>
      <input class="w3-input" name="data_code_link" value="<?=$_POST['data_code_link']?>" placeholder="E.g.: https://zenodo.org/record/XXXXXXX - where multiple Xs stand for the Zenodo record number">

      <label>Comments (max. 500 characters)</label>
      <textarea class="w3-input" name=review maxlength="500"><?= $_POST['review'] ?></textarea>

<?php if (!$this->exist && FALSE): //currently deactivated ?>
      <label>If you want, we can send an email to the corresponding author in your behalf, letting them know about your reproducibility efforts. Just inform the author's email below, and we take care of the rest! <br>(leave it blank if you don't want to inform the author).</label>
      <input class="w3-input" type="email" name="author_email" maxlength="120" value="<?= $_POST['author_email'] ?>" placeholder="author@reproducible.edu">
<?php endif; ?>
      <h3><b>NOTICE:</b> BY SUBMITTING THIS FORM, 
      YOU CERTIFY THAT YOU, OR A GROUP YOU REPRESENT OR BELONG TO MADE REASONABLE EFFORTS TO REPRODUCE THIS PAPER.</h3>

      <input class="w3-btn w3-theme-d3" type="submit" name="confirm"  value="Confirm review">
	<?php if ($this->exist): ?>
	  <input type=hidden name="edit" value="true">
	  <input type=hidden name="review_id" value=<?=$_POST['review_id']?>>
	<?php endif; ?>

      <a class="w3-btn w3-theme-l1" href="<?=BASE_URL.'reviews/get?doi='.$_POST['doi']?>">Cancel</a>

  </ul>
</form>
</div>
</body>
