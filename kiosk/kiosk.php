<!DOCTYPE html>
<html>
<head>
    <title>Kiosk</title>
    <style>
        .menu {
            max-width: 1500px;
            margin: 0 auto;
            width: 100%;
            flex-wrap: wrap;
        }

        /* 상품 카드 스타일 */
        .product-card {
            box-sizing: border-box;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            width: calc(16.666% - 20px);
            float: left;
            border-radius: 10px;
            box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.3);
            background-color: #FFFFFF;
            color: #333;
        }

        /* 장바구니 스타일 */
        .cart-container {
            margin: 0 auto;
            clear: both;
            width: 99%;
            position: relative;
            /* 부모 요소에 position: relative 설정 */
        }

        .styled-cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ddd;
            margin-bottom: 7px;
            padding: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            font-family: 'Noto Sans KR', sans-serif;
        }

        .item-info {
            flex: 1;
            text-align: left 20px;
        }

        .item-price,
        .item-quantity,
        .item-total {
            flex-basis: 100px;
            text-align: center;
        }

        .item-buttons {
            flex-basis: 150px;
            text-align: right 10px;
        }

        .cart-item p {
            margin-bottom: 5px;
        }

        .cart-item-buttons {
            display: flex;
            justify-content: space-between;
        }

        .cart-btn {
            padding: 5px 10px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border-radius: 3px;
        }

        .cart-btn:hover {
            background-color: #45a049;
        }

        h1 {
            font-size: 50px;
            text-align: center;
            color: #FFFFFF;
            font-family: 'Gugi', sans-serif;
        }

        h2 {
            padding:10px;
            text-align: center;
            color: #FFFFFF;
            background-color: #191970;
            border-radius: 10px;
            border-style: solid;
            border-width: 1px;
            font-family: 'Jua', sans-serif;
        }

        .quantity {
            width: calc(100% - 30px);
            height: 30px;
            color: #707070;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0 15px;
        }

        #put-in {
            margin-top:10px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition-duration: 0.4s;
            width: 100%;
            height: 40px;
        }

        #put-in:hover {
            background-color: #45a049;
        }

        .num-btn {
            background-color: #4CAF50;
            color: white;
            width: 30px;
            height: 30px;
            font-size: 16px;
        }

        .delete-btn {
            border-radius: 5px;
            float:right;
            background-color: #4CAF50;
            color: white;
            width: 80px;
            height: 30px;
            font-size: 16px;
        }

        footer {
            position: relative;
            bottom: 0;
            width: 99%;
            margin: 0 auto;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .checkout-button-container {
            text-align: center;
            position: relative;
            top: 10px;
            bottom: 10px;
            display: flex;
            justify-content: center;
            width: 100%;
            margin: 0 auto;
        }

        #checkout-btn {
            display:inline-block;
            margin: 10px;
            background-color: #ff3c26;
            color: white;
            padding: 10px 5px;
            border-radius: 10px;
            border: none;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 25px;
            cursor: pointer;
            transition-duration: 0.4s;
            position: relative;
            height: 50px;
            flex: 1;
        }

        #checkout-btn:hover {
            background-color: #db200b;
        }

        #back-btn {
            display:inline-block;
            margin: 10px;
            background-color: #ff3c26;
            color: white;
            padding: 10px 5px;
            border-radius: 10px;
            border: none;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 25px;
            cursor: pointer;
            transition-duration: 0.4s;
            position: relative;
            height: 50px;
            flex: 1;
        }

        #back-btn:hover {
            background-color: #db200b;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gugi&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300&display=swap" rel="stylesheet">
</head>

<body style="background-color: #4d59db;">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <h1>Machuriall Kiosk</h1>
    <div class="menu">
        <?php
        // DB 정보 불러오기
        include '../db_info.php';

        // GET 파라미터로 전달된 store_id 확인
        if (isset($_GET['store_id']) && isset($_GET['customer_id'])) {
            $store_id = $_GET['store_id'];
            $customer_id = $_GET['customer_id'];

            // 상품 정보를 데이터베이스에서 가져오는 쿼리
            $sql = "SELECT * FROM PRODUCT WHERE store_id = $store_id";
            $stmt = oci_parse($conn, $sql);
            oci_execute($stmt);

            while ($row = oci_fetch_assoc($stmt)) {
                // 각 상품에 대한 정보를 나열
                echo "<div class='product-card'>";
                echo "<p><strong>상품명:</strong> " . $row['PRODUCT_NAME'] . "</p>";
                echo "<p><strong>가격: </strong>" . $row['PRODUCT_PRICE'] . "(원)</p>";
                echo "<p><strong>재고:</strong> " . $row['PRODUCT_STOCK'] . "</p>";

                // 수량 선택을 위한 입력 필드 추가
                echo "<label for='quantity_$row[PRODUCT_ID]'>수량 선택(개)</label>";
                echo "<input type='number' class='quantity' id='quantity_$row[PRODUCT_ID]' name='quantity' min='1' value='1'>";

                // 장바구니 버튼 및 이벤트 핸들러 추가
                echo "<button id='put-in' onclick='addToCart($row[PRODUCT_ID], \"$row[PRODUCT_NAME]\", $row[PRODUCT_PRICE])'>장바구니 담기</button>";

                echo "</div>";
            }

            // 장바구니 표시
            echo "<div class='cart-container'>";
            echo "<h2><i class='fa fa-shopping-cart' aria-hidden='true'></i> 장바구니</h2>";
            echo "<div class='cart' id='cart'></div>
            </div></div>";

            oci_free_statement($stmt);
        } else {
            echo "잘못된 매개변수입니다.";
        }

        oci_close($conn);
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
                    cartItem.className = 'cart-item styled-cart-item';

                    var totalItemPrice = cart[i].price * cart[i].quantity;

                    cartItem.innerHTML = `
                        <div class="item-info">상품명: ${cart[i].name}</div>
                        <div class="item-price">가격: ${cart[i].price}(원)</div>
                        <div class="item-quantity">
                            <button class="num-btn" onclick="updateCartItem(${cart[i].id}, ${i}, 1)"> + </button>
                            ${cart[i].quantity}
                            <button class="num-btn" onclick="updateCartItem(${cart[i].id}, ${i}, -1)"> - </button>
                        </div>
                        <div class="item-total">총 ${totalItemPrice}(원)</div>
                        <div class="item-buttons">
                            <button class="delete-btn" onclick="removeFromCart(${i})">삭제</button>
                        </div>
                    `;

                    cartItem.classList.add('styled-cart-item');
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

            function backToLogin() {
                var storeId = <?php echo $store_id; ?>;
                window.location.href = "kiosk_login.php?store_id=" + storeId;
            }

            function checkout() {
                // 장바구니에 상품이 담겨있는지 확인
                if (cart.length === 0) {
                    alert('장바구니가 비어있습니다. 상품을 담아주세요.');
                    return;
                }

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
                                }
                            } else if (xhr.status === 400) {
                                // 재고가 부족한 경우의 처리
                                var productName = cart[i].name; // 해당 상품의 이름 가져오기
                                alert("'" + productName + "'" + '의 재고를 확인해주세요.');
                                insufficientStock = true;
                            } else {
                                console.error('상품 재고 조회 실패');
                                insufficientStock = true;
                            }
                        }
                    };

                    xhr.open('GET', 'check_product_stock.php?product_id=' + productId + '&quantity=' + quantity, false);
                    xhr.send();

                    // 재고 부족한 상품이 있다면 결제를 중단
                    if (insufficientStock) {
                        return;
                    }
                }

                for (var i = 0; i < cart.length; i++) {
                    var productId = cart[i].id;
                    var quantity = cart[i].quantity;

                    // 상품의 재고를 감소시키는 쿼리 실행
                    var xhrUpdate = new XMLHttpRequest();
                    xhrUpdate.onreadystatechange = function () {
                        if (xhrUpdate.readyState === XMLHttpRequest.DONE) {
                            if (xhrUpdate.status !== 200) {
                                console.error('상품 결제 실패');
                            }
                        }
                    };

                    xhrUpdate.open('GET', 'update_product_stock.php?product_id=' + productId + '&quantity=' + quantity, false);
                    xhrUpdate.send();
                }

                // JSON 형식으로 전송할 데이터 생성
                var orderDetailData = [];
                var totalReceiptPrice = 0;

                for (var i = 0; i < cart.length; i++) {
                    var productData = {
                        product_id: cart[i].id,
                        product_count: cart[i].quantity,
                        intermediate_price: cart[i].price * cart[i].quantity,
                    };

                    orderDetailData.push(productData);
                    totalReceiptPrice += cart[i].price * cart[i].quantity;
                }

                var xhrReceiptNum = new XMLHttpRequest();
                xhrReceiptNum.open('GET', 'get_receipt_count.php', false);
                xhrReceiptNum.send();
                var receiptCount = parseInt(xhrReceiptNum.responseText) + 1;

                var receiptData = {
                    receipt_price: totalReceiptPrice,
                    receipt_num: receiptCount,
                    payment_method: 'Credit Card',
                    store_id: <?php echo $store_id; ?>,
                    customer_id: <?php echo $customer_id; ?>,
                };

                // RECEIPT 테이블에 데이터 전송
                var xhrReceipt = new XMLHttpRequest();
                xhrReceipt.open('POST', 'update_receipt.php', false);
                xhrReceipt.setRequestHeader('Content-Type', 'application/json');
                xhrReceipt.send(JSON.stringify(receiptData));

                // ORDER_DETAIL 테이블에 데이터 전송
                var xhrOrderDetail = new XMLHttpRequest();
                xhrOrderDetail.open('POST', 'update_order_detail.php', false);
                xhrOrderDetail.setRequestHeader('Content-Type', 'application/json');
                xhrOrderDetail.send(JSON.stringify({ receipt_id: xhrReceipt.responseText, products: orderDetailData }));

                // 결제 완료 후 고객 포인트 업데이트
                var xhrUpdatePoints = new XMLHttpRequest();
                xhrUpdatePoints.onreadystatechange = function () {
                    if (xhrUpdatePoints.readyState === XMLHttpRequest.DONE) {
                        if (xhrUpdatePoints.status === 200) {
                            // 성공 처리, 예: 성공 메시지 표시
                            console.log(xhrUpdatePoints.responseText);
                        } else {
                            // 오류 처리, 예: 오류 메시지 표시
                            console.error('고객 포인트 업데이트 오류');
                        }
                    }
                };

                xhrUpdatePoints.open('POST', 'update_customer_point.php', true);
                xhrUpdatePoints.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhrUpdatePoints.send('customer_id=<?php echo $customer_id; ?>&total_receipt_price=' + totalReceiptPrice);

                // 결제 후 장바구니 비우기
                cart = [];
                updateCart();

                // 초기화면으로 이동
                alert('결제가 완료되었습니다.');
                backToLogin();
            }
        </script>
    </div>
</body>
<footer>
    <!-- 결제 버튼 및 돌아가기 버튼 -->
    <div class='checkout-button-container' id='checkout-button-container'>
        <button id='back-btn' onclick='backToLogin()'><i class='fa fa-rotate-left' aria-hidden='true'></i> 돌아가기</button>
        <button id='checkout-btn' onclick='checkout()'><i class='fa fa-credit-card-alt' aria-hidden='true'></i> 결제하기</button>
    </div>
</footer>
</html>