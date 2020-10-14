

<div id="getReviews" style="position:absolute; width:100%;">

<?php 
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
          <form action="/papers/update" method="post">
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
?>
<!-----------    Rework this ------------------>
          <a href="'.BASE_URL.'papers/upvote?doi='.$this->doi.'">
          <span class="w3-btn w3-theme">I'm interested</span></a>
<!--------------------------------------------->
        </form>
        </div>

      <div id="tags" style="padding-top:25px;">
        <span class="w3-button w3-theme-l2" style="height: 30px;">Biology</span>
        <span class="w3-button w3-theme-l2" style="height: 30px;">Biomolecule</span>
        <span class="w3-button w3-theme-l2" style="height: 30px;">DNA</span>
      </div>

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
    <form action="../reviews/register" method="post"  
	<?php if ($_SESSION['email_status'] != 'verified') 
echo 'onsubmit="alert('."'You must login with a verified email address to submit reports.'".'); return false;"';?>
    >
	<input type="hidden" name="doi" value="<?=$this->doi?>">
	<input class="w3-btn w3-block w3-deep-orange w3-xlarge " style="border-radius:25px;max-width:300px;max-height:62px;margin:auto;" type="submit" name="add" value="Add your report">
      </form>
    </div>

  </div>

<?php endif;?>

</div>
