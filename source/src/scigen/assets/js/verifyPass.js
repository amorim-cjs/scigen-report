function checked(){
	if (document.getElementById('password').value ==
		document.getElementById('password2').value
		{
			document.getElementById('message').style.color = 'green';
			document.getElementById('message')innerHTML = 'Passwords match';
			return true;
		} else {
			document.getElementById('message').style.color = 'red';
			document.getElementById('message')innerHTML = 'Passwords do not match';
			return false;
		}
}
