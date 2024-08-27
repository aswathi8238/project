<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forgotpassword</title>
    <link rel="stylesheet" href="./bst/css/bootstrap.min.css"/>
</head>
<body>
<div class="container">
        <div class="row">
            <div class="col-12">
                    <h3 class="text-center">forgot password</h3>
            </div>
        </div>
    </div>
    <div class="container mt-2">
        <div class="row">
            <div class="col-4">
            <form method="post" action="router.php?opt=5">
                     <h4 class="text-center">foregot password</h4>
                   <div class="form-group mt-2">
                    <label>Email</label>
                    <input type="email" name="txtEmail" class="form-control" placeholder="Email" required>
                   </div>
                   <div class="form-group mt-2">
                    <label>Password</label>
                    <input type="password" name="txtPass" class="form-control" placeholder="Password" required>
                   </div>
                   <div class="form-group mt-2">
                    <label> conform Password</label>
                    <input type="password" name="txtcPass" class="form-control" placeholder="conform password" required>
                   </div>
                   <input type="submit" class="btn mt-2 btn-dark w-100" value="submit">
            </div>
        </div>
    </div>

</body>
</html>