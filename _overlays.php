<style>
.overlay { display: none; }
</style>

<div id="boycott-overlay" class="overlay">
  Thanks!<br/>
  We've added your pledge to our count!<br/>
  <br/>
  <form action="add-boycott.php" method="POST">
    <input type="hidden" name="boycott-overlay" value="1"/>
    <input type="hidden" name="uniq" value=""/>
    <input type="hidden" name="pledgeNum" value=""/>
    <input type="hidden" name="issue" value=""/>
    <input name="email" placeholder="email address"/><br/>
    <br/>
    <label><input type="checkbox" name="subscribed" value="1" checked="checked"/>Send me updates on this issue!</label><br/>
  </form>
</div>

<div id="subscribe-overlay" class="overlay">
</div>

<div id="unboycott-overlay" class="overlay">
</div>
