<!DOCTYPE html>
<!-- Coding By CodingLab | www.codinglabweb.com -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!-- Custom CSS File -->
    <link rel="stylesheet" href="../css/accountstyle.css" />
    <title>Login 페이지</title>
  </head>
  <body>
    <section class="container">
      <header>로그인</header>
      <form action="process_signin.php" class="form" method="post">
      <div class="input-box">
        <label for="store_id">가게선택</label>
        <select name="store_id" id="store_id" class="select-box">
          <option value="" disabled selected>Select the store</option>
          <?php
          // DB 정보 불러오기
          include '../db_info.php';

          // STORE 테이블에서 가게 목록을 가져오기
          $sql = "SELECT store_id, store_name FROM STORE ORDER BY store_id ASC";
          $result = oci_parse($conn, $sql);
          oci_execute($result);

          while ($row = oci_fetch_assoc($result)) {
              echo "<option value='" . $row['STORE_ID'] . "'>" . $row['STORE_NAME'] . "</option>";
          }

          oci_free_statement($result);
          oci_close($conn);
          ?>
        </select><br>
        </div>

        <div class="input-box">
          <label for="email">이메일 입력</label>
          <input type="text" name="email" id="email" placeholder="Enter email address" required />
        </div>
        <div class="input-box">
          <label for="password">비밀번호 입력</label>
          <input type="password" name="password" id="password" placeholder="Password" required />
        </div>

        <div class="user-box">
          <h3>사용자 유형 선택</h3>
          <div class="user-option">
            <div class="user">
              <input type="radio" id="user" name="user_type" value="user" checked />
              <label for="user">고객</label>
            </div>
            <div class="user">
              <input type="radio" id="admin" name="user_type" value="admin" />
              <label for="admin">관리자</label>
            </div>
          </div>
        </div>
        <button type="submit">로그인</button>
      </form>
    </section>
  </body>
</html>