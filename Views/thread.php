<div class="container py-5">
    <div class="mt-4" style="width: 50%; min-width: 400px; margin: 0 auto;">
        <a href="/" style="color: black; text-decoration: none;">
            <i class="fa-solid fa-chevron-left"></i>
            タイムラインに戻る
        </a>
    </div>

    <div id="thread" class="py-3" style="width: 50%; min-width: 400px; margin: 0 auto;">
    </div>

    <div id="more-replies-btn-wrapper" class="mb-4 text-center" style="display: none;">
        <button id="more-replies-btn" type="button" class="btn btn-primary rounded-pill">
            リプライをもっと見る
            <i class="fa-solid fa-chevron-down"></i>
        </button>
    </div>

    <div id="reply-btn-wrapper" class="mb-3 text-center" style="display: none;">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#replyModal">
            <i class="fa-solid fa-reply" aria-hidden="true" style="transform: rotate(180deg);"></i> Reply
        </button>

        <!-- Modal -->
        <div class="modal fade text-start" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replyModalLabel">リプライ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
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
                                <button type="submit" class="btn btn-primary">リプライ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://localhost:8000/js/thread.js"></script>
