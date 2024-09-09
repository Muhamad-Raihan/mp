<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="copycss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login Page</title>
</head>
<body background="../img/ywhite.png">
    <div class="form-box">
        <h1>LOGiN FORM</h1>
        <div class="login-container">
            <div class="image-section">
                <img src="../img/download.jfif" alt="Image" class="login-image">
            </div>
            <div class="input-section">
                <div class="input-box">
                    <i class="fa-regular fa-envelope"></i>
                    <label>Username</label>
                    <input type="text" placeholder="Input your username" name="username"><br><br>
                    <i class="fa fa-key"></i>
                    <label>Password</label>
                    <input type="password" placeholder="Put down your password" id="myInput" name="password">
                    <span class="eye" onclick="myFunction()">
                        <i id="hide1" class="fa fa-eye"></i>
                        <i id="hide2" class="fa fa-eye-slash"></i>
                    </span>
                </div>
                <button type="button" class="login-btn">LOGIN</button>
            </div>
        </div>
    </div>
<script src="index.js"></script>
<footer class="footer">
    <p>Created by Muhammad Raihan Syahfitrah - 2024</p>
</footer>
</body>
</html>