<!DOCTYPE html>
<html>
<head>
    <title>Kiosk</title>
    <style>
        /* 상품 카드 스타일 */
        .product-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            width: 200px;
            float: left;
        }

        /* 장바구니 스타일 */
        .cart-container {
            clear: both;
        }

        /* 장바구니 아이템 스타일 */
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
            width: 200px;
            float: left;
        }

        /* 결제 버튼 스타일 */
        #checkout-btn {
            margin-top: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>상품 목록</h1>

    <?php
    // 데이터베이스 연결 설정
    $servername = "localhost";
    $username = "root";
    $password = "admin";
    $database = "demoDB";

    // 데이터베이스 연결
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

    // GET 파라미터로 전달된 store_id 확인
    if (isset($_GET['store_id']) && isset($_GET['user_id'])) {
        $store_id = $_GET['store_id'];
        $user_id = $_GET['user_id'];

        // 상품 정보를 데이터베이스에서 가져오는 쿼리
        $sql = "SELECT * FROM PRODUCT WHERE store_id = $store_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // 각 상품에 대한 정보를 나열
                echo "<div class='product-card'>";
                echo "<p><strong>상품명:</strong> " . $row['product_name'] . "</p>";
                echo "<p><strong>가격:</strong> $" . $row['product_price'] . "</p>";
                echo "<p><strong>재고:</strong> " . $row['product_stock'] . "</p>";

                // 수량 선택을 위한 입력 필드 추가
                echo "<label for='quantity_$row[product_id]'>수량:</label>";
                echo "<input type='number' id='quantity_$row[product_id]' name='quantity' min='1' value='1'>";

                // 장바구니 버튼 및 이벤트 핸들러 추가
                echo "<button onclick='addToCart(" . $row['product_id'] . ", \"$row[product_name]\", " . $row['product_price'] . ")'>장바구니 담기</button>";

                echo "</div>";
            }
        } else {
            echo "해당 상점에 등록된 상품이 없습니다.";
        }

        // 장바구니 표시
        echo "<div class='cart-container'>";
        echo "<h2>장바구니</h2>";
        echo "<div id='cart'></div>";

        // 결제 버튼
        echo "<button id='checkout-btn' onclick='checkout()'>결제하기</button>";
        echo "</div>";
    } else {
        echo "잘못된 매개변수입니다.";
    }

    $conn->close();
    ?>

    <script>
        var cart = [];

        function addToCart(productId, productName, productPrice) {
            // 수량 선택 필드에서 수량 가져오기
            var quantityField = document.getElementById('quantity_' + productId);
            var quantity = parseInt(quantityField.value);

            // 장바구니에 상품 추가
            var existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                // 이미 장바구니에 있는 상품이라면 수량 추가
                existingItem.quantity += quantity;
            } else {
                // 장바구니에 없는 상품이라면 새로 추가
                cart.push({ id: productId, name: productName, price: productPrice, quantity: quantity });
            }
            // 수량 선택 필드 초기화
            quantityField.value = 1;
            updateCart();
        }

        function updateCart() {
            var cartContainer = document.getElementById('cart');
            cartContainer.innerHTML = '';
            for (var i = 0; i < cart.length; i++) {
                var cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                var totalItemPrice = cart[i].price * cart[i].quantity;
                cartItem.innerHTML = '<p><strong>상품명:</strong> ' + cart[i].name + '</p>' +
                                     '<p><strong>가격:</strong> $' + cart[i].price + '</p>' +
                                     '<p><strong>수량:</strong> ' + cart[i].quantity + '</p>' +
                                     '<p><strong>총 가격:</strong> $' + totalItemPrice + '</p>' +
                                     '<button onclick="updateCartItem(' + cart[i].id + ', ' + i + ', 1)">수량 +</button>' +
                                     '<button onclick="updateCartItem(' + cart[i].id + ', ' + i + ', -1)">수량 -</button>' +
                                     '<button onclick="removeFromCart(' + i + ')">삭제</button>';

                cartContainer.appendChild(cartItem);
            }
        }

        function updateCartItem(productId, index, quantityChange) {
            cart[index].quantity += quantityChange;
            if (cart[index].quantity < 1) {
                removeFromCart(index)
            }
            updateCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function checkout() {
            // 장바구니에 담긴 각 상품의 개수가 현재 재고보다 많은지 확인
            var insufficientStock = false;
            for (var i = 0; i < cart.length; i++) {
                var productId = cart[i].id;
                var quantity = cart[i].quantity;

                // 현재 상품의 재고를 가져오는 쿼리 실행
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            if (response !== "") {
                                alert(response);
                                insufficientStock = true;
                                return;
                            }
                        } else if (xhr.status === 400) {
                            // 재고가 부족한 경우의 처리
                            alert('상품 ' + productId + '의 재고가 부족합니다.');
                            insufficientStock = true;
                            return;
                        } else {
                            console.error('상품 재고 조회 실패');
                            insufficientStock = true;
                            return;
                        }
                    }
                };

                xhr.open('GET', 'check_product_stock.php?product_id=' + productId + '&quantity=' + quantity, false);
                xhr.send();
            }

            // 재고 부족한 상품이 있다면 결제를 중단
            if (insufficientStock) {
                return;
            }

            // 결제 처리
            for (var i = 0; i < cart.length; i++) {
                // 상품의 재고를 감소시키는 쿼리 실행
                var productId = cart[i].id;
                var quantity = cart[i].quantity;

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // 상품 결제 성공
                            var response = xhr.responseText;
                            if (response !== "") {
                                alert(response);
                            }

                            // 결제 후 장바구니 비우기
                            cart = [];
                            updateCart();

                            // 페이지 새로고침
                            location.reload();
                            alert('결제가 완료되었습니다.');
                        } else if (xhr.status === 400) {
                            // 재고가 부족한 경우의 처리
                            alert('상품 ' + productId + '의 재고가 부족합니다.');
                        } else {
                            console.error('상품 결제 실패');
                        }
                    }
                };

                xhr.open('GET', 'update_product_stock.php?product_id=' + productId + '&quantity=' + quantity, false);
                xhr.send();
            }
        }
    </script>
</body>
</html>
