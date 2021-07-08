

<div id="getReviews" style="position:absolute; width:100%;">

<?php 
if (isset($this->id)){
	echo "User has been successfully linked to a paper review";
	header("Location: ".BASE_URL."reviews/get?doi=". $_POST['doi']);
}
if (isset($_POST['edit'])){
	$this->exist = true;
}
//$paper = $this->paper_data;
if(isset($this->paper_data)):
?>

<!-- Overview of paper reproducibility -->
  <div id="paper">
    <div id="paper-info" class="w3-panel w3-theme-l5 w3-padding" style="width:100%;">
      <div id="paper-title-wrap" class="w3-panel w3-theme-l5" style="position:fixed; top:50px; left: 0;width:100%;z-index:2;"><h2><b id="paper-title">
        <?= $paper['title'] ?></b></h2></div>
        <div id="paper-summary" class="w3-panel w3-row" style="position:relative; width:100%;">
      <div id="paper-authors" class="w3-col m9 w3-theme-l5" >
	<h2><?= $paper['authors'] ?></h2>
        <h3><?php
	  $reference="<i>". $paper['journal'] . '</i> ';
	  if ($paper['volume'] != 0) $reference .= 'vol.  <b>'.$paper['volume'].'</b>';
	  if ($paper['issue'] != 0)  $reference .=  ' issue ' . $paper['issue'];
	  if ($paper['page'] != 0)   $reference .=  ' p. ' . $paper['page'];
	  if ($paper['year'] != 0)   $reference .= ' ('. $paper['year'].')';
	  echo $reference;
?>      </h3>

        <div id="correct-info">
          <form action="/papers/update" method="post" >
          <input type="hidden" name="doi" value="<?=$this->doi?>">
          <input type="hidden" name="title" value="<?=$paper['title']?>">
          <input type="hidden" name="author" value="<?=$paper['authors']?>">
          <input type="hidden" name="journal" value="<?=$paper['journal']?>">
          <input type="hidden" name="year" value="<?=$paper['year']?>">
          <input type="hidden" name="volume" value="<?=$paper['volume']?>">
          <input type="hidden" name="issue" value="<?=$paper['issue']?>">
          <input type="hidden" name="page" value="<?=$paper['page']?>">
<?php if ($_SESSION['loggedin'] && $_SESSION['email_status'] != "verified"){
echo '<span class="w3-btn w3-theme"><b>Confirm your email</b> to suggest changes</span>';
	}
elseif ($_SESSION['loggedin'] && $_SESSION['email_status'] == "verified") {
	echo '<input type="submit" value="Suggest changes" class="w3-btn w3-theme">';
}
else {
	echo '<a href="'.BASE_URL.'login/index?doi='.$this->doi.'">',
		'<span class="w3-btn w3-theme">Login to suggest changes</span></a>';
}
?></form>
<!-----------   Interest  ------------------>
        
<?php if ($_SESSION['loggedin'] && !$this->upvoted):?>
     <button id="vote-btn" class="w3-btn w3-theme" onclick="switchInterest();" style="border-radius:25px;">
     + <?=$paper['interest']?></button>
<?php elseif ($_SESSION['loggedin'] && $this->upvoted):?>
    <button id="vote-btn" class="w3-btn w3-theme-l5" onclick="switchInterest();" style="border-radius:25px;">
    + <?=$paper['interest']?></button>
<?php else:?>
  <span class="w3-theme-l4" style="padding:8px 16px;border-radius:25px;">+ <?=$paper['interest']?></span>
<?php endif; ?>

<!--------------------------------------------->
        
        </div>
<!--------------Tags for the future------------------
      <div id="tags" style="padding-top:25px;">
        <span class="w3-button w3-theme-l2" style="height: 30px;">Biology</span>
        <span class="w3-button w3-theme-l2" style="height: 30px;">Biomolecule</span>
        <span class="w3-button w3-theme-l2" style="height: 30px;">DNA</span>
      </div>
--------------------------------------------->
      </div>
<div id="rep-chart" class="w3-col m2">
<canvas id="repCanvas" class="chartjs" width="400" height="400" style="float: right;"></canvas>
</div>
<script>generateChart();</script>
    </div>
    </div>

    <div id="paper-rep" style="padding-bottom: 40px;">
       <!-- <canvas id="repCanvas" class="chartjs" width="400" height="400"></canvas> -->
        
      <!--<div  class="w3-row-padding">
        <div class="w3-col m1"><p> </p></div>
          <div class="w3-col m3 w3-card 
          <?= ($paper['rep_success'] > $paper['rep_fail']) ? 'w3-green' : 'w3-pale-green'; ?> "
            style="min-width:40%;border-radius:35px;">
	    <div class="w3-display-container w3-center" >
	<img src="<?php echo BASE_URL. 'assets/images/yes.png';?>" class="w3-image" alt="reproduced">
	      <div class="w3-display-bottomleft"><h4 style="text-align:left;">
          <b><?= $paper['rep_success'] ?></b> person(s) reproduced this result 
          (<?php $percent = 100*$paper['rep_success'] / ($paper['rep_success'] + $paper['rep_fail']);
          if (is_nan($percent)) echo "0"; else echo $percent; ?> %),
           <?=$paper['partial']?> of them partially 
          (<?php $percent = 100*$paper['partial'] / ($paper['rep_success'] + $paper['rep_fail']);
          if (is_nan($percent)) echo "0"; else echo $percent; ?> %).<br>
          <?=$paper['tricky']?> of <?= $paper['rep_success'] ?> person(s) report it to be tricky.
	</h4></div>
	    </div>
          </div>
          <div class="w3-col m1"><p> </p></div>
          <div class="w3-col m3 w3-card 
          <?=$paper['rep_fail'] > $paper['rep_success'] ? 'w3-red' : 'w3-pale-red' ?>" 
          style="min-width:40%;border-radius:35px;">
	    <div class="w3-display-container w3-center">
	<img src="<?php echo BASE_URL. 'assets/images/no.png';?>" class="w3-image" alt="Not reproduced">
	      <div class="w3-display-bottomleft"><h4>
            <b><?= $paper['rep_fail'] ?></b> person(s) could not reproduce this result
            (<?php $percent = 100*$paper['rep_fail'] / ($paper['rep_success'] + $paper['rep_fail']) ;
            if (is_nan($percent)) echo "0"; else echo $percent;?> %)<br>
            <?=$paper['possible']?> of <?= $paper['rep_fail'] ?> person(s) believe it might be reproducible.
	  </h4></div>
	    </div>
          </div>
        </div> -->
<?php endif;?>
    </div>
  </div>

<div id="rev-sub" class="w3-panel w3-display-container" style="display:none;">
  <form class="w3-container w3-row w3-theme-l5 "  style="max-width:1000px;margin:auto;padding-bottom:50px;" action="confirm" method="post">
<label class="w3-text-red w3-large"><?=$this->message?></label> 
 
  <h3>Add a new report</h3>
  <div class="w3-col m6 w3-padding">
      <input type="hidden" class="w3-input" name="doi" placeholder="DOI" value=<?= $this->doi?> readonly>
      <input name="username" type="hidden" value=<?= $_SESSION['username']?>>
      <br><label> Can you reproduce this paper's the result(s)?</label>
      <p><input class="w3-radio" type="radio" name="reproducible" value="Y" 
	<?php if($_POST['reproducible'] == 'Y'):?> checked <?php endif;?>> Yes</p>
      <p><input class="w3-radio" type="radio" name="reproducible" value="T"
      <?php if($_POST['reproducible'] == 'T'):?> checked <?php endif;?>> Almost/Tricky (please explain)</p>
      <p><input class="w3-radio" type="radio" name="reproducible" value="K" 
	<?php if($_POST['reproducible'] == 'K'):?> checked <?php endif;?>> Partially (please explain)</p>
      <p><input class="w3-radio" type="radio" name="reproducible" value="P"
      <?php if($_POST['reproducible'] == 'P'):?> checked <?php endif;?>> Hardly (please explain)</p>
      <p><input class="w3-radio" type="radio" name="reproducible" value="N"
      <?php if($_POST['reproducible'] == 'N'):?> checked <?php endif;?>> No </p><br><br>
      </div>
      <div class="w3-col m6 3-padding">
      <label> Main <b>p-value</b>? (optional)</label>
      <input class="w3-input" name="pvalue" type="number" min="0" max="1" step="0.0001" <?php if (isset($_POST['pvalue'])) echo 'value="'. $_POST['pvalue'] .'"';?> placeholder="0.057">

      <label> Main <b>correlation</b> as a result? (optional)</label>
      <input class="w3-input" name="corr" type="number" min="-1" max="1" step="0.0001" <?php if (isset($_POST['corr'])) echo 'value="'. $_POST['corr'].'"' ;?> placeholder="-0.0874">

      <label> Estimate <b>Accuracy</b> (optional)</label>
      <input class="w3-input" name="acc" type="number" min="0" max="1" step="0.0001" <?php if (isset($_POST['acc'])) echo 'value="'. $_POST['acc'] . '"'; ?> placeholder="0.986">

      <label> Missing parameters (Leave blank if none, max. 250 characters)</label>
      <input class="w3-input" name="missing_param" maxlength="250" value="<?= $_POST['missing_param'] ?>" placeholder="E.g.: Parameter 'alpha' is said to be between 100 and 1000, but which value is used in fig. 2 is not clear">

      <label> Data/code (optional, max. 250 characters)</label>
      <input class="w3-input" name="data_code_link" maxlength="250" value="<?=$_POST['data_code_link']?>" placeholder="E.g.: https://zenodo.org/record/XXXXXXX">

      <label>Comments (max. 500 characters)</label>
      <textarea class="w3-input" name=review maxlength="500"><?= $_POST['review'] ?></textarea>

<?php if (!$this->exist && FALSE): //currently deactivated ?>
      <label>If you want, we can send an email to the corresponding author in your behalf, letting them know about your reproducibility efforts. Just inform the author's email below, and we take care of the rest! <br>(leave it blank if you don't want to inform the author).</label>
      <input class="w3-input" type="email" name="author_email" maxlength="120" value="<?= $_POST['author_email'] ?>" placeholder="author@reproducible.edu">
<?php endif; ?>
      </div>
      <h3><b>NOTICE:</b> BY SUBMITTING THIS FORM, 
      YOU CERTIFY THAT YOU, OR A GROUP YOU REPRESENT OR BELONG TO MADE REASONABLE EFFORTS TO REPRODUCE THIS PAPER.</h3>

      <input class="w3-btn w3-theme-d3" type="submit" name="confirm"  value="Confirm review">
	<?php if ($this->exist): ?>
	  <input type=hidden name="edit" value="true">
	  <input type=hidden name="review_id" value=<?=$_POST['review_id']?>>
	<?php endif; ?>

      <!--<a class="w3-btn w3-theme-l1" href="<?=BASE_URL.'reviews/get?doi='.$_POST['doi']?>">Cancel</a>-->
      <span onclick="document.getElementById('rev-sub').style.display='none'; document.getElementById('add-btn').style.display='block';" 
      class="w3-btn w3-theme-l1">Cancel</span>

  
</form>
</div>

<!-- Reviews input one by one -->
  <div id="reviews" class="w3-panel"  >
    <div id="user-rev" class="w3-row" >
      <div id="review-board" class="w3-col m10 w3-card w3-padding w3-theme-l4"
        style="max-width:1600px; margin-right:10%; border-radius:20px;" >
<?php $userHasReviewed = false;
foreach ($this->reviews_data as $review) :
?>     
	<?php $this->reviewHead($review);?>
	<?php if ($review['user_id'] == $_SESSION['user_id']):?>
	      <form action="register" method="post">
          <input type="hidden" name="doi" value=<?=$this->doi?>>
          <input type="hidden" name="reproducible" value=<?=$review['rep_status']?>>
          <input type="hidden" name="review" value="<?=$review['review']?>">
          <input type="hidden" name="pvalue" value=<?=$review['pvalue']?>>
          <input type="hidden" name="corr" value=<?=$review['corr']?>>
          <input type="hidden" name="acc" value=<?=$review['acc']?>>
          <input type="hidden" name="missing_param" value=<?=$review['missing_info']?>>
          <input type="hidden" name="exist" value="true">
          <input type="hidden" name="review_id" value=<?=$review['review_id']?>>
          <input class="w3-btn w3-theme" type="submit" name="correct" value="edit">
          <input class="w3-btn w3-theme" type="submit" name="delete" value="delete" 
            onclick="return confirm('Are you sure you want to delete this review?')">
	      </form>
  <?php endif; 
  $userHasReviewed = $userHasReviewed || ($review['user_id'] == $_SESSION['user_id']);?>
      
        <label> Comments: </label>
        <p class="w3-input w3-xlarge w3-theme-l5 w3-padding" style="padding-bottom:50px;"><?= $review['review'] ?></p>
<?php echo '</div>';
    endforeach;
?>
      </div>
    </div>
  </div>

<?php if (!$userHasReviewed && isset($this->paper_data)):?>
  <div id="add-review" class="w3-row-padding" style="width:99%;max-width:1600px;max-height:62px;margin:auto;top:-235px;">
    <div class="w3-col">
	<!--<input type="hidden" name="doi" value="<?=$this->doi?>">-->
  <button id="add-btn" class="w3-btn w3-block w3-deep-orange w3-xlarge " style="border-radius:25px;max-width:300px;max-height:62px;margin:auto;" type="submit" name="add"
  <?php if ($_SESSION['email_status'] != 'verified') {
echo 'onclick="alert('."'You must login with a verified email address to submit reports.'".'); return false;"';
  } else {
    echo 'onclick="document.getElementById('. "'rev-sub'" . ").style.display='block';
    document.getElementById('add-btn').style.display='none';" . '"';
  }?>>
Add your report </button>
    </div>

  </div>

<?php endif;?>

</div>
