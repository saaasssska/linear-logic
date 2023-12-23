<?php
// Инициализировать сеанс (Initialize the session)
session_start();
 
// пользователь будет перенаправлен на страницу приветствия, если войдет в систему 
// (the user will be redirected to welcome page if logged in)
// $_SESSION — Session variables
// An associative array containing session variables available to the current script.

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Включить файл конфигурации (Include config file)
require_once "config.php";
 
// Определите переменные и инициализируйте их пустыми значениями. 
// (Define variables and initialize with empty values)
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Обработка данных формы при отправке формы 
// (Processing form data when form is submitted)
// $_SERVER — Server and execution environment information
// $_SERVER is an array containing information 
// such as headers, paths, and script locations.

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Проверьте, пусто ли имя пользователя (Check if username is empty)
    // $_POST — HTTP POST variables
    // An associative array of variables passed to the current script via the HTTP POST method
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Проверьте, пуст ли пароль (Check if password is empty)
    // The trim() function in PHP removes whitespace or any other predefined character 
    // from both the left and right sides of a string.

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Подтвердить учетные данные (Validate credentials)
    if(empty($username_err) && empty($password_err)){
        // Подготовьте оператор выбора (Prepare a select statement)
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Привяжите переменные к подготовленному оператору в качестве параметров. 
            //(Bind variables to the prepared statement as parameters)

             mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Установить параметры (Set parameters)
            $param_username = $username;
            
            // Попытка выполнить подготовленный оператор (Attempt to execute the prepared statement)
            if(mysqli_stmt_execute($stmt)){
                // Сохранить результат (Store result)
                mysqli_stmt_store_result($stmt);
                
                // Проверьте, существует ли имя пользователя, если да, то проверьте пароль. 
                //(Check if username exists, if yes then verify password)

                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Привязать переменные результата (Bind result variables)

                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Пароль правильный, поэтому начните новый сеанс. 
                            // (Password is correct, so start a new session)
                            session_start();
                            
                            // Храните данные в переменных сеанса (Store data in session variables)
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Перенаправить пользователя на страницу приветствия (Redirect user to welcome page)
                            header("location: ../main.html");
                        } else{
                            // Пароль недействителен, отобразить общее сообщение об ошибке. (Password is not valid, display a generic error message)
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Имя пользователя не существует, отобразить общее сообщение об ошибке (Username doesn't exist, display a generic error message)
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Закрыть заявление (Close statement)
            mysqli_stmt_close($stmt);
        }
    }
    
    // Закрыть соединение (Close connection)
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../main.css">
    <style>
        body{ font: 14px sans-serif; }
        body:after {
  content: "";
  position: fixed;
  top: 0; bottom: 0; left: 0; right: 0; 
  background: rgba(0,0,0,0.1);
  pointer-events: none;
}
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
<div class="container">
    <header class="navbar">
      <div class="logo">L<span>in</span>ear Logic</div>
      <nav>
        <ul>
          <li><a href="../main.html">Home</a></li>
          <li><a href="../lessons.html">Lessons</a></li>
          <li><a href="../tests.html">Test</a></li>
          <li><a href="#">Привет</a></li>
        </ul>
      </nav>
    </header>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>