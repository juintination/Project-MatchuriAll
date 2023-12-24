<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!---Custom CSS File--->
    <link rel="stylesheet" href="../css/accountstyle.css" />
    <title>관리자 및 가게 정보 입력</title>
  </head>
  <body>
    <section class="container">
      <header>관리자 및 가게 정보 입력</header>
      <form action="admin_register.php" class="form" method="post">
        <div class="input-box">
          <label for="adminName">관리자 이름</label>
          <input type="text" name="adminName" id="adminName" placeholder="Enter full name" required />
        </div>

        <div class="input-box">
          <label for="adminEmail">이메일</label>
          <input type="email" name="adminEmail" id="adminEmail" placeholder="Enter email address" required />
        </div>

        <div class="input-box">
          <label for="adminPw">비밀번호</label>
          <input type="password" name="adminPw" id="adminPw" placeholder="Enter Password" required />
        </div>

        <div class="column">
          <div class="input-box">
            <label for="adminPhone">핸드폰 번호</label>
            <input type="text" name="adminPhone" id="adminPhone" placeholder="Enter phone number" required />
          </div>
          <div class="input-box">
            <label for="adminBirth">생년월일</label>
            <input type="date" name="adminBirth" id="adminBirth" placeholder="Enter birth date" required />
          </div>
        </div>
        <div class="input-box address">
          <label for="storeName">가게 정보 입력</label>
          <input type="text" name="storeName" id="storeName" placeholder="Enter store name" required />
          <input type="text"  name="storeInfo" id="storeInfo" placeholder="Enter store information" required />
          <input type="text" name="classification" id="classification" placeholder="Enter store classification" required />
        </div>
        <button type="submit">등록</button>
      </form>
    </section>
  </body>
</html>
