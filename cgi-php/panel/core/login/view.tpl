<!DOCTYPE html>
<html lang="{ LANG }">
    <head>
        <meta charset="utf-8">
        <meta name="google" content="notranslate">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{ LT:TITLE }</title>
        <link rel="icon" href="/panel/favicon.ico">
        <link rel="stylesheet" href="/panel/main.css">
        <link rel="stylesheet" href="/panel/480.css">
    </head>
    <body>
        <div id="header">
            <div class="container">
                <div class="left"><div class="logo"></div></div>
                <div class="right"><a href="/">{ LT:SIGN_OUT-UPP }</a></div>{ MULTILANG }
                <div class="clear"></div>
            </div>
        </div>
        <div class="container">
            <div id="route">
                <p><span>&#187;</span>{ LT:ROUTE }</p>
                <div class="clear"></div>
            </div>
            <form action="{ REQUEST }" method="post">
                <p class="name">{ LT:MAIL }</p>
                <div class="input"><input type="text" name="mail" placeholder="{ MAIL:PH }" value="{ MAIL }"></div>
                <p class="name">{ LT:PASS }</p>
                <div class="input"><input type="password" name="pass" placeholder="{ PASS:PH }" value=""></div>{ WARNING }
                <div class="button"><button id="button" type="submit" name="login">{ LT:SIGN_IN-UPP }</button></div>
            </form>
        </div>
    </body>
</html>
