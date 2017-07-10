<!doctype html>
<html lang="en">
[[$chunk_head]]
<body id="login">
<div class="wrapper">
    <div class="container body_title_container">
        <h1><span class="body_title_logo"><img src="image/logo.png" ></span>Manager Login</h1>
    </div>
    <div class="container login_form_container">
        <div class="login_form_message">[[*post_result_message]]</div>
        <form class="login_form" method="post" action="">
            <input type="hidden" name="complementary" value="[[*complementary]]">
            <input type="text" name="username" value="[[*username]]" placeholder="Username">
            <input type="password" name="password" value="" placeholder="Password">
            <input type="submit" value="Login">
        </form>
    </div>
</div>
[[+script]]
</body>
</html>