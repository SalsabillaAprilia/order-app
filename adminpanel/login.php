<?php
    session_start();
    require "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
</head>

<style>
    .main{
        height: 100vh;
    }
    
    .login-box {
        width: 500px;
        box-sizing: border-box;
        border-radius: 10px;
    }

   .logo-brand {
            width: 150px; 
            height: auto;
            display: block;
            margin: 0 auto;
        }
</style>

<body>
<div class="main d-flex flex-column justify-content-center align-items-center">
    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="../image/newlogo.png" alt="Logo Brand" class="logo-brand">
    </div>

    <!-- Kotak form login -->
    <div class="login-box p-5 shadow">
        <form action="" method="post">
            <div>
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <div>
                <button class="btn btn-success form-control mt-3" type="submit" name="loginbtn">Login</button>
            </div>
        </form>
    </div>
    <?php 
                if(isset($_POST['loginbtn'])){
                    $username = htmlspecialchars($_POST['username']);
                    $password = htmlspecialchars($_POST['password']);

                    $query = mysqli_query($con, "SELECT * FROM users WHERE username='$username' ");
                    $countdata = mysqli_num_rows($query);
                    $data = mysqli_fetch_array($query); 

                    if($countdata>0){
                            if (password_verify($password, $data['password'])) {
                            $_SESSION['username'] = $data['username'];
                            $_SESSION['login'] = true;
                            header('location: ../adminpanel');
                        }
                        else{
                            ?>
                            <div class="alert alert-warning mt-2" style="width: 500px; margin: 10px auto;" role="alert">
                                Password Salah!
                            </div>
                            <?php
                        }
                    }
                    else{
                        ?>
                        <div class="alert alert-warning mt-2" style="width: 500px; margin: 10px auto;" role="alert">
                            Akun Tidak Tersedia!
                        </div>
                        <?php
                    }
                }
            ?>
</div>
</body>
</html>