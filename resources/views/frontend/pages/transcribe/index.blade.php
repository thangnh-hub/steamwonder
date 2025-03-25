<!DOCTYPE html>
<html>

<head>
    <title>Transcribe Audio</title>
</head>

<body>
    <form action="/transcribe" method="post">
        @csrf
        <input type="file" name="audio">
        <input type="text" name="content">
        <button type="submit">Transcribe</button>
    </form>
</body>

</html>
