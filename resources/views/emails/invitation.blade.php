<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Email</title>
</head>
<body>
<h2>Join Invitation</h2>
<p>Hello {{ $name }}</p>
<p>You have been invited to register to our crm as employee!</p>
<p>please enter the following link and save your information</p>
<p>Link: <a href="{{ $register_link }}"> {{ $register_link }}</a></p>
</body>
</html>
