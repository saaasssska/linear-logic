<?php
// Включить файл конфигурации (Include config file)
require_once "config.php";
 
// Определите переменные и инициализируйте их пустыми значениями. (Define variables and initialize with empty values)
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Обработка данных формы при отправке формы (Processing form data when form is submitted)
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Подтвердите имя пользователя (Validate username)
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Подготовьте оператор выбора (Prepare a select statement)
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Привяжите переменные к подготовленному оператору в качестве параметров.
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Установить параметры
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Попытка выполнить подготовленный оператор
            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement (Закрыть заявление)
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password (Подтвердить пароль)
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password (Подтвердите пароль)
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database 
    // Проверьте ошибки ввода перед вставкой в базу данных

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Подготовьте оператор вставки (Prepare an insert statement)
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            // Привяжите переменные к подготовленному оператору в качестве параметров.

            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Установить параметры (Set parameters)
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Попытка выполнить подготовленный оператор 
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Закрыть заявление (Close statement)
            mysqli_stmt_close($stmt);
        }
    }
    
    // Закрыть связь (Close connection)
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>