<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>회원가입</title>
    <!---Custom CSS File--->
    <link rel="stylesheet" href="../css/accountstyle.css" />
  </head>
  <body>
    <section class="container">
      <header>회원가입</header>
      <form action="process_signup.php" class="form" method="post" name="signup">
        <div class="user-box">
          <h3>관리자이신가요?</h3>
          <div class="user-option">
            <div class="user">
              <input type="radio" id="check-customer" name="role" value="user" checked />
              <label for="check-customer">아니요, 일반 사용자입니다.</label>
            </div>
            <div class="user">
              <input type="radio" id="check-admin" name="role" value="admin" />
              <label for="check-admin">네, 관리자입니다.</label>
            </div>
          </div>
        </div>
        <button type="submit">계속하기</button>
      </form>
    </section>
  </body>
</html>
