@extends('layouts.app')

@section('content')
<div class="container">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
        Create post
    </button>
    
    {{-- Create Post Modal  --}}
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Create new post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title">
                        </div>
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" id="body" rows="3"></textarea>
                        </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="button-submit">Create post</button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Posts list --}}
        <div class="row justify-content-center flex-column align-items-center" id="row">
        </div>
        
        {{-- Pagination --}}
        <div class="row justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    @endsection
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#button-submit').click(function() {
                createPost();
            });
        });
        
        function fetchDataPagination(page = 1) {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "get",
            url: "http://127.0.0.1:8000/api/posts?page=" + page,
            success: function(response) {
                $data = response.data;
                $.each($data, function(key, post) {
                    const title = post.title;
                    const body = post.body;
                    const PostId = post.id;
                    var card =
                        "<div class='card m-1 card-" +
                        PostId +
                        " 'style=width:25rem;>" +
                        "<div class='card-body'>" +
                        "<label for='title'>Title: </label>" +
                        "<h5 class='card-title'>" +
                        title +
                        "</h5>" +
                        "<span for='title'>Body: </span>" + 
                        "<p class='card-text'> " +
                        body +
                        " </p>" +
                        "<div class='d-flex justify-content-end'>" +
                        "<a href='/post-info/" +
                        PostId +
                        "' onclick=\"return confirm('Are you sure you want to continue?')\" class='btn btn-info'>Manage</a>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                    $("#row").append(card);
                });

                // Update pagination
                var pagination = $(".pagination");
                pagination.empty();

                if (response.prev_page_url != null) {
                    pagination.append(
                        "<li class='page-item'><a class='page-link' href='#' onclick='fetchDataPagination(" +
                        (response.current_page - 1) +
                        ")' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>"
                    );
                } else {
                    pagination.append(
                        "<li class='page-item disabled'><a class='page-link' href='#' tabindex='-1' aria-disabled='true'><span aria-hidden='true'>&laquo;</span></a></li>"
                    );
                }

                for (var i = 1; i <= response.last_page; i++) {
                    if (response.current_page == i) {
                        pagination.append(
                            "<li class='page-item active'><a class='page-link' href='#'>" +
                            i +
                            "</a></li>"
                        );
                    } else {
                        pagination.append(
                            "<li class='page-item'><a class='page-link' href='#' onclick='fetchDataPagination(" +
                            i +
                            ")'>" +
                            i +
                            "</a></li>"
                        );
                    }
                }

                if (response.next_page_url != null) {
                    pagination.append(
                        "<li class='page-item'><a class='page-link' href='#' onclick='fetchDataPagination(" +
                        (response.current_page + 1) +
                        ")' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>"
                    );
                } else {
                    pagination.append(
                        "<li class='page-item disabled'><a class='page-link' href='#' tabindex='-1' aria-disabled='true'><span aria-hidden='true'>&raquo;</span></a></li>"
                    );
                }
            },
        });
    }
    fetchDataPagination();

    function createPost() {
        // Отримуємо дані з форми
        var title = $('#title').val();
        var body = $('#body').val();
        var userId = {{ Auth::id() }};

        // Відправляємо AJAX-запит на створення поста
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: 'http://127.0.0.1:8000/api/post/create ',
            data: {
                title: title,
                body: body,
                user_id: userId,
            },
            success: function(response) {
                // Якщо пост успішно створено, закриваємо модальне вікно та очищаємо форму
                $('#modal').modal('hide');
                $('#title').val('');
                $('#body').val('');

                // Оновлюємо список постів
                fetchDataPagination();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Якщо сталася помилка, виводимо повідомлення про помилку
                console.log(errorThrown);
            }
        });
    }
</script>
