<style>
.red{
  color:red;
}
</style>

<body>

<div class="w3-container ">
  <form action="delete" method="post">
    <p><br> <b>WARNING</b>: By deleting your account, we will  <span class="red">irreversibly delete</span> your information from our database. 
          This means that all the <b>reviews</b> you may have contributed will <b>also be deleted</b>. 
          We value every contribution, and give you the option of deleting your personal account,
          together with all your personal information but your name and reviews.
          If you choose this option, your reviews will still be public and signed by you,
          but no other information will be kept.<br>
        <p><input class="w3-radio" type="radio" name="delOption" value="minimum" checked>  
          Delete my account, but keep my reviews (<b>recommended</b>)</p>
        <p><input class="w3-radio" type="radio" name="delOption" value="all"> 
          Delete my account and all my reviews (irreversibly erase your contributions to science)</p>
          <input class="w3-input" type="password" name="password" placeholder="Password">
        <input class="w3-button" style="color:white;background-color:red;" type="submit" name="delete" value="Confirm">
	<a class="w3-btn w3-theme" href="<?=BASE_URL . 'users/profile'?>">Cancel</a>
        </form>
</div>

</body>
