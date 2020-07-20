<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<body>

<div class="w3-container w3-padding">
<form action="/info/contact" method="post">

  <label>Name</label>
  <input class="w3-input" name="name" placeholder="Your name">

  <label>Email</label>
  <input class="w3-input" name="email" placeholder="email address">

  <label>Select your type of contact</label>
  <select name="sortAs">
    <option value="contact">Contact</option>
    <option value="feedback">Feedback</option>
  </select>
  <br>
  <label>Subject</label>
  <input class="w3-input" name="subject">

  <label>Message</label><br>
  <textarea name="message" max="500">
  </textarea><br>

  <div class="g-recaptcha" data-sitekey="key"></div>
  
  <input class="w3-btn w3-theme" type="submit" name="submit" value="submit">
</form>
</div>
</body>
