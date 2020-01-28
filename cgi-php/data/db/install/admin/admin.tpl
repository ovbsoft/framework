
<form action="/" method="post">
    <p class="name">{ LE:MAIL }</p>
    <p class="input">
        <input type="text" name="mail" placeholder="{ LE:MAIL_PH }" value="{ MAIL }">
    </p>{ MAIL:WG }
    <p class="name">{ LE:USER }</p>
    <p class="input">
        <input type="text" name="user" placeholder="{ LE:USER_PH }" value="{ USER }">
    </p>{ USER:WG }
    <p class="name">{ LE:PASS }</p>
    <p class="input">
        <input type="password" name="pass" placeholder="{ LE:PASS_PH }" value="{ PASS }">
    </p>{ PASS:WG }
    <p class="name">{ LE:CONFIRM }</p>
    <p class="input">
        <input type="password" name="confirm" placeholder="{ LE:CONFIRM_PH }" value="{ CONFIRM }">
    </p>{ CONFIRM:WG }
    <p class="button">
        <button id="button" type="submit" name="post">{ LE:REGISTRATION-UPP }</button>
    </p>
</form>