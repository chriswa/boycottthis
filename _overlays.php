<style>
.overlay { display: none; }
</style>

<div id="boycott-overlay" class="overlay">
  Thanks! We've added your pledge to our count!<br/>
  
  <form action="?" method="POST">
    <input type="hidden" name="pledgeNum" value=""/>
    <input name="email" placeholder="email address"/><br/>
    <label><input type="checkbox" name="issue_subscribe" value="1" checked="checked"/>Send me updates on this issue!</label><br/>
    <input type="submit" name="submit" value="Submit"/>
  </form>
  <button>Skip</button>
</div>

<div id="subscribe-overlay" class="overlay">
</div>

<div id="unboycott-overlay" class="overlay">
</div>
