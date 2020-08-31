<?php
class Reviews_View extends Base_View{
	public function __construct(){
		parent::__construct();
	}

	public function render($name){
		require("scigen/views/layout/reviewHeader.php");
		require_once("scigen/views/$name.php");
		require_once("scigen/views/layout/footer.php");
	}

	public function reviewHead($review){
		$hasher = new Hasher();
		$tag = $hasher->makeTag($review['user_id'], $review['review_id']);

		echo '<div id="' . $tag . '">';

		echo "<a href=" . BASE_URL . "users/profile/" . $hasher->makeTag($review['user_id'], 0) . ">";
		echo "<h3><b>" . $review['given_name'] . " " . $review['family_name'] . "</b></h3></a>";
		echo "<h4>";

	
		echo '<a href="' . BASE_URL . "reviews/get/?doi=" . $_GET['doi'] . '#' . $tag . '">';
		$this->report($review['rep_status']);
		echo '</a>';

		echo '&nbsp; <button class="w3-btn w3-theme-l3 w3-small" onclick="copyLink(\''. $tag .'\');">
			Copy link</button>';

		if ($review['pvalue'] != 0) echo ", obtaining p-value = " . $review['pvalue'];
		if ($review['corr'] != 0) echo ", (main) correlation = " . $review['corr'];
		if ($review['acc'] != 0) echo ", estimated accuracy = " . $review['acc'];
		if (!empty($review['missing_param'])) "reporting missing information: " . $review['missing_param'];

		echo "</h4>";

	}
	public function report($status){
		switch ($status){
		case 'Y':
			echo "<b>Succeeded</b> in replicating results";
			break;
		case 'K':
			echo "<i>Partially</i> <b>Succeeded</b> in replicating results";
			break;
		case 'T':
			echo "It was <i>tricky</i>, but succeeded in replication";
			break;
		case 'P':
			echo "Could not replicate results, but it seems <i>possible</i>";
			break;
		case 'N':
			echo "<b>Could not replicate</b>";
			break;
		default:
			break;
		}
	}

}
?>
