<!DOCTYPE html>
<html>

<head>
    <title>Google Drive</title>
</head>

<body>
    <form action="{{ route('google_drive.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <input type="text" name="content">
        <button type="submit">Upload</button>
    </form>
</body>

</html>
