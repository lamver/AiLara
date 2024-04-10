@if(1 === 2)
    <script>
@endif
const identifier = {{ $_GET['id'] }};
let configJson = [];
fetch(`/api/form/config?id={{ $_GET['id'] }}&state=${Math.floor(Math.random() * 10000)}.${Date.now()}`)
    .then(response => response.json())
    .then(json => {
        configJson = json;

        const formId = 'ai_form_' + identifier;
        const form = document.getElementById(formId);

        let iter = 0;
        let defaultIdTask = 0;

        if (Object.keys(configJson['tasks']).length === 1) {
            const firstKey = Object.keys(configJson['tasks'])[0];
            createParamsBlock();
            loadParams(firstKey);
            return;
        }

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
        createParamsBlock();
        loadParams(defaultIdTask);

    }).catch(error => console.log(error));

    function createParamsBlock() {
        const formId = 'ai_form_' + identifier;
        const form = document.getElementById(formId);
        const paramsBlock = document.createElement('div');
        paramsBlock.classList.add('row');
        paramsBlock.id = 'paramBlock_' + identifier;
        form.appendChild(paramsBlock);
    }

    function loadParams(id) {
        const paramBlockId = 'paramBlock_' + identifier;
        const paramBlock = document.getElementById(paramBlockId);
        paramBlock.innerHTML = '';
        let params = configJson['tasks'][id]['params'];

        const paramInputFormId = document.createElement('input');
        paramInputFormId.name = 'form_id';
        paramInputFormId.value = identifier;
        paramInputFormId.hidden = true;
        paramBlock.appendChild(paramInputFormId);

        const paramInputTaskId = document.createElement('input');
        paramInputTaskId.name = 'task_id';
        paramInputTaskId.value = id;
        paramInputTaskId.hidden = true;
        paramBlock.appendChild(paramInputTaskId);

        Object.keys(params).forEach(function(k, v){
            const paramInputBlock = document.createElement('div');
            if (params[k]['classListParamBlock']) {
                paramInputBlock.classList.add(params[k]['classListParamBlock']);
            }
            paramBlock.appendChild(paramInputBlock);

            if (params[k]['type'] === 'text') {
                const paramInput = document.createElement('textarea');
                paramInput.classList.add(params[k]['classList']);
                paramInput.style = (params[k]['style']);
                paramInput.name = k;
                paramInput.rows = 5;
                paramInput.id = 'param_' + k;
                paramInput.require = params[k]['required'];
                paramInput.placeholder = params[k]['placeholder'];

                const counterBlock = document.createElement('div');
                counterBlock.id = 'counterBlock_' + identifier;
                paramBlock.appendChild(counterBlock);

                let counterBlockElm = document.getElementById('counterBlock_' + identifier);

                paramInput.addEventListener('input', function() {
                    const maxLength = params[k]['max_limit'];
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
                        counterBlockElm.textContent = 'Количество символов: ' + numberFormat(currentLength) + ' из ' + numberFormat(params[k]['max_limit']);
                    } else {
                        counterBlockElm.removeChild(counterBlockElm.firstChild);
                    }
                });

                paramInputBlock.appendChild(paramInput);

                return;
            }

            if (params[k]['type'] === 'number') {
                const paramInput = document.createElement('input');
                paramInput.type = params[k]['type'];
                paramInput.classList.add(params[k]['classList']);
                paramInput.style = (params[k]['style']);
                paramInput.name = k;
                paramInput.max = params[k]['max_limit'];
                paramInput.min = params[k]['min_limit'];
                paramInput.id = 'param_' + k;
                paramInput.require = params[k]['required'];
                paramInput.placeholder = params[k]['placeholder'];
                paramInput.addEventListener('input', function() {

                    this.value = this.value.replace(/\D/g, '');

                });
                paramInputBlock.appendChild(paramInput);

                return;
            }

            if (params[k]['type'] === 'string') {
                const paramInput = document.createElement('input');
                paramInput.type = params[k]['type'];
                paramInput.classList.add(params[k]['classList']);
                paramInput.style = (params[k]['style']);
                paramInput.name = k;
                paramInput.rows = 10;
                paramInput.id = 'param_' + k;
                paramInput.require = params[k]['required'];
                paramInput.placeholder = params[k]['placeholder'];
                paramInputBlock.appendChild(paramInput);
            }

            if (params[k]['type'] === 'select') {
                const paramInput = document.createElement('select');
                paramInput.name = k;
                paramInput.classList.add(params[k]['classList']);
                paramInput.style = (params[k]['style']);
                for (const key in params[k]['options']) {
                    const optionElement = document.createElement('option');
                    optionElement.value = key;
                    optionElement.textContent = params[k]['options'][key];
                    paramInput.appendChild(optionElement);
                }
                paramInputBlock.appendChild(paramInput);
            }
        });

        const sendTaskButton = document.createElement('button');
        sendTaskButton.innerHTML = configJson['tasks'][id]['btnName'];
        sendTaskButton.setAttribute('class', 'btn btn-primary shadow-sm');
        sendTaskButton.setAttribute('style', 'min-width: 200px;');
        sendTaskButton.setAttribute('title', 'Отправить форму');
        sendTaskButton.setAttribute('type', 'submit');
        sendTaskButton.setAttribute('id', 'sendTaskButton');
        sendTaskButton.id = 'sendTaskButton_' + identifier;

        paramBlock.append(sendTaskButton);
        const formId = 'ai_form_' + identifier;
        const form = document.getElementById(formId);
        form.addEventListener('keyup', saveFormData);
        form.addEventListener('change', saveFormData);

        restoreFormData();

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
                    console.log('ff')
                    console.log(data);
                    if (data.result) {
                        localStorage.setItem('taskId',data.data.task_id)
                        location.href = data.data.task_url;
                    }
                })
                .catch(error => {
                    console.error('There has been an error with your fetch operation:', error);
                });
        });
    }

    function numberFormat(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function fillFormUserData() { console.log('ff');
        document.addEventListener('DOMContentLoaded', function(){
            const form = document.getElementById('ai_form_' + identifier);

            form.addEventListener('keyup', saveFormData);
            form.addEventListener('change', saveFormData);


        });

        restoreFormData();
    }

    function saveFormData() {
        const formId = 'ai_form_' + identifier;
        const form = document.getElementById(formId);
        const formData = new FormData(form);
        const formDataObj = Object.fromEntries(formData.entries());
        localStorage.setItem('formState', JSON.stringify(formDataObj));
    }

    function restoreFormData() {
        const savedFormData = localStorage.getItem('formState');
        const formId = 'ai_form_' + identifier;
        const form = document.getElementById(formId);
        if(savedFormData) {
            const parsedFormData = JSON.parse(savedFormData);
            for(const key in parsedFormData) {
                const input = form.querySelector(`[name='${key}']`);
                if(input) {
                    input.value = parsedFormData[key];
                }
            }
        }
    }


@if(1 === 2)
    </script>
@endif
