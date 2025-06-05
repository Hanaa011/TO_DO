<!-- login.php -->
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول / إنشاء حساب</title>
  <style>
    body {
      background-color: #B069DB; 
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .container {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 300px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .toggle {
      margin-top: 15px;
      color: blue;
      cursor: pointer;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <div class="container">
    <form id="loginForm" method="POST" action="checklo.php">
      <h2>تسجيل الدخول</h2>
      <input type="text" name="username" placeholder="اسم المستخدم" required>
      <input type="password" name="password" placeholder="كلمة المرور" required>
      <button type="submit">دخول</button>
      <div class="toggle" onclick="toggleForm()">إنشاء حساب جديد؟</div>
    </form>

    <form id="registerForm" method="POST" action="register.php" style="display:none;">
      <h2>إنشاء حساب</h2>
      <input type="text" name="username" placeholder="اسم المستخدم" required>
      <input type="password" name="password" placeholder="كلمة المرور" required>
      <button type="submit">تسجيل</button>
      <div class="toggle" onclick="toggleForm()">لديك حساب بالفعل؟</div>
    </form>
  </div>

  <script>
    function toggleForm() {
      const login = document.getElementById('loginForm');
      const register = document.getElementById('registerForm');
      login.style.display = login.style.display === "none" ? "block" : "none";
      register.style.display = register.style.display === "none" ? "block" : "none";
    }
  </script>

</body>
</html>
