# Project-MatchuriAll
Implementation of website that manages unmanned stores and dynamically creates kiosks

---

## 프로젝트 개요

- 테이블 간 일대일, 일대다, 다대다 관계가 모두 포함된 데이터베이스를 사용하는 웹사이트
- 관리 중인 무인 가게의 매출, 상품 재고 등을 실시간으로 확인하고 관리할 수 있는 웹사이트
- 해당 가게에 대한 동적으로 생성되고 재고가 실시간으로 변경되는 키오스크가 포함된 웹사이트
- 해당 가게에 대한 회원들의 구매 내역과 포인트를 한눈에 볼 수 있는 웹사이트

---

## 프로젝트 팀원

| 권덕재(BE) | 박민현(FE) | 이정은(FE) | 이채영(BE) |
| :--: | :--: | :--: | :--: |
| ![djdj](https://github.com/juintination/Project-MatchuriAll/assets/89019601/076750cd-4bfb-444c-a94d-1042877f135a) | ![mhmh](https://github.com/juintination/Project-MatchuriAll/assets/89019601/8de055e1-726a-4197-ab9f-8e0d018672ca) | ![jeje](https://github.com/juintination/Project-MatchuriAll/assets/89019601/ca24f19e-2eec-44b0-85ca-04579675fa87) | ![lcy](https://github.com/juintination/Project-MatchuriAll/assets/89019601/422bc440-5f23-44b6-873f-a0d1375acbd9) |

---

## 목차

- [사용 방법](#사용-방법)
- [E-R 다이어그램](#E-R-다이어그램Peter-Chen-표기법)
- [실행 화면과 기능 설명](#실행-화면과-기능-설명)

---

## 사용 방법

- `http://203.249.87.58/class_502/502_S3/demo` 에 접속하여 해당 프로젝트의 내용을 확인할 수 있으나 데이터베이스및실습 수업 시간에 프로젝트를 위해 제공받은 리눅스 서버와 오라클 DB를 사용하였기 때문에 시간이 지나면 사용이 불가능할 수 있음
  - 다른 계정으로 오라클 DB에 접속하기 위해 `db_info.php` 의 내용을 사용해야 하며 MySQL을 비롯한 다른 데이터베이스를 사용하기 위해 php 파일을 수정해야 함
  - 처음 실행하게 되면 `실행할 환경/setup.php` 를 실행해야 모든 테이블 및 스키마가 자동으로 생성되며 이후에 관리자 회원가입을 통해 관리자 및 가게를 생성한 이후에 정상적으로 사용이 가능함

---

## E-R 다이어그램(Peter Chen 표기법)

<img width="844" alt="KakaoTalk_20231218_162849193" src="https://github.com/juintination/Project-MatchuriAll/assets/89019601/7e0d4275-1c42-47f5-9dbd-16b44a617b71">

## E-R 다이어그램(IE 표기법)

<img width="1208" alt="KakaoTalk_20231208_193419300_02" src="https://github.com/juintination/Project-MatchuriAll/assets/89019601/dfc47429-f23d-46af-a8e9-4c03aef3c771">

---

## 실행 화면과 기능 설명

### Overall Flow

![overall_flow](https://github.com/juintination/Project-MatchuriAll/assets/89019601/b0dfb093-7710-4515-a27c-ba24ad0a7101)

### Index

![index](https://github.com/juintination/Project-MatchuriAll/assets/89019601/53504229-2abd-4c84-a85e-490a3b36812b)

우측 상단의 버튼을 통해 회원가입 또는 로그인을 진행할 수 있으며 우측 하단의 아이콘을 통해 관련 링크로 이동할 수 있습니다.

### 회원 가입 페이지

![signup](https://github.com/juintination/Project-MatchuriAll/assets/89019601/6e8d9e8a-c61d-4959-9dbb-bf6469d567a3)

회원 가입 버튼을 누르면 관리자인지 일반 회원인지 확인합니다.

![admin_signup](https://github.com/juintination/Project-MatchuriAll/assets/89019601/4c181520-1ec8-4be7-9747-9440bc54b7b1)

관리자를 선택하게 되면 관리자 정보와 가게 정보를 필수로 입력하게 됩니다. 이때 관리자 이메일은 중복될 수 없습니다.

![user_signup](https://github.com/juintination/Project-MatchuriAll/assets/89019601/7cfd8735-8364-4f89-bb1f-f6229c3c2e8f)

일반 회원을 선택하게 되면 가입할 회원 정보를 입력하게 됩니다. 이때 회원의 이메일뿐만 아니라 추후에 키오스크에 쉽게 로그인하기 위해 핸드폰 번호 또한 중복될 수 없습니다.

### 로그인 페이지

![signin](https://github.com/juintination/Project-MatchuriAll/assets/89019601/d99ea5b3-a2ab-43f9-aa01-55dd4f60dc5b)

로그인 버튼을 누르면 관리자 혹은 일반 회원으로 어떤 가게에 로그인할지 선택합니다.

### 관리자 페이지

![admin_page](https://github.com/juintination/Project-MatchuriAll/assets/89019601/0629dcc8-83fd-4693-af48-3228fa04d8c4)

관리자 페이지입니다. 해당 가게 및 관리자 정보를 확인할 수 있으며 상품들의 정보를 비롯한 재고를 관리할 수 있습니다.
또한 하루, 일주일, 이번 달의 매출 정보를 확인할 수 있으며 바로 아래에서 새로운 상품을 추가할 수 있습니다.

![edit_product](https://github.com/juintination/Project-MatchuriAll/assets/89019601/4f519984-7e5d-40e9-b103-cc78bb4ebd30)

상품 정보 수정 페이지입니다. 상품의 이름, 가격, 재고를 직접 수정할 수 있습니다.

![get_profit](https://github.com/juintination/Project-MatchuriAll/assets/89019601/56ba4f17-fb78-4b9b-8437-dee595c27c91)

매출 상세 정보 페이지입니다. 누가, 언제, 어떤 결제 수단으로, 어떤 상품들을 구매했는지 한 눈에 확인할 수 있습니다.

### 일반 회원 페이지

![user_page](https://github.com/juintination/Project-MatchuriAll/assets/89019601/920f5902-5826-4719-b82d-3e309cfac754)

일반 회원 페이지입니다. 해당 회원의 정보를 확인할 수 있으며 구매 내역과 그로 인한 포인트 또한 확인할 수 있습니다.

![get_order_detail](https://github.com/juintination/Project-MatchuriAll/assets/89019601/9cbfa40f-0e69-4f25-8007-4f6a5efd43e5)

어떤 구매 내역에 대한 상세한 정보 또한 확인할 수 있습니다.

### 프로필 편집

![edit_profile](https://github.com/juintination/Project-MatchuriAll/assets/89019601/333fa3de-9715-47e2-9774-3d6c6f5c1a2b)

프로필 편집 버튼을 누르면 위와 같이 프로필 정보를 변경할 수 있습니다.
지금 보이는 귀여운 `매추리` 이미지가 기본 이미지이며 프로필 이미지의 크기는 2MB로 제한됩니다.

### 키오스크

![kiosk_login](https://github.com/juintination/Project-MatchuriAll/assets/89019601/bca3a2e1-5263-4ac4-aad7-12d4142a2f23)

예시로 사용된 DB Cafe의 키오스크 로그인 화면입니다. 해당 가게에 등록된 회원의 핸드폰 번호를 입력하여 로그인할 수 있습니다.

![kiosk](https://github.com/juintination/Project-MatchuriAll/assets/89019601/da8de460-6559-459e-aa1e-8a6014a1b08f)

마찬가지로 예시로 사용된 DB Cafe의 키오스크 화면입니다. 해당 가게에 있는 상품을 장바구니에 담고 한꺼번에 결제할 수 있습니다.
현재는 결제 기능을 따로 구현하지 않은 상태이며 결제 수단은 자동으로 `Credit Card` 로 통일됩니다.

---

## 기타 사항

- 지도교수 : 김영철 교수님
- 사용언어 및 개발환경 : <img src="https://img.shields.io/badge/Visual Studio Code-007ACC?style=for-the-badge&logo=Visual Studio Code&logoColor=white"> <img src="https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=Apache&logoColor=white"> <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=PHP&logoColor=white"> <img src ="https://img.shields.io/badge/HTML5-E34F26.svg?&style=for-the-badge&logo=HTML5&logoColor=white"/> <img src ="https://img.shields.io/badge/CSS3-1572B6.svg?&style=for-the-badge&logo=CSS3&logoColor=white"/> <img src ="https://img.shields.io/badge/JavaScriipt-F7DF1E.svg?&style=for-the-badge&logo=JavaScript&logoColor=black"/> <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=MySQL&logoColor=white"> <img src="https://img.shields.io/badge/Oracle-F80000?style=for-the-badge&logo=Oracle&logoColor=white">

## 참고

- [명령어 설명 규칙](https://technet.tmaxsoft.com/upload/download/online/jeus/pver-20170202-000001/reference-book/jeusadmin-conventions.html)
- [사용한 템플릿 사이트](http://www.mashup-template.com/)
