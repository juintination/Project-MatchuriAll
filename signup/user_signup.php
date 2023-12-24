<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!---Custom CSS File--->
    <link rel="stylesheet" href="../css/accountstyle.css" />
    <title>일반 회원 회원가입</title>
  </head>
  <body>
    <section class="container">
      <header>일반 회원 회원가입</header>
      <form action="user_register.php" class="form" method="post">
        <div class="input-box">
          <label for="storeID">가게 선택</label>
          <select name="storeID" id="storeID" class="select-box" required>
            <option value="" disabled selected>Select the store</option>
            <?php
            // DB 정보 불러오기
            include '../db_info.php';

            // 가게 목록 불러오기
            $sql = "SELECT store_id, store_name FROM STORE ORDER BY store_id ASC";
            $result = oci_parse($conn, $sql);
            oci_execute($result);

            while ($row = oci_fetch_assoc($result)) {
                $storeID = $row["STORE_ID"];
                $storeName = $row["STORE_NAME"];
                echo "<option value='$storeID'>$storeName</option>";
            }

            oci_free_statement($result);
            oci_close($conn);
            ?>
        </select>
        </div>
        
        <div class="input-box">
          <label for="customerName">이름</label>
          <input type="text" name="customerName" id="customerName" placeholder="Enter full name" required />
        </div>

        <div class="input-box">
          <label for="customerEmail">이메일</label>
          <input type="text" name="customerEmail" id="customerEmail" placeholder="Enter email address" required />
        </div>

        <div class="input-box">
          <label for="customerPw">비밀번호</label>
          <input type="password" name="customerPw" id="customerPw" placeholder="Enter password" required />
        </div>

        <div class="column">
          <div class="input-box">
            <label for="customerPhone">핸드폰 번호</label>
            <input type="text" name="customerPhone" id="customerPhone" placeholder="Enter phone number" required />
          </div>
          <div class="input-box">
            <label for="customerBirth">생년월일</label>
            <input type="date" name="customerBirth" id="customerBirth" placeholder="Enter birth date" required />
          </div>
        </div>
        <div class="user-box">
          <h3>성별</h3>
          <div class="user-option">
              <div class="user">
                  <input type="radio" id="male" name="customerSex" value="남자" />
                  <label for="male">남자</label>
              </div>
              <div class="user">
                  <input type="radio" id="female" name="customerSex" value="여자" />
                  <label for="female">여자</label>
              </div>
          </div>
        </div>
        <button type="submit" value="회원가입">회원가입</button>
      </form>
    </section>
  </body>
</html>