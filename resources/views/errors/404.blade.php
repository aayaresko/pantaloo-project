<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/main.css?v=1">
    <title>Document</title>
</head>
<body>
    <div class="notFoundWrapper">
            <div class="pageWrap">
                <img src="/media/images/404img.png">
                <div class="notFoundPageInfo">
                    <h3>Woops ... !</h3>
                    <p>Sorry, we arent able to find what you where looking for</p>
                    <a href="#" class="btn-back" onclick="window.history.back();return false">Back</a>
                    <a href="https://casinobit.io/" class="btn-back notArrow">home</a>
                </div>
            </div>
            <footer class="footer footer-home">
                <div class="msg-block">
                    <span class="msg">What do you want to play?</span>
                </div>
                <div class="games-listing-block">
                    <ul class="games-listing">
                        @include('footer_links', ['currentLang' => 'en'])
                    </ul>
                </div>
            </footer>
            <div class="mobile-container">
            <section class="welcome-mob">
                <div class="msg-block">
                    <span class="msg">What do you want to play?</span>
                </div>
                <div class="games-listing-block">
                    <ul class="games-listing">
                        @include('footer_links', ['currentLang' => 'en'])
                    </ul>
                </div>
            </section>
            </div>
    </div>
</body>
</html>