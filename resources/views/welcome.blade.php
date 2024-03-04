@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="px-2 py-3 my-5 text-center">
                    <h1 class="display-5 fw-bold">
                        АйСонник - это нейросеть, которая знает все о твоих снах
                    </h1>
                    <h2>
                        Ваш персональный толкователь снов.
                    </h2>

                    <div id="ai-form-1">
                        <div class="spinner-border m-5" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-md-stretch">
                <div class="col-md-6">
                    <div class="h-100 p-5 text-white bg-dark rounded-3">
                        <h2>Сны как тунель в подсознание</h2>
                        <p>
                            Сонники - это одно из самых загадочных явлений, которое существует с самого давних времен. Люди всегда пытались понять, что скрывают сны, и какие таинственные послания могут быть закодированы в наших ночных видениях. Но что, если сонники можно связать не только с мистикой и психологией, но и с современными технологиями, такими как нейросети?

                        </p>
                        <p>
                            Искусственный интеллект, или ИИ, стал неотъемлемой частью нашей жизни, и его возможности растут с каждым днем. Может ли нейросеть помочь нам разгадать тайны наших снов? Да, новые возможности открываются перед нами благодаря синтезу мистики и современных технологий.

                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h-100 p-5 text-white bg-dark border rounded-3">
                        <h2>Процесс работы нейросети Ай Сонник</h2>
                        <p>
                            Итак, каким образом нейросеть может помочь в интерпретации снов? ИИ понимает различные символы и образы, которые могут появляться в наших снах. Это может быть все, начиная от обычных предметов до абстрактных понятий и эмоций. Нейросеть способна распознавать образы из снов, она может анализировать их и выводить различные интерпретации.
                        </p>
                        <p>
                            Сонник - нейросеть - это не просто стандартные толкования снов, которые мы можем найти в книгах и интернете, а индивидуальные и персональные интерпретации, которые учитывают все особенности нашей жизни и подсознания. Например, если вы видите сон о падении, нейросеть может анализировать ваш текущий эмоциональный и психологический статус, а также ситуацию в вашей жизни, и предложить более точное и индивидуальное толкование этого сна.
                        </p>
                        <p>
                            Кроме того, нейросеть может помочь нам понять частоту и характер наших сновидений. Она может отслеживать наши сновидения и ассоциации с определенными эмоциями или событиями в реальной жизни, что позволит нам лучше понять себя и свои внутренние мотивации.
                        </p>
                    </div>
                </div>
            </div>
            <p>
            </p><div class="row align-items-md-stretch">
                <div class="col-md-6">
                    <div class="h-100 p-5 text-white bg-dark rounded-3">
                        <h2>Преимущества нейросети АйСонник</h2>
                        <p>
                            Сонник-нейросеть может быть не только инструментом для интерпретации снов, но и средством для самопознания и развития личности. Он может помочь нам лучше понять не только наши собственные образы из снов, но и связать их с нашими эмоциями, мыслями и жизненным путем.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h-100 p-5 text-white bg-dark border rounded-3">
                        <h2>Применение нейросети для самопознания</h2>
                        <p>Тайны снов остаются загадкой, но благодаря синтезу мистики и современных технологий, мы можем приблизиться к пониманию их значимости. Нейросеть как современный алгоритм интерпретации снов может помочь нам лучше узнать самих себя, свои желания и стремления, и открыть новые горизонты в изучении сновидческого мира. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom-scripts')
    <script>
        const formId = 101;
        const mainFormTemplate = document.getElementById("ai-form-1");

        fetch(`/api/form/template?id=`+formId+`&state=${Math.floor(Math.random() * 10000)}.${Date.now()}`)
            .then(response => response.text())
            .then(html => {
                mainFormTemplate.innerHTML = html;
                const scriptMainFormClient = document.createElement("script");
                scriptMainFormClient.type = "application/javascript";
                scriptMainFormClient.async = true;
                scriptMainFormClient.src = `/api/form/js?id=`+formId+`&state=${Math.floor(Math.random() * 10000)}.${Date.now()}`;
                document.body.appendChild(scriptMainFormClient);
            }).catch(error => console.log(error));
    </script>
@endpush
