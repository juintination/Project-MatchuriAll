<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width,initial-scale=1" name="viewport">
  <meta content="description" name="description">
  <meta name="google" content="notranslate">
  <meta content="Mashup templates have been developped by Orson.io team" name="author">

  <!-- Disable tap highlight on IE -->
  <meta name="msapplication-tap-highlight" content="no">
  <link rel="apple-touch-icon" sizes="180x180" href="css/assets/apple-icon-180x180.png">
  <link href="css/assets/favicon.ico" rel="icon">
  <link rel="stylesheet" href="css/main.550dcf66.css">
  
  <!-- Link to Google Fonts (Nanum Gothic) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@400;700&display=swap" />
  
  <style>
    /* Apply Nanum Gothic font family to relevant elements */
    body, p, h3, h4 {
      font-family: 'Nanum Gothic', sans-serif;
    }
  </style>

  <title>무인 가게 관리 웹사이트</title>
</head>
<body>
  <!-- Content of header -->
  <header>
    <nav class="navbar navbar-default active">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#" title="">
            <span><img src="css/assets/images/matchuriall.png" alt="매추리알" width="30" height="30"></span>
            <span>MatchuriAll</span>
          </a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="signin/signin.php" title="">로그인</a></li>
            <li><a href="signup/signup.php" title="">회원가입</a></li>
          </ul>
        </div> 
      </div>
    </nav>
  </header>

  <!-- Content -->
  <div class="hero-full-container background-image-container white-text-container">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <h1>MatchuriAll</h1>
          <p>WELCOME TO MatchuriAll WEBSITE.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="section-container">
    <div class="container text-center">
      <div class="row section-container-spacer">
        <div class="col-xs-12 col-md-12">
          <h2>About Us</h2>
          <p>매추리알은 실시간으로 무인 가게의 매출 데이터를 추적하고 재고를 관리합니다.</p> 
          <p>관리자는 언제 어디서나 실시간으로 매출과 재고 현황을 확인할 수 있으며</p>
          <p>해당 가게의 고객들은 실시간으로 구매 내역과 포인트를 확인할 수 있습니다.</p>
        </div>  
      </div>
      <div class="row">
        
        <div class="col-xs-12 col-md-3">
          <img src="css/assets/images/djdj.jpg" alt="" class="reveal img-responsive reveal-content image-center">
          <h3>권덕재</h3>
          <h4>B989003</h4>
        </div>
       
        <div class="col-xs-12 col-md-3">
          <img src="css/assets/images/mhmh.png" alt="" class="reveal img-responsive reveal-content image-center">
          <h3>박민현</h3>
          <h4>B989019</h4>
        </div>

        <div class="col-xs-12 col-md-3">
          <img src="css/assets/images/jeje.jpg" alt="" class="reveal img-responsive reveal-content image-center">
          <h3>이정은</h3>
          <h4>C089048</h4>
        </div>
        
        <div class="col-xs-12 col-md-3">
          <img src="css/assets/images/lcy.png" alt="" width ="215.5" height="206" class="reveal img-responsive reveal-content image-center">
          <h3>이채영</h3>
          <h4>C189053</h4>
        </div>
      </div>
    </div>
  </div>
<script>
  document.addEventListener("DOMContentLoaded", function (event) {
    navbarFixedTopAnimation();
  });
</script>

<footer class="footer-container white-text-container">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <h3>MatchuriAll</h3>
        <div class="row">
          <div class="col-xs-12 col-sm-7">
            <p>
              <small>
                <a href="http://www.mashup-template.com/" target="_bland" target="_bland" title="Create website with free html template">Mashup Template</a>
                <a href="https://www.unsplash.com/" target="_bland" target="_bland" title="Beautiful Free Images">Unsplash</a>
              </small>
            </p>
          </div>
          <div class="col-xs-12 col-sm-5">
            <p class="text-right">
              <a href="https://www.instagram.com/hongik_sw_official/" target="_bland" class="social-round-icon white-round-icon fa-icon" title="">
                <i class="fa fa-instagram" aria-hidden="true"></i>
              </a>
              <a href="https://www.facebook.com/DSChongik/" target="_bland" class="social-round-icon white-round-icon fa-icon" title="">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a href="https://github.com/juintination/Project-MatchuriAll/" target="_bland" class="social-round-icon white-round-icon fa-icon" title="">
                <i class="fa fa-github" aria-hidden="true"></i>
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

<script>
  document.addEventListener("DOMContentLoaded", function (event) {
    navActivePage();
    scrollRevelation('.reveal');
  });
</script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID 

<script>
  (function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
      (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date(); a = s.createElement(o),
      m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
  })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
  ga('create', 'UA-XXXXX-X', 'auto');
  ga('send', 'pageview');
</script> -->

<script type="text/javascript" src="js/main.0cf8b554.js"></script>
</body>
</html>