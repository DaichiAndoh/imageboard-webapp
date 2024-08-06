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

    <script src="http://localhost:8000/js/common.js"></script>
    <title>Imageboard-Webapp</title>
</head>
<body>
    <!-- nav -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">Imageboard-webapp</a>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ceateModal">
                <i class="fa-solid fa-plus"></i> 新規スレッド
            </button>
        </div>
    </nav>

    <!-- Modal -->
    <div class="modal fade" id="ceateModal" tabindex="-1" aria-labelledby="ceateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ceateModalLabel">新規スレッド</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="create-thread-form">
                    <div class="mb-3">
                        <label for="subject" class="form-label">タイトル</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                        <div id="subject-error-msg" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">コンテンツ</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        <div id="content-error-msg" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">イメージ</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".jpg, .jpeg, .png, .gif">
                        <div id="file-error-msg" class="invalid-feedback"></div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        <button type="submit" class="btn btn-primary">作成</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>

    <!-- alert -->
    <div id="alert-danger" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none; margin: 56px 0 0;">
        <span id="alert-danger-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none; margin: 56px 0 0;">
        <span id="alert-success-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
