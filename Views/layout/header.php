<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/934007e345.js" crossorigin="anonymous"></script>

    <script src="js/common.js"></script>
    <title>Imageboard-Webapp</title>
</head>
<body>
    <!-- nav -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Imageboard-webapp</a>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ceateModal">
                New Thread
            </button>

            <!-- Modal -->
            <div class="modal fade" id="ceateModal" tabindex="-1" aria-labelledby="ceateModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ceateModalLabel">New Thread</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="create-thread-form">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject">
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Image</label>
                                <input type="file" class="form-control" name="file" id="file">
                            </div>

                            <div class="mt-5 text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create Thread</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- alert -->
    <div id="alert-danger" class="alert alert-danger" role="alert" style="display: none;">
    </div>
    <div id="alert-success" class="alert alert-success" role="alert" style="display: none;">
    </div>
