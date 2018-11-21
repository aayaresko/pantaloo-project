<html>
<body>
{{ dump($link) }}
<iframe src="{{ $link->response }}" width="600" height="600" align="left">
    Ваш браузер не поддерживает плавающие фреймы!
</iframe>
</body>
</html>