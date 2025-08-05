<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  </head>
  <body>
    <!-- Data Table -->
    <div class="container m-5 col-8 mx-auto text-bg-light p-3 rounded">
        <h1 class="text-center mb-5">All Posts</h1>
        <div class="flex justify-between mb-3">

            <a href="/addPost" class="btn btn-primary me-2">Create Post</a>
            <button type="button" id="logout" class="btn btn-danger">Logout</button>

        </div>
        <table id="PostsTable" class="table table-striped table-hover">

            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>View</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>

            </tbody>

        </table>
    </div>
    <!--End Data Table -->

    <!-- View Model -->
    <section>
        <div class="modal fade" id="singlePostView" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Single Post</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </section>
    <!--End View Model -->

    <!-- update Model -->
    <section>
        <div class="modal fade" id="updatePostModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updatePostLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Post</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updatePost">
                        <input type="hidden" id="postId">
                        <div class="mb-3">
                            <label for="updateTitle" class="form-label">Title</label>
                            <input type="text" id="updateTitle" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="updateDescription" class="form-label">Description</label>
                            <input type="text" id="updateDescription" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="updateImage" class="form-label">Image</label>
                            <input type="file" id="updateImage" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="showImage" class="form-label">Current Image</label>
                            <img id="showImage" src="" width="100">
                        </div>
                        <button type="submit" id="update" class="btn btn-primary">Update Post</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </section>
    <!--End update Model -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script>
        // Redirect to login if token is missing
        const token = localStorage.getItem('api_token');
        if (!token) {
            window.location.href = "http://127.0.0.1:8000";
        }

        // Logout
        document.querySelector('#logout').addEventListener('click', function() {
            const token = localStorage.getItem('api_token');
            if (token) {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                  .then(data => {
                    localStorage.removeItem('api_token');
                    window.location.href = "http://127.0.0.1:8000/";
                }).catch(error => console.error(error));
            } else {
                window.location.href = "http://127.0.0.1:8000/";
            }
        });

        // Load Post Data In Table
        function loadData() {
            const token = localStorage.getItem('api_token');
            if (token) {
                fetch('/api/posts', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                }).then(response => response.json())
                  .then(data => {
                    //console.log(data.posts);
                    data.posts.forEach(post => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><img src="/images/${post.image}" width="100"></td>
                            <td>${post.title}</td>
                            <td>${post.description}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-postid="${post.id}" data-bs-target="#singlePostView" >
                                    View
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-postid="${post.id}" data-bs-target="#updatePostModel" >
                                    Update
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="deletePost(${post.id})">
                                    Detele
                                </button>
                            </td>
                        `;
                        document.querySelector('#PostsTable tbody').appendChild(row);
                    })
                  }).catch(error => console.error(error));
            }else{
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="6" class="text-center">Please login to view posts</td>`;
                document.querySelector('#PostsTable tbody').appendChild(row);
            }
        }
        loadData();

        // single post view
        var singlePostView = document.querySelector('#singlePostView');

        if (singlePostView) {
            singlePostView.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const id = button.getAttribute('data-bs-postid');
                //console.log(id);
                const token = localStorage.getItem('api_token');
                fetch(`/api/posts/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    //console.log(data.post);
                    const post = data.post;
                    const modalBody = document.querySelector('#singlePostView .modal-body');
                    modalBody.innerHTML = "";
                    modalBody.innerHTML = `
                        <h4>${post.title || ''}</h4>
                        <br>
                        <p>${post.description || ''}</p>
                        <br>
                        <img src="/images/${post.image || ''}" width="100">
                    `;
                });

            })
        }

        // edit post view
        var updatePostModel = document.querySelector('#updatePostModel');

        if(updatePostModel){
             updatePostModel.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const id = button.getAttribute('data-bs-postid');
                //console.log(id);
                const token = localStorage.getItem('api_token');

                fetch(`/api/posts/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    console.log(data.post);
                    const post = data.post;
                    document.querySelector('#postId').value = post.id;
                    document.querySelector('#updateTitle').value = post.title;
                    document.querySelector('#updateDescription').value = post.description;
                    document.querySelector('#showImage').src = `/images/${post.image}`;
                });

            })
        }

        //Update Post
        var updatePost = document.querySelector('#updatePost');

        updatePost.onsubmit = async (e) => {
            e.preventDefault();

            const token = localStorage.getItem('api_token');
            const id = document.querySelector('#postId').value;
            const title = document.querySelector('#updateTitle').value;
            const description = document.querySelector('#updateDescription').value;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('title', title);
            formData.append('description', description);
            if(!document.querySelector('#updateImage').files[0] == ''){
                const image = document.querySelector('#updateImage').files[0];
                formData.append('image', image);
            }

            let response = await fetch(`/api/posts/${id}`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                window.location.href = "http://127.0.0.1:8000/allposts";
            }).catch(error => console.error(error));
        }

        //Delete Post
        function deletePost(id){
            const token = localStorage.getItem('api_token');
            fetch(`/api/posts/${id}`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-HTTP-Method-Override': 'DELETE'
                }
            }).then(response => response.json())
            .then(data => {
                console.log(data);
                window.location.href = "http://127.0.0.1:8000/allposts";
            }).catch(error => console.error(error));
        }
    </script>
  </body>
</html>
