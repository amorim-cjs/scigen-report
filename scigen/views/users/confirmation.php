<body>
<?php if ($this->confirmed):?>
<h2><b>Email confirmed!</b></h2>

<h2>You can now submit your own reports. The scientific community is glad with your contributions!</h2>
<?php else:?>
<h2><b>We couldn't verify your address</b></h2>

<h2>Check if no letters are missing from your confirmation link. If the link is correct, a connection issue may cause this error; try again later.</h2>
<?php endif;?>
</body>
