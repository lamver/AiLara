@if(1 === 2)
    <script>
@endif
const identifier = {{ $_GET['id'] }};
let configJson = [];
fetch(`/api/form/config?id={{ $_GET['id'] }}&state=${Math.floor(Math.random() * 10000)}.${Date.now()}`)
    .then(response => response.json())
    .then(json => {
        configJson = json;
        console.log(configJson['tasks']);
        const formId = 'ai_form_' + identifier;
        const form = document.getElementById(formId);

        let iter = 0;
        let defaultIdTask = 0;

        Object.keys(configJson['tasks']).forEach(function(k, v) {
            if (defaultIdTask === 0) {
                defaultIdTask = k
            }
            const radioInput = document.createElement('input');
            radioInput.type = 'radio';
            radioInput.classList.add('btn-check');
            radioInput.name = 'taskId';
            radioInput.value = configJson['tasks'][k]['id'];
            radioInput.id = 'success-outlined_' + k;
            radioInput.autocomplete = 'off';
            radioInput.addEventListener('click', function() {
                loadParams(configJson['tasks'][k]['id']);
            });

            if (iter === 0) {
                radioInput.checked = true;
            }

            iter++;

            const label = document.createElement('label');
            label.classList.add('btn', 'btn-outline-secondary');
            label.htmlFor = 'success-outlined_' + k;
            label.textContent = configJson['tasks'][k]['name'];
            label.style = 'margin: 5px;';

            form.appendChild(radioInput);
            form.appendChild(label);
        });

        const paramsBlock = document.createElement('div');
        paramsBlock.classList.add('row');
        paramsBlock.id = 'paramBlock_' + identifier;
        form.appendChild(paramsBlock);

        loadParams(defaultIdTask);

    }).catch(error => console.log(error));

    function loadParams(id) {
        const paramBlockId = 'paramBlock_' + identifier;
        const paramBlock = document.getElementById(paramBlockId);
        paramBlock.innerHTML = '';
        let params = configJson['tasks'][id]['params'];

        Object.keys(params).forEach(function(k, v){
            console.log(params[k]);

            if (params[k]['type'] === 'text') {
                const paramInput = document.createElement('textarea');
                paramInput.classList.add(params[k]['classList']);
                paramInput.style = (params[k]['style']);
                paramInput.name = k;
                paramInput.rows = 10;
                paramInput.id = 'param_' + k;
                paramInput.require = params[k]['required'];
                paramInput.placeholder = params[k]['placeholder'];

                const counterBlock = document.createElement('div');
                counterBlock.id = 'counterBlock_' + identifier;
                paramBlock.appendChild(counterBlock);

                let counterBlockElm = document.getElementById('counterBlock_' + identifier);

                paramInput.addEventListener('input', function() {
                    const maxLength = 300;
                    const currentLength = this.value.length;

                    if (currentLength > maxLength) {
                        this.value = this.value.substring(0, maxLength);
                        alert('Превышено максимальное количество символов (300)!');
                    }

                    const counter = document.getElementById('charCounter');
                    if (!counter) {
                        const counter = document.createElement('div');
                        counter.id = 'charCounter';
                        counterBlockElm.appendChild(counter);
                    }

                    if (currentLength > 0) {
                        counterBlockElm.textContent = 'Количество символов: ' + currentLength;
                    } else if (counter) {
                        counterBlockElm.removeChild(counter);
                    }
                });

                paramBlock.appendChild(paramInput);

                return;
            }

            if (params[k]['type'] === 'number') {
                const paramInput = document.createElement('input');
                paramInput.type = params[k]['type'];
                paramInput.classList.add(params[k]['classList']);
                paramInput.style = (params[k]['style']);
                paramInput.name = k;
                paramInput.rows = 10;
                paramInput.id = 'param_' + k;
                paramInput.require = params[k]['required'];
                paramInput.placeholder = params[k]['placeholder'];

                paramBlock.appendChild(paramInput);
            }
        });

        const sendTaskButton = document.createElement('button');
        sendTaskButton.innerHTML = 'Прокомментируй сон <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg>';
        sendTaskButton.setAttribute('class', 'btn btn-primary shadow-sm');
        sendTaskButton.setAttribute('style', 'min-width: 200px;');
        sendTaskButton.setAttribute('title', 'Отправить форму');
        sendTaskButton.setAttribute('type', 'submit');
        sendTaskButton.setAttribute('id', 'sendTaskButton');
        sendTaskButton.id = 'sendTaskButton_' + identifier;

        paramBlock.append(sendTaskButton);

        document.getElementById('sendTaskButton_' + identifier).addEventListener('click', function() {
            event.preventDefault();
            let formValues = {};
            let formData = new FormData(document.getElementById('ai_form_' + identifier));

            for (let [key, value] of formData.entries()) {
                formValues[key] = value;
            }

            fetch('/api/task/execute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formValues)
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('There has been an error with your fetch operation:', error);
                });
        });
    }

@if(1 === 2)
    </script>
@endif
