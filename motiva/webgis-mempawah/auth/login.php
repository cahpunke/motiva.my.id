<?php
// Login Page
include '../templates/header.php';
?>
<div class="container">
    <h2>Login</h2>
    <form method="POST" action="proses_login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
<?php include '../templates/footer.php'; ?>
