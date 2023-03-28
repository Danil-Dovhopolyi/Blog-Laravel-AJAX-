function fetchDataPagination(page = 1) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "get",
        url: "http://127.0.0.1:8000/api/posts?page=" + page,
        success: function (response) {
            $data = response.data;
            console.log($data);
            $.each($data, function (key, post) {
                const title = post.title;
                const body = post.body;
                const PostId = post.id;
                var card =
                    "<div class='card m-1 card-" +
                    PostId +
                    " 'style=width:25rem;>" +
                    "<div class='card-body'>" +
                    "<h5 class='card-title'>" +
                    title +
                    "</h5>" +
                    "<h6 class='card-subtitle mb-2 text-muted'>Card subtitle</h6>" +
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
                    "<li class='page-item'><a class='page-link' href='#' onclick='fetchData(" +
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
                        "<li class='page-item'><a class='page-link' href='#' onclick='fetchData(" +
                            i +
                            ")'>" +
                            i +
                            "</a></li>"
                    );
                }
            }

            if (response.next_page_url != null) {
                pagination.append(
                    "<li class='page-item'><a class='page-link' href='#' onclick='fetchData(" +
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
