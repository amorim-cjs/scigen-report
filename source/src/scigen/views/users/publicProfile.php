<div class="w3-container" style="margin:auto;padding-bottom:50px;">

<h1><b><?= $this->user['given_name'] . " " . $this->user['family_name']?></b></h1>
<h2><?= $this->user['affiliation']?> </h2>
<h2>Expertise: <?= $this->user['expertise']?> </h2>


<!-- # of contributions goes here -->
<?php 
$reproduced = 0;
foreach ($this->reviews as $review){
  if ($review['rep_status'] == 'Y' || $review['rep_status'] == 'T' || $review['rep_status'] == 'K') $reproduced++;
}
?>
<h3><b><?= sizeof($this->reviews)?></b> review(s) submitted.<br>
 <b><?= $reproduced?></b> papers successfully reproduced, <b><?=sizeof($this->reviews) - $reproduced?></b> negative results.</h3>

<!-- list of contributions goes here -->
  <div class="report-list">
<?php foreach ($this->reviews as $review): ?>
<a href="<?=BASE_URL.'reviews/get?doi='.$review['doi']?>">
  <h3><b><?=$review['title']?></b>, DOI:<?=$review['doi']?></h3></a>
  <p><?php
    switch ($review['rep_status']){
      case 'Y':
        echo "<span class='w3-tag w3-green w3-xlarge' style='transform:rotate(-3deg);'>Successfully reproduced";
        break;
      case 'T':
        echo "<span class='w3-tag w3-green w3-xlarge' style='transform:rotate(-3deg);'>Reproduced, but tricky";
	break;
      case 'K':                                                                                                                       echo "<span class='w3-tag w3-green w3-xlarge' style='transform:rotate(-3deg);'>Reproduced partially";                   break;

      case 'P':
        echo "<span class='w3-tag w3-red w3-xlarge' style='transform:rotate(3deg);'>Not reproduced, but seems possible";
        break;
      case 'N':
        echo "<span class='w3-tag w3-red w3-xlarge' style='transform:rotate(3deg);'>Failed to reproduce";
        break;
      default:
      break;
    }
  ?></span></p>

<?php endforeach; ?>
</div>
</div>
<br><br><br>
