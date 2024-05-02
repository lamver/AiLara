@extends('layouts.admin')
<style>
    [data-bs-toggle="modal" ] {
        cursor: pointer;
    }
    /**
    Генератор каринок
 */

    #imgOnDesResult {
        padding-top: 15px;
    }

    #innerResult img {
        width: -webkit-fill-available;
        max-width: 260px;
        cursor: pointer;
    }
    #innerResult label {
        position: relative;
        display: inline-block;
    }
    #innerResult label:before {
        content: '';
        position: absolute;
        border-radius: 50%;
        border: 3px white solid;
        top: 15px;
        left:15px;
        display:block;
        width: 25px;
        height: 25px;
        background: #34495e;
        cursor: pointer;
        opacity: 0.5;
    }
    #innerResult input:checked + label:before {
        background: #0d6efd;
        border: 3px white solid;
        opacity: 1;
    }

</style>
@section('page_title')
    {{ __('admin.Blog / Posts / Create') }}
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.post.index') }}" type="button" class="btn btn-sm btn-outline-success">{{ __('admin.All posts') }}</a>
{{--            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>--}}
        </div>
{{--        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
        </button>--}}
    </div>
@endsection
@section('content')
{{--{{ $post->title }}--}}
@php
    $route = route('admin.blog.post.store');
    $method = 'POST';
    $btnName = 'Create';
@endphp
@if(isset($post))
    @php
    $route = route('admin.blog.post.update', ['post' => $post->id]);
    $method = 'PUT';
    $btnName = 'Update';
    @endphp
@endif
<form method="post" action="{{ $route }}">
    @method($method)
    @csrf
    @foreach($modelParams as $param)
        {{ $param->Type }} <br>
        @if($param->Extra == 'auto_increment')
            @continue
        @endif
        @if($param->Field == 'status')
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\Modules\Blog\Posts::STATUS as $postStatus)
                    <option @if(isset($post) && $post->{$param->Field} ==  $postStatus) selected @endif value="{{ $postStatus }}">{{ $postStatus }}</option>
                @endforeach
            </select>
            @continue
        @endif
        @if($param->Field == 'post_category_id')
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                <option value=""> - no parent cat -</option>
                @foreach($categoryTree['categories'] as $category)
                    <option @if(isset($post) && $post->{$param->Field} ==  $category->id) selected @endif value="{{$category->id}}">{{$category->id}} {{$category->title}}</option>
                    @if(count($category->childs)) {{--{{ print_r($category->childs[0]->title) }}--}}
                    @include('admin.modules.blog.category.select-categories', ['childs' => $category->childs, 'model' => isset($post) ? $post : null])
                    @endif
                @endforeach
            </select>
            @continue
        @endif
        @if(in_array($param->Type, ['bigint unsigned', 'int unsigned']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? '' }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['varchar(255)']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}"
                       value="{{ $post->{$param->Field} ?? ''  }}" aria-describedby="emailHelp"
                       placeholder="{{ $param->Comment}}">
                <span data-type-id="label_{{ $param->Field }}" data-type="{{ $param->Field }}"
                      class="input-group-text"
                      data-bs-toggle="modal"
                      data-bs-target="#aiModal">
                    &nbsp;<i class="fa fa-child"></i>&nbsp; ai
                </span>
            </div>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['text', 'longtext']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <div class="input-group mb-3">
                <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}"
                          placeholder="{{ $param->Comment}}">{{ $post->{$param->Field}  ?? '' }}</textarea>
                <span data-type-id="label_{{ $param->Field }}" data-type="{{ $param->Field }}"
                      class="input-group-text"
                      data-bs-toggle="modal"
                      data-bs-target="#aiModal">
                        &nbsp;<i class="fa fa-child"></i>&nbsp; ai
                </span>
            </div>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['timestamp']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <input type="date" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        <br>{{ $param->Type }}<br>
        <label for="exampleInputEmail1">{{ $param->Field }}</label>
        <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" placeholder="{{ $param->Comment}}">{{ $post->{$param->Field}  ?? '' }}</textarea>
        <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
    @endforeach
    <br>
    <button class="btn btn-primary" type="submit">{{ $btnName }}</button>
</form>
@endsection

<!-- Modal -->
<div class="modal fade" id="aiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{__('admin.Field')}}: <span></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">{{__('admin.Ai type')}}</label>
                    <select class="form-select" id="typeTask">
                        <option value="1">{{__('admin.Task text')}}</option>
                        <option value="2">{{__('admin.Task image')}}</option>
                        <option value="3">{{__('admin.Task write text')}}</option>
                        <option value="6">{{__('admin.Task answer')}}</option>
                        <option value="7">{{__('admin.Task write rewrite')}}</option>
                        <option value="11">{{__('admin.Task make title')}}</option>
                        <option value="20">{{__('admin.Task seo title')}}</option>
                        <option value="21">{{__('admin.Task seo description')}}</option>
                        <option value="22">{{__('admin.Task seo article')}}</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{__('admin.Text')}}</label>
                    <textarea class="form form-control" name="aiForm" placeholder="{{ __('admin.Ask ai') }}"></textarea>
                </div>
                <div class="mb-3" id="innerBox" style="display: none">
                    <div class="mb-2">{{__('admin.Result')}}</div>
                    <div id="innerResult" class="shadow-lg p-3 mb-5 bg-body rounded" style="text-align: center"></div>
                </div>

                <button id="createAi" type="button" class="btn btn-primary">
                    <span>{{__('admin.Create')}}</span>
                    <svg style="fill: rgb(255, 255, 255); display: none" width="24" height="24" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <circle cx="4" cy="12" r="3">
                            <animate id="spinner_qFRN" begin="0;spinner_OcgL.end+0.25s" attributeName="cy"
                                     calcMode="spline" dur="0.6s" values="12;6;12"
                                     keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                        </circle>
                        <circle cx="12" cy="12" r="3">
                            <animate begin="spinner_qFRN.begin+0.1s" attributeName="cy" calcMode="spline" dur="0.6s"
                                     values="12;6;12" keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                        </circle>
                        <circle cx="20" cy="12" r="3">
                            <animate id="spinner_OcgL" begin="spinner_qFRN.begin+0.2s" attributeName="cy"
                                     calcMode="spline" dur="0.6s" values="12;6;12"
                                     keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                        </circle>
                    </svg>
                </button>

            </div>
            <div class="modal-footer">
                <button type="button" id="insertBtn" class="btn btn-primary" disabled>
                    {{__('admin.Insert')}}
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.Close')}}</button>
            </div>
        </div>
    </div>
</div>

<script>

    const AiManager = {

        aiCreateTaskRoute: '{{route('admin.createAiTask')}}',
        aiGetTaskRoute: '{{route('admin.getAiTask')}}',
        modalEl: document.getElementById('aiModal'),
        createAiEl: document.getElementById('createAi'),
        createAiBtn: document.getElementById('createAi'),
        insertBtn: document.getElementById('insertBtn'),
        innerBox: document.getElementById('innerBox'),
        aiResult: {},
        attemptCount: 0,
        tries: 25,

        init: function () {

            this.modalEl.addEventListener('show.bs.modal', (event) => {
                this.modalEl.dataset.typeId = event.relatedTarget.dataset.typeId
                this.modalEl.dataset.type = event.relatedTarget.dataset.type;
                let modelTitle = this.modalEl.querySelector('.modal-header .modal-title span');
                modelTitle.innerText = document.querySelector(`[for='${event.relatedTarget.dataset.typeId}']`).innerText

            });

            this.modalEl.addEventListener('hide.bs.modal', () => {
                this.reset();
            });

            this.createAiBtn.addEventListener('click', this.createAiFunc.bind(this));
            this.insertBtn.addEventListener('click', this.insertFunc.bind(this));

        },
        createAiFunc: async function () {

            let text = this.modalEl.querySelector(".modal-body [name='aiForm']").value
            let type = this.modalEl.querySelector(".modal-body #typeTask").value;

            if (text.length < 3) return;

            this.createAiBtnAction(true);

            let result = await this.fetchAi(this.aiCreateTaskRoute, {text: text, type: type})

            if (result.result) {
                const intervalId = setInterval(async () => {
                    this.aiResult = await this.fetchGetAiTask(result.task_id, intervalId);
                    if (this.aiResult?.status !== 1) return;

                    this.createAiBtnAction(false);
                    this.innerBox.style.display = 'block';
                    this.insertBtn.disabled = false;
                    this.attemptCount = 0;

                    if (this.aiResult?.url_files?.length > 0) {
                        this.responseImg();
                        return false;
                    }
                    this.aiResult.answer = this.aiResult.answer.replace(/<\/?[^>]+(>|$)/g, "");
                    this.innerBox.querySelector('#innerResult').innerText = this.aiResult.answer;


                }, 3000);
            }

        },
        fetchAi: async function (url, data) {

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

        },
        fetchGetAiTask: async function (id, intervalId) {

            let response = await this.fetchAi(this.aiGetTaskRoute, {id: id});
            let {result, answer} = await response;

            if (result && answer.status === 1 || ++this.attemptCount >= this.tries) {
                clearInterval(intervalId);
                return answer;
            }

            return answer;

        },
        responseImg: function () {

            let html = "";
            let index = 0;
            for (const img of this.aiResult.url_files) {
                index++
                html += `<input type="radio" name="img" id="myCheckbox${index}" style="display: none" value="${img}">
                         <label for="myCheckbox${index}"><img src="${img}" ></label>`;
            }

            this.innerBox.querySelector('#innerResult').innerHTML = html;

            for (let el of document.querySelectorAll('#innerResult input[type="radio"]')) {
                el.addEventListener('change', () => {
                    this.aiResult = {'answer': el.value}
                });
            }

        },
        insertFunc: function () {

            document.getElementById(this.modalEl.dataset.typeId).value = this.aiResult.answer;
            bootstrap.Modal.getInstance(this.modalEl).hide();

        },
        createAiBtnAction: function (status) {

            if (status) {
                this.createAiBtn.disabled = true;
                this.createAiBtn.querySelector('span').style.display = "none";
                this.createAiBtn.querySelector('svg').style.display = "block";
                return;
            }

            this.createAiBtn.disabled = false;
            this.createAiBtn.querySelector('span').style.display = "block";
            this.createAiBtn.querySelector('svg').style.display = "none";

        },
        reset: function () {
            this.innerBox.querySelector('#innerResult').innerText = "";
            this.innerBox.style.display = 'none';
            this.insertBtn.disabled = true;
            this.modalEl.dataset.typeId = ""
            this.modalEl.dataset.type = "";
            this.modalEl.querySelector(".modal-body [name='aiForm']").value = "";
            this.aiResult = {};
        }

    };

    AiManager.init();

</script>
