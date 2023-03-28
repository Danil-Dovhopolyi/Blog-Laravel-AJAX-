@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" id="row">
            <div class="card p-5" style='width:25rem;'>
                <form class="d-flex flex-column" id="update-post-form">
                    @csrf
                    @method('PUT')
                    <label for="title">Title:</label>
                    <input type="text" name="title" value="{{ $post->title }}">
                    <label for="body">Body:</label>
                    <input type="text" name="body" value="{{ $post->body }}">
                    <div class="d-flex justify-content-between mt-2">
                        @if (auth()->user()->can('update', $post))
                            <button type='button' class='btn btn-warning update' id="updatePost" style='width:5rem;'
                                disabled>Update</button>
                        @endif
                        @if (auth()->user()->can('update', $post))
                            <button type='button' class='btn btn-danger'
                                style='width:5rem;'data-post-id={{ $post->id }} id='delete'>Delete</button>
                        @endif
                        <a href="{{ route('home') }}" class="btn btn-primary" style='width:5rem;'>Back</a>
                    </div>
                </form>
            </div>
        </div>
    @endsection
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#row').on('click', '#delete', function() {
                var postId = $(this).data('post-id');
                var postElement = $(this).closest('.card-' + postId);
                deletePost(postId, postElement);
            });
            $('#row').on('click', '#updatePost', function() {
                updatePost();
            });
            var initialTitle = $('input[name="title"]').val();
            var initialBody = $('input[name="body"]').val();

            // перевірка змін у полях введення
            $('input[name="title"], input[name="body"]').on('input', function() {
                var currentTitle = $('input[name="title"]').val();
                var currentBody = $('input[name="body"]').val();

                if (currentTitle == initialTitle && currentBody == initialBody) {
                    $('button.update').attr('disabled', 'disabled');
                } else {
                    $('button.update').removeAttr('disabled');
                }
            });
        });

        function updatePost() {
            event.preventDefault();
            var findPostId = window.location.pathname.split('/')[2];
            // Отримуємо дані з форми
            var postData = {
                'title': $('input[name=title]').val(),
                'body': $('input[name=body]').val()
            };
            if (confirm("Are you sure you want to update this post?")) {
                // Відправляємо запит на сервер для оновлення поста
                $.ajax({
                    url: 'http://127.0.0.1:8000/api/post/' + findPostId,
                    type: 'PUT',
                    data: postData,
                    success: function(response) {
                        window.location.href = "{{ route('home') }}";
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        }


        function deletePost(postId, postElement) {
            if (confirm("Are you sure you want to delete this post?")) {
                // код для видалення допису через AJAX
                $.ajax({
                    url: "http://127.0.0.1:8000/api/post/" + postId,
                    type: 'DELETE',
                    success: function(result) {
                        // видалення елементу з DOM
                        $('.card-' + postId).fadeOut(300, function() {
                            postElement.remove();
                        });
                        window.location.href = "{{ route('home') }}";
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        }
    </script>
