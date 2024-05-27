@extends('layouts.admin')
@section('content')
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{__('admin.Title')}}</th>
            <th scope="col">{{__('admin.Body')}}</th>
            <th scope="col">{{__('admin.User')}}</th>
            <th scope="col">{{__('admin.Comment on page')}}</th>
            <th scope="col">{{__('admin.Created_at')}}</th>
            <th scope="col">{{__('admin.Updated_at')}}</th>
            <th scope="col">{{__('admin.Status')}}</th>
            <th scope="col">{{__('admin.Actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            @php
                $user = $comment->creator()->first();
                $post =  $comment->commentable()->first();

                  if (!$post) {
                    continue;
                }

            @endphp
            <tr>
                <th scope="row">{{$comment->id}} <i class="bi bi-airplane-fill"></i></th>
                <td>{{$comment->title}}</td>
                <td>{!! \Illuminate\Support\Str::markdown($comment->body) !!}</td>
                <td>
                    <a href="{{route('admin.user.show', $user->id)}}">
                        {{$user->name}}
                    </a>
                </td>
                <td>
                    <a href="{{$post->currentPostUrl()}}#commentBox-{{$comment->id}}" target="_blank">
                        {{$post->title}}
                    </a>
                </td>
                <td>{{$comment->created_at}}</td>
                <td>{{$comment->updated_at}}</td>
                <td>
                </td>
                <td>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-light commentStatus" data-comment-id="{{$comment->id}}">
                            <i style="color: #198754; font-size: 22px"
                               class="fas @if($comment->active)fa-toggle-on @else fa-toggle-off @endif"></i>
                        </button>
                        &nbsp;
                        <a href="{{route('admin.comment.edit', $comment->id)}}">
                            <button type="button" class="btn btn-light">
                                <i class="fas fa-wrench"></i>
                            </button>
                        </a>
                        &nbsp;
                        <form method="post" action="{{route('admin.comment.destroy', $comment->id)}}" id="formDelete">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="container">
        {{ $comments->links('pagination::bootstrap-5') }}
    </div>
@endsection
@push('bottom-scripts')
    <script>
        let commentStatus = document.querySelectorAll(".commentStatus");

        for (let item of commentStatus) {
            item.addEventListener('click', async function (event) {
                event.preventDefault();
                let elI = this.querySelector('i.fas');
                let commentID = this.dataset.commentId;
                this.disabled = true;

                let data = {
                    id: commentID,
                    'status': !elI.classList.contains('fa-toggle-on')
                };
                await fetchPost("{{route('admin.comment.setStatus')}}", data);
                elI.classList.toggle("fa-toggle-on");
                elI.classList.toggle("fa-toggle-off");
                this.disabled = false;
            });
        }

        let fetchPost = async function (url, data) {

            let result = await fetch(url, {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": document.querySelector('input[name="_token"]').value
                },
                method: "post",
                credentials: "same-origin",
                body: JSON.stringify(data)
            });

            return await result.json();
        }

    </script>
@endpush
