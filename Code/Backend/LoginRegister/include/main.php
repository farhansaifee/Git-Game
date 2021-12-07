<style>
    body {
        background-color: #14213d;
    }
</style>

<div class="main">
    <img src="resources/Logo_Orange.png" width="200px" alt="Logo">
    <div>
        <p class="head">Git Game</p>
    </div>
    <div id="first">
        <div class="login">
            <form action="index.php" method="post">
                <input class="my-3" type="text" placeholder="Username" name="username" maxlength="50" required><br>
                <input type="password" placeholder="Password" name="password" maxlength="50" required><br>
                <div class="mt-3">
                    <a href="">Forgot&nbsp;password?</a>&nbsp;&nbsp;
                    <button type="submit" class="login-button">Login</button>
                </div>
                <input type="hidden" name="method" value="login">
            </form>
        </div>
        <div class="mt-5">
            <button id="change" class="btn btn-link text-white">Create a new account!</button>
        </div>
    </div>
    <div id="second">
        <div class="login text-white">
            <form action="index.php" method="post">
                <input type="text" placeholder="Firstname" name="firstname" maxlength="50" required><br>
                <input type="text" placeholder="Lastname" name="lastname" maxlength="50" required><br>
                <input type="password" placeholder="Password" name="password" maxlength="50" pattern="[A-Za-z0-9\.\-_]{8,}" required><br>
                <input type="password" placeholder="Confirm password" name="passwordConfirm" maxlength="50" pattern="[A-Za-z0-9\.\-_]{8,}" required><br>
                <input type="email" placeholder="E-Mail" name="email" maxlength="50" required><br>
                <input type="text" placeholder="Username" name="username" maxlength="50" required><br>
                <select name="gender">
                    <option value="other">other</option>
                    <option value="male">male</option>
                    <option value="female">female</option>
                </select>
                <input type="hidden" name="method" value="register">

                <div class="mt-3">
                    <button type="submit" class="login-button">Register</button>
                </div>
            </form>
        </div>
        <div class="mt-5">
            <button id="goback" class="btn btn-link text-white">Back to Login</button>
        </div>
    </div>
</div>

<script>

</script>