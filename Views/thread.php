<div class="container">
    <div class="mt-3">
        <a href="/" style="color: black; text-decoration: none;">
            <i class="fa-solid fa-chevron-left"></i>
            タイムラインに戻る
        </a>
    </div>

    <div id="thread" class="pt-4 mb-3">
    </div>

    <div id="more-replies-btn-wrapper" class="mb-4 text-center">
        <button id="more-replies-btn" type="button" class="btn btn-link">
            リプライをもっと見る
        </button>
    </div>

    <div class="mb-3 text-center">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#replyModal">
            Reply
        </button>

        <!-- Modal -->
        <div class="modal fade text-start" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replyModalLabel">Reply</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Image</label>
                                <input type="file" class="form-control" id="file">
                            </div>

                            <div class="mt-5 text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Reply</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://localhost:8000/js/thread.js"></script>
