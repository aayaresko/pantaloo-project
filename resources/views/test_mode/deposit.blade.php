
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div>
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <h2>Set Deposit</h2>
                <br>
                <form id = "sendDeposit">
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" name = 'email' class="form-control" placeholder="Enter email" required>
                    </div>
                    <div class="form-group">
                        <label>Enter Code</label>
                        <input type="code" name = 'code' class="form-control" placeholder="Enter Code" required>
                    </div>
                    <div class="form-group">
                        <label>Enter Amount</label>
                        <input type="number" name = 'amount' class="form-control" placeholder="Enter Amount" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">SEND</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $("form").on( "submit", function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: '/testMode/sendDeposit',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $(this).serialize(),
            success: function(data)
            {
                if (data.success == true) {
                    alert(data.msg);
                } else {
                    alert(data.msg);
                }
            },
            error: function (data) {
                alert('Problems');
            }
        });
    });
</script>
</body>
</html>

