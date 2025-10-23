<!DOCTYPE html>
<html>
<head>
    <title>Upload to Cloudinary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">Upload Image to Cloudinary</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        <img src="{{ session('image') }}" alt="Uploaded Image" class="img-fluid mt-3" width="300">
    @endif

    <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="image" class="form-label">Select image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

</body>
</html>
