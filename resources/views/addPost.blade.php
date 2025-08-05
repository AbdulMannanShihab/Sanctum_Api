<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  </head>
  <body>
    <div class="container m-5 col-8 mx-auto text-bg-light p-3 rounded">
        <h1 class="text-center mb-5">Add Post</h1>
        <form id="addPost" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <label for="inputTitle" class="col-sm-2 col-form-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" id="title" class="form-control" id="inputTitle">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputDescription" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                    <input type="text" id="description" class="form-control" id="inputDescription">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputImage" class="col-sm-2 col-form-label">Image</label>
                <div class="col-sm-10">
                    <input type="file" id="image" class="form-control" id="inputImage">
                </div>
            </div>
            <button type="submit" id="add" class="btn btn-primary">Add Post</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script>
        // Redirect to login if token is missing
        const token = localStorage.getItem('api_token');
        if (!token) {
            window.location.href = "http://127.0.0.1:8000";
        }
        var addPost = document.querySelector('#addPost');

        addPost.onsubmit = async (e) => {
            e.preventDefault();

            const token = localStorage.getItem('api_token');
            const title = document.querySelector('#title').value;
            const description = document.querySelector('#description').value;
            const image = document.querySelector('#image').files[0];

            const formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);
            formData.append('image', image);

            let response = await fetch('/api/posts', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                //console.log(data);
                window.location.href = "http://127.0.0.1:8000/allposts";
            }).catch(error => console.error(error));
        }
    </script>
  </body>
</html>
