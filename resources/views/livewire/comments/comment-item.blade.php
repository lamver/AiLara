<div>
    <div class="card mb-3" id="commentBox-{{$comment->id}}">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <h5 class="card-title">{{ $comment->title }}</h5>
                </div>
                {{ $editCommentId }} {{ Auth::check() }}
                @if(!$editCommentId && Auth::check())
                    <div class="btn-group col-2">
                        @if((int)Auth::user()->id === (int)$comment->creator_id)
                            <button type="button" class="btn btn-primary btn-sm"
                                    wire:click.prevent="startEditingComment( {{ $comment->id }}, {{$comment}} )">
                                {{__('Edit')}}
                            </button>
                            <button type="button" class="btn btn-danger btn-sm"
                                    wire:confirm="{{__('delete')}} ?"
                                    wire:click.prevent="deleteComment({{$comment->id}})"> {{__('Delete')}}
                            </button>
                        @else
                            @if(!$replyCommentId)
                                <button type="button" class="btn btn-info btn-sm"
                                        wire:click.prevent="startReply({{ $comment->id }})">
                                    {{__('Reply')}}
                                </button>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col">
                    <p class="card-text">{!! Str::markdown($comment->body) !!}</p>
                </div>
            </div>

            @if((int)$editCommentId === (int)$comment->id)

                <div class="flex-1 mb-3">
                    <textarea
                        class="form-control mb-3"
                        name="editCommentValue"
                        wire:model.lazy="editCommentValue"
                        rows="4"
                    > {{$comment->body}} </textarea>
                    <button class="btn btn-success"
                            wire:click="updateComment({{ $comment->id }})">{{__('Save')}}</button>
                    <button class="btn btn-danger" wire:click="cancelEditedComment">{{__('Cancel')}}</button>
                    @error('editCommentValue')
                    <x-input-error class="mt-2 text-sm" :messages="$message"/>
                    @enderror
                </div>
            @endif

            @if((int)$replyCommentId === (int)$comment->id)
                <div class="flex-1 mb-3 ml-3 mt-3">
                  <textarea
                      class="form-control mb-3"
                      name="comment"
                      wire:model.lazy="replyCommentBody"></textarea>
                    <button class="btn btn-success"
                            wire:click="replyComment({{ $comment->id }})">{{__('Reply')}}</button>
                    <button wire:click.prevent="cancelReply" class="btn btn-danger">{{__('Cancel')}}</button>
                    @error('replyCommentBody')
                    <x-input-error class="mt-2 text-sm" :messages="$message"/>
                    @enderror
                </div>
            @endif

            <div class="row mt-3">
                <div class="col-md-4">
                    <img style="width: 110px; height: 120px; object-fit: cover; object-position: center;" src="{{!empty($comment->creator()->first()->avatar) ? $comment->creator()->first()->avatar : asset('images/avatar/no_avatar.png') }}" class="rounded float-start" alt="...">
                </div>
                <div class="col-md-12 mt-1">
                    {{$comment->formatCreatedAt()}}
                </div>
            </div>

        </div>
    </div>

    @if($comment->children)
        @foreach($comment->children as $children)
            <div style="margin-left: 35px">
                <livewire:Comments.comment-item :comment="$children" key="'child_'.{{ now() }} .'-'.{{ $children->id }}"/>
            </div>
        @endforeach
    @endif

</div>
