<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition 2</title>
    <link rel="stylesheet" type="text/css" href="assets/libraries/customs/styles.css">
</head>

<body>

    <div id="loading" style="display: none;">
        <div class="overlay">
            <div class="spinner"></div>
            <p>Wait...</p>
        </div>
    </div>

    <div id="app">

        <h1>Select App</h1>

        <button onclick="app_upload()">Upload</button>
        <button onclick="app_uji()">Uji</button>

    </div>

    <div id="upload" hidden>

        <h1>Upload Page</h1>

        <button onclick="add()">Add</button>
        <button onclick="cls()">Clear</button>
        <button onclick="save()">Save</button>
        <button onclick="app_upload_back()">Back</button>

        <p>*Semakin banyak gambar dan pose yang bervariasi semakin baik akurasi yang dihasilkan.</p>

        <br>
        <input type="text" name="file_name" id="file_name" placeholder="Face Name" onblur="replace_characters()">
        <br>
        <br>
        <br>
        <input type="file" name="file_upload" id="file_upload">

    </div>

    <div id="uji" hidden>

        <h1>Recognition Page</h1>

        <button onclick="through_webcam()">Webcam</button>
        <button onclick="through_file()">File</button>
        <button onclick="uji_upload_back()">Back</button>

        <p>*Lorem, ipsum dolor sit amet consectetur adipisicing elit. Libero, consectetur!.</p>

        <br>

        <div id="webcam" hidden>
            <select name="select_webcam" id="select_webcam" onchange="change_webcam()" hidden>
                <option value="" selected disabled>Loading...</option>
            </select>

            <br>

            <video width="640" height="480" id="video" style="display: none;" autoplay></video>
        </div>

        <div id="file" hidden>
            <input type="file" name="select_file" id="select_file" onchange="upload_image()">

            <br>
            <br>

            <div id="image_uploaded"></div>
        </div>

    </div>

</body>

<script src="assets/libraries/face-api.js/dist/face-api.min.js"></script>
<script src="assets/libraries/customs/scripts.js"></script>

</html>