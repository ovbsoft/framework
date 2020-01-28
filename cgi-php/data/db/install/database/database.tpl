
<form action="{ REQUEST }" method="post">
    <p class="name">{ LE:HOST }</p>
    <p class="input">
        <input type="text" name="host" placeholder="{ HOST:PH }" value="{ HOST }">
    </p>
    <p class="name">{ LE:USER }</p>
    <p class="input">
        <input type="text" name="user" placeholder="{ USER:PH }" value="{ USER }">
    </p>
    <p class="name">{ LE:PASS }</p>
    <p class="input">
        <input type="password" name="pass" placeholder="{ PASS:PH }" value="{ PASS }">
    </p>
    <p class="name">{ LE:BASE }</p>
    <p class="input">
        <input type="text" name="base" placeholder="{ BASE:PH }" value="{ BASE }">
    </p>{ WARNING }
    <p class="button">
        <button id="button" type="submit" name="post">{ LE:SAVE-UPP }</button>
    </p>
</form>