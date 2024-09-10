
<div style="flex: 1;">
<div id="result"></div>

<div style="display: flex; flex-direction: column;">
<div id="show_case"></div>
<input type='hidden' value='' id='message_id'>
<div id="user_dash" style="visibility: visible; display: flex">
    <img src="emoji/cat.gif" height="100">
    <div>
        <textarea id="textaria" name="textaira" rows="4" cols="37"></textarea>
        <div id="error" style="display: none;"></div>
        <div id="suggesstion-box"></div>
    </div>
</div>

<div class="join" id="join" style="display: none;">
    <h3>Join Chat</h3>
    <form method="post" id="user-form">
        <input type="text" name="username" id="username" placeholder="your name?"><br>
        <button class="btn" id="btn-submit">join</button>
    </form>
</div>
<div style="clear: both"></div>
</div>
</div>