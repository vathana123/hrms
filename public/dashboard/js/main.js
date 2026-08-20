(() => {
    "use strict";

    const noop = () => {};

    const onReady = callback => {
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", callback, { once: true });
            return;
        }

        callback();
    };

    const toArray = value => Array.prototype.slice.call(value || []);

    const findByName = (root, name) => {
        if (!root || !name) return null;

        return toArray(root.querySelectorAll("[name]")).find(element => element.getAttribute("name") === name) || null;
    };

    const findLabelFor = (root, name) => {
        if (!root || !name) return null;

        return toArray(root.querySelectorAll("label")).find(label => label.getAttribute("for") === name) || null;
    };

    const callIfFunction = callback => {
        if (typeof callback === "function") callback();
    };

    const valueOrEmpty = value => value === null || typeof value === "undefined" ? "" : value;

    const setupFormEvents = (events = {}, options = {}) => {
        if (options.runOnLoadFirst) {
            callIfFunction(events.onload);
        }

        window.onInput = typeof events.oninput === "function" ? events.oninput : noop;
        window.onChange = typeof events.onchange === "function" ? events.onchange : noop;

        if (!options.runOnLoadFirst) {
            callIfFunction(events.onload);
        }
    };

    const appendRequiredMarker = (form, element) => {
        if (!element.required) return;

        const label = findLabelFor(form, element.name);
        if (!label || label.dataset.requiredMarker === "true") return;

        const marker = document.createElement("span");
        marker.textContent = " *";
        marker.style.color = "red";
        label.appendChild(marker);
        label.dataset.requiredMarker = "true";
    };

    const initRequiredMarkers = (form = document.getElementById("request-form")) => {
        if (!form) return;

        form.querySelectorAll("input, select").forEach(element => appendRequiredMarker(form, element));
    };

    const clearFormControls = scope => {
        scope.querySelectorAll("input, textarea, select").forEach(element => {
            if (element.tagName === "SELECT") {
                element.selectedIndex = 0;
                return;
            }

            if (element.type === "checkbox" || element.type === "radio") {
                element.checked = false;
            }

            element.value = "";
        });
    };

    const cloneCleanTemplateRow = tbody => {
        const firstRow = tbody ? tbody.querySelector("tr:first-child") : null;
        if (!firstRow) return null;

        const template = firstRow.cloneNode(true);
        clearFormControls(template);
        return template;
    };

    const updateRowNumbers = tbody => {
        if (!tbody) return;

        tbody.querySelectorAll("tr").forEach((row, index) => {
            row.querySelectorAll("input, textarea, select").forEach(element => {
                const name = element.getAttribute("name");
                if (!name) return;

                const match = name.match(/^([^\[]+)\[(\d+)\](\[.*\])$/);
                if (match) {
                    element.setAttribute("name", `${match[1]}[${index}]${match[3]}`);
                }
            });

            const rowNumber = row.querySelector("td .no_of_row, td input[name='no_of_row[]']");
            if (rowNumber) rowNumber.value = index + 1;
        });
    };

    const createRemoveCell = (row, tbody) => {
        const cell = document.createElement("td");
        const button = document.createElement("button");

        button.type = "button";
        button.textContent = "-";
        button.className = "btn btn-sm btn-danger rm-btn";
        button.addEventListener("click", () => {
            row.remove();
            updateRowNumbers(tbody);
            window.onInput();
            window.onChange();
        });

        cell.appendChild(button);
        return cell;
    };

    const initRepeatableTables = (form = document.getElementById("request-form"), options = {}) => {
        if (!form) return;

        form.querySelectorAll("table").forEach(table => {
            if (table.dataset.repeatableInitialized === "true") return;

            const headerRow = table.querySelector("thead tr");
            const tbody = table.querySelector("tbody");
            const template = cloneCleanTemplateRow(tbody);

            if (!headerRow || !tbody || !template) return;

            const addCell = document.createElement("th");
            const addButton = document.createElement("button");

            if (options.addColumnWidth !== false) addCell.style.width = "50px";

            addButton.type = "button";
            addButton.textContent = "+";
            addButton.className = "btn btn-sm btn-success";
            addButton.addEventListener("click", () => {
                const row = template.cloneNode(true);
                row.appendChild(createRemoveCell(row, tbody));
                tbody.appendChild(row);
                updateRowNumbers(tbody);
                window.onInput();
                window.onChange();
            });

            addCell.appendChild(addButton);
            headerRow.appendChild(addCell);
            table.dataset.repeatableInitialized = "true";
            updateRowNumbers(tbody);
        });
    };

    const parseJsonObjectOrArray = value => {
        if (value && typeof value === "object") return value;

        try {
            const parsed = JSON.parse(value);
            return parsed && typeof parsed === "object" ? parsed : null;
        } catch (error) {
            return null;
        }
    };

    const findTableByRow = (form, name, row) => {
        if (!form || !row || !Object.keys(row).length) return null;

        const tables = toArray(form.querySelectorAll("table"));

        return tables.find(table => {
            const tbody = table.querySelector("tbody");
            if (!tbody) return false;

            return Object.keys(row).every(key => findByName(table, `${name}[0][${key}]`));
        }) || null;
    };

    const replaceWithReadonlyInput = (element, value) => {
        const input = document.createElement("input");

        input.type = "text";
        input.name = element.name;
        input.value = valueOrEmpty(value);
        input.className = element.className;
        input.id = element.id;
        input.style.cssText = element.style.cssText;
        input.readOnly = true;

        element.parentNode.replaceChild(input, element);
        return input;
    };

    const setEditableValue = (element, value) => {
        if (!element) return;

        switch (element.tagName.toLowerCase()) {
            case "select":
                element.value = valueOrEmpty(value);
                break;
            case "input":
                if (element.type === "checkbox") {
                    element.value = valueOrEmpty(value);
                    element.checked = Boolean(value);
                } else {
                    element.value = valueOrEmpty(value);
                }
                break;
            default:
                element.value = valueOrEmpty(value);
                break;
        }
    };

    const setReadOnlyValue = (form, element, value) => {
        if (!element) return;

        switch (element.tagName.toLowerCase()) {
            case "select":
                replaceWithReadonlyInput(element, value);
                break;
            case "input":
                if (element.type === "checkbox") {
                    if (Boolean(value)) {
                        const label = findLabelFor(form, element.name);
                        if (label) label.className = "badge badge-light d-block m-0";
                    }
                } else {
                    element.value = valueOrEmpty(value);
                    element.readOnly = true;
                }
                break;
            default:
                element.value = valueOrEmpty(value);
                element.readOnly = true;
                break;
        }
    };

    const setReadonlyGridValue = element => {
        if (!element) return null;

        if (element.tagName.toLowerCase() === "select") {
            return replaceWithReadonlyInput(element, element.value);
        }

        element.readOnly = true;
        return element;
    };

    const fillGridRow = (scope, name, row, index, options = {}) => {
        Object.entries(row).forEach(([key, value]) => {
            const originalName = `${name}[0][${key}]`;
            const indexedName = `${name}[${index}][${key}]`;
            let element = findByName(scope, originalName);

            if (!element) return;

            if (index > 0) element.setAttribute("name", indexedName);

            element.value = valueOrEmpty(value);

            if (options.readOnly) {
                element = setReadonlyGridValue(element);
                if (element) element.value = valueOrEmpty(value);
            }
        });
    };

    const populateGridValue = (form, item, options = {}) => {
        const gridData = parseJsonObjectOrArray(item.value);
        if (!gridData) return false;
        if (!Array.isArray(gridData) || gridData.length === 0) return true;

        const table = findTableByRow(form, item.name, gridData[0]);
        if (!table) return true;

        const tbody = table.querySelector("tbody");
        const template = cloneCleanTemplateRow(tbody);
        if (!tbody || !template) return true;

        gridData.forEach((rowData, index) => {
            if (index === 0) {
                fillGridRow(form, item.name, rowData, index, options);
                return;
            }

            const row = template.cloneNode(true);
            fillGridRow(row, item.name, rowData, index, options);

            if (options.addRemoveButtons) {
                row.appendChild(createRemoveCell(row, tbody));
            }

            tbody.appendChild(row);
        });

        return true;
    };

    const hideCheckboxes = (form = document) => {
        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.className = "d-none";

            const label = findLabelFor(form, checkbox.name);
            if (label) label.className = "d-none";
        });
    };

    const normalizeValues = values => Array.isArray(values) ? values : [];

    const populateEditableValues = (form, values, options = {}) => {
        normalizeValues(values).forEach(item => {
            if (populateGridValue(form, item, { addRemoveButtons: true, readOnly: false })) return;

            const element = findByName(form, item.name);
            setEditableValue(element, item.value);
        });
    };

    const populateReadOnlyValues = (form, values) => {
        normalizeValues(values).forEach(item => {
            if (populateGridValue(form, item, { readOnly: true })) return;

            const element = findByName(form, item.name);
            setReadOnlyValue(form, element, item.value);
        });
    };

    const populateApprovalEditableValues = (form, values, editableNames = []) => {
        normalizeValues(values).forEach(item => {
            if (populateGridValue(form, item, { addRemoveButtons: true, readOnly: false })) return;

            let element = findByName(form, item.name);
            if (!element) return;

            switch (element.tagName.toLowerCase()) {
                case "select":
                    if (editableNames.includes(item.name)) {
                        element.value = valueOrEmpty(item.value);
                    } else {
                        replaceWithReadonlyInput(element, item.value);
                    }
                    break;
                case "input":
                    element.value = valueOrEmpty(item.value);
                    if (element.type === "checkbox") {
                        element.checked = Boolean(item.value);
                    }
                    if (!editableNames.includes(item.name)) {
                        element.readOnly = true;
                    }
                    break;
                default:
                    element.value = valueOrEmpty(item.value);
                    break;
            }
        });
    };

    const initRequestForm = (options = {}) => {
        onReady(() => {
            setupFormEvents(options.events || {}, { runOnLoadFirst: Boolean(options.runOnLoadFirst) });

            const form = document.querySelector(options.formSelector || "#request-form");
            if (!form) return;

            initRequiredMarkers(form);
            initRepeatableTables(form, { addColumnWidth: options.addColumnWidth });

            if (options.values) {
                populateEditableValues(form, options.values);
            }
        });
    };

    const initReadOnlyRequestForm = (options = {}) => {
        onReady(() => {
            const form = document.querySelector(options.formSelector || "#request-form");
            if (!form) return;

            if (options.hideCheckboxes !== false) hideCheckboxes(form);
            populateReadOnlyValues(form, options.values);
        });
    };

    const initApprovalForm = (options = {}) => {
        onReady(() => {
            const form = document.querySelector(options.formSelector || "#request-form");
            if (!form) return;

            if (options.editable) {
                populateApprovalEditableValues(form, options.values, options.editableNames || []);
                return;
            }

            if (options.hideCheckboxes !== false) hideCheckboxes(form);
            populateReadOnlyValues(form, options.values);
        });
    };

    const initAutoPrint = () => {
        window.addEventListener("load", () => window.print(), { once: true });
    };

    const initClickableRows = (options = {}) => {
        const selector = options.selector || "[data-clickable-url]";

        document.addEventListener("click", event => {
            const row = event.target.closest(selector);
            if (!row || event.target.closest("a, button, input, select, textarea, label")) return;

            const url = row.getAttribute(options.urlAttribute || "data-clickable-url");
            if (url) window.location = url;
        });
    };

    const initConfirmSubmit = (options = {}) => {
        const selector = options.selector || "[data-confirm-submit]";

        document.addEventListener("submit", event => {
            const form = event.target.closest(selector);
            if (!form) return;

            const message = form.getAttribute(options.messageAttribute || "data-confirm-submit");
            if (message && !window.confirm(message)) event.preventDefault();
        });
    };

    const initPasswordToggle = (options = {}) => {
        onReady(() => {
            const checkbox = document.querySelector(
                options.checkboxSelector || "#show_password"
            );
            const input = document.querySelector(
                options.inputSelector || "#password"
            );
        
            if (!checkbox || !input) return;
        
            const togglePassword = () => {
                input.type = checkbox.checked ? "text" : "password";
            };
        
            // Initialize state
            togglePassword();
        
            // Toggle on change
            checkbox.addEventListener("change", togglePassword);
        });
    };

    const initTelegramVerification = (options = {}) => {
        const selector = options.buttonSelector || "#connectTelegramBtn";
        const verifyUrl = options.verifyUrl;
        const maxAttempts = Number(options.maxAttempts || 6);
        const intervalMs = Number(options.intervalMs || 10000);

        if (!verifyUrl) return;

        document.addEventListener("click", event => {
            const button = event.target.closest(selector);
            if (!button) return;

            let attempts = 0;
            const interval = window.setInterval(() => {
                attempts += 1;

                fetch(verifyUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.connected) {
                            window.clearInterval(interval);
                            window.location.reload();
                        }
                    });

                if (attempts >= maxAttempts) {
                    window.clearInterval(interval);
                    if (options.logOnStop) console.log("Telegram verification stopped.");
                }
            }, intervalMs);
        });
    };

    const initAutoSubmitFileInput = (options = {}) => {
        onReady(() => {
            const input = document.querySelector(options.inputSelector || 'input[type="file"][data-auto-submit]');
            if (!input) return;

            input.addEventListener("change", () => {
                const form = options.formSelector ? document.querySelector(options.formSelector) : input.closest("form");
                if (form) form.submit();
            });
        });
    };

    const initImagePreview = (options = {}) => {
        onReady(() => {
            const inputSelector = options.inputSelector || 'input[type="file"]';
            const preview = document.querySelector(options.previewSelector || "#image-preview");
            if (!preview) return;

            document.querySelectorAll(inputSelector).forEach(input => {
                input.addEventListener("change", function () {
                    if (!this.files || !this.files[0]) return;

                    const reader = new FileReader();
                    reader.addEventListener("load", event => {
                        preview.setAttribute("src", event.target.result);
                    });
                    reader.readAsDataURL(this.files[0]);
                });
            });
        });
    };

    const initUserEmailFromName = (options = {}) => {
        onReady(() => {
            const nameInput = document.querySelector(options.nameSelector || 'input[name="name"]');
            const emailInput = document.querySelector(options.emailSelector || 'input[name="email"]');
            const domain = options.domain || "@bnkc.com";

            if (!nameInput || !emailInput) return;

            nameInput.addEventListener("input", function () {
                let name = this.value.trim();

                if (name.length === 0) {
                    emailInput.value = "";
                    return;
                }

                name = name.toLowerCase();
                name = name.replace(/\s+/g, " ");
                name = name.replace(/ /g, ".");

                emailInput.value = name + domain;
            });
        });
    };

    const initUserCreateForm = (options = {}) => {
        initImagePreview(options.imagePreview || {});
        initUserEmailFromName(options.emailFromName || {});
    };

    const initSignatureCropper = (options = {}) => {
        onReady(() => {
            const submitButton = document.querySelector(options.submitButtonSelector || "#submit_btn");
            const currentSignature = document.querySelector(options.currentSignatureSelector || "#current_signature");
            const image = document.querySelector(options.imageSelector || "#image");
            const input = document.querySelector(options.inputSelector || "#inputImage");
            let cropper = null;

            if (!submitButton || !image || !input || typeof Cropper === "undefined") return;

            input.addEventListener("change", event => {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.addEventListener("load", () => {
                    submitButton.classList.remove("d-none");
                    if (currentSignature) currentSignature.classList.add("d-none");

                    image.src = reader.result;

                    if (cropper) cropper.destroy();

                    cropper = new Cropper(image, {
                        aspectRatio: 150 / 100,
                        viewMode: 1,
                        preview: "#preview",
                        dragMode: "move",
                        cropBoxResizable: false,
                        cropBoxMovable: false
                    });
                });
                reader.readAsDataURL(file);
            });

            submitButton.addEventListener("click", event => {
                event.preventDefault();
                if (!cropper) return;

                cropper.getCroppedCanvas({
                    width: 150,
                    height: 100
                }).toBlob(blob => {
                    const file = new File([blob], "signature.png", { type: "image/png" });
                    const dataTransfer = new DataTransfer();

                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    input.closest("form").submit();
                });
            });
        });
    };

    const initProfilePage = (options = {}) => {
        initAutoSubmitFileInput({
            inputSelector: options.avatarInputSelector || "#avatar-file",
            formSelector: options.avatarFormSelector || "#avatar-form"
        });
        initSignatureCropper(options.signature || {});
        initTelegramVerification({
            buttonSelector: options.telegramButtonSelector || "#connectTelegramBtn, #resetTelegramBtn",
            verifyUrl: options.telegramVerifyUrl,
            maxAttempts: options.maxAttempts || 6,
            intervalMs: options.intervalMs || 10000
        });
    };

    const initSummernoteEditor = (options = {}) => {
        onReady(() => {
            if (typeof $ === "undefined" || !$.fn || !$.fn.summernote) return;

            const selector = options.selector || ".summernote";

            $(selector).summernote({
                height: options.height || 300,
                callbacks: {
                    onImageUpload(files) {
                        const data = new FormData();

                        data.append("file", files[0]);
                        data.append("_token", options.csrfToken || "");

                        $.ajax({
                            url: options.uploadUrl,
                            method: "POST",
                            data,
                            contentType: false,
                            processData: false,
                            success(response) {
                                if (response) {
                                    const image = $('<img class="w-100">').attr("src", `${options.uploadsBaseUrl}/${response}`);
                                    $(selector).summernote("insertNode", image[0]);
                                }
                            },
                            error() {
                                alert("File upload failed.");
                            }
                        });
                    },
                    onMediaDelete(target) {
                        const fileUrl = target[0].src;
                        const fileName = fileUrl.substring(fileUrl.lastIndexOf("/") + 1);

                        $.ajax({
                            url: `${options.deleteUrlBase}/${fileName}`,
                            type: "POST",
                            data: {
                                _method: "DELETE",
                                _token: options.csrfToken || ""
                            },
                            error() {
                                alert("File delete failed.");
                            }
                        });
                    },
                    onKeydown(event) {
                        if (event.key !== "Backspace" && event.keyCode !== 8) return;

                        const selection = window.getSelection();
                        if (selection.rangeCount === 0) return;

                        const range = selection.getRangeAt(0);
                        const node = range.startContainer;

                        if (node.nodeType === 1 && node.querySelector("img")) {
                            event.preventDefault();
                        }
                    }
                }
            });
        });
    };

    const conditionFieldMap = {
        "User Department": "user_department_group",
        "User Branch": "user_branch_group",
        "User Job Level": "user_job_level_group",
        "User Job Position": "user_job_position_group",
        "User Role": "user_role_group",
        "Multi Text": "multi_text_group",
        Amount: "amount_group",
        Date: "date_group",
        "Current Date": "current_date_group",
        DateTime: "datetime_group",
        "Current DateTime": "current_datetime_group",
        Time: "time_group",
        "Current Time": "current_time_group"
    };

    const toggleConditionFields = select => {
        if (!select) return;

        Object.values(conditionFieldMap).forEach(id => {
            const group = document.getElementById(id);
            if (group) group.classList.add("d-none");
        });

        const activeGroup = document.getElementById(conditionFieldMap[select.value]);
        if (activeGroup) activeGroup.classList.remove("d-none");
    };

    const initApprovalConditionFields = (options = {}) => {
        onReady(() => {
            const select = document.querySelector(options.typeSelector || "#type");
            if (!select) return;

            select.addEventListener("change", () => toggleConditionFields(select));
            toggleConditionFields(select);
        });
    };

    const promptValue = label => prompt(label) || "";

    const isRequired = value => String(value || "").toLowerCase() === "yes";

    const optionsFromPrompt = value => value ? value.split(",").map(item => item.trim()).filter(Boolean) : [];

    const createSelectOptions = options => ["<option value=\"\"></option>"].concat(
        options.map(option => `<option value="${option}">${option}</option>`)
    ).join("");

    const initFormDesigner = (options = {}) => {
        onReady(() => {
            const tabDesign = document.getElementById(options.designTabId || "tab-design");
            const tabCode = document.getElementById(options.codeTabId || "tab-code");
            const formCanvas = document.getElementById(options.canvasId || "form-canvas");
            const designCode = document.getElementById(options.codeId || "design_code");
            const toolbox = document.getElementById(options.toolboxId || "toolbox");

            if (!tabDesign || !tabCode || !formCanvas || !designCode || !toolbox) return;

            const syncCodeFromCanvas = () => {
                designCode.value = formCanvas.innerHTML.trim();
            };

            const createTableHeaderElement = type => {
                if (type !== "label") return null;

                const element = document.createElement("th");
                element.innerText = promptValue("Column Name:");
                return element;
            };

            const createTableBodyElement = type => {
                const element = document.createElement("td");
                let name = "";
                let placeholder = "";
                let required = "";
                let optionsInput = "";
                let selectList = null;

                switch (type) {
                    case "textbox":
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="text" name="[0][${name}]" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "number":
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="number" name="[0][${name}]" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "amount":
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="number" name="[0][${name}]" ${isRequired(required) ? "required" : ""} placeholder="${placeholder}" step="0.01" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">`;
                        return element;
                    case "tel":
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="tel" oninput="this.value = this.value.replace(/[^0-9]/g, '')" pattern="0[0-9]{8,9}" minlength="9" maxlength="10" name="[0][${name}]" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "email":
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="email" name="[0][${name}]" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "date":
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="date" name="[0][${name}]" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "date-time":
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="datetime-local" name="[0][${name}]" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "time":
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="time" name="[0][${name}]" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "select":
                        selectList = document.createElement("select");
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        selectList.setAttribute("name", `[0][${name}]`);
                        if (isRequired(required)) selectList.setAttribute("required", "required");
                        optionsInput = promptValue("Enter options (one per line, use Shift+Enter for new line):");
                        selectList.innerHTML = createSelectOptions(optionsFromPrompt(optionsInput));
                        element.appendChild(selectList);
                        return element;
                    case "radio":
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="radio" name="[0][${name}]" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "checkbox":
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="checkbox" name="[0][${name}]" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "textarea":
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<textarea name="[0][${name}]" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}></textarea>`;
                        return element;
                    default:
                        return null;
                }
            };

            const createElement = type => {
                let element = null;
                let name = "";
                let placeholder = "";
                let required = "";
                let optionsInput = "";
                let selectList = null;

                switch (type) {
                    case "label":
                        element = document.createElement("label");
                        element.innerText = promptValue("Label:");
                        element.style.width = "180px";
                        element.classList.add("bg-light");
                        element.setAttribute("for", promptValue("For:"));
                        return element;
                    case "textbox":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="text" name="${name}" id="${name}" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "file":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="file" name="${name}" id="${name}" ${isRequired(required) ? "required" : ""} placeholder="">`;
                        return element;
                    case "number":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="number" name="${name}" id="${name}" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "amount":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="number" name="${name}" id="${name}" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""} step="0.01" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">`;
                        return element;
                    case "tel":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="tel" oninput="this.value = this.value.replace(/[^0-9]/g, '')" pattern="0[0-9]{8,9}" minlength="9" maxlength="10" name="${name}" id="${name}" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "email":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="email" name="${name}" id="${name}" placeholder="${placeholder}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "date":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="date" name="${name}" id="${name}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "date-time":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="datetime-local" name="${name}" id="${name}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "time":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<input type="time" name="${name}" id="${name}" ${isRequired(required) ? "required" : ""}>`;
                        return element;
                    case "select":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        selectList = document.createElement("select");
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        optionsInput = promptValue("Enter options (one per line, use Shift+Enter for new line):");
                        selectList.setAttribute("name", name);
                        selectList.setAttribute("id", name);
                        if (isRequired(required)) selectList.setAttribute("required", "required");
                        selectList.innerHTML = createSelectOptions(optionsFromPrompt(optionsInput));
                        element.appendChild(selectList);
                        return element;
                    case "radio":
                        element = document.createElement("div");
                        element.classList.add("p-0");
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<label><input type="radio" name="${name}" id="${name}" ${isRequired(required) ? "required" : ""}> Radio</label>`;
                        return element;
                    case "checkbox":
                        element = document.createElement("div");
                        element.classList.add("p-0");
                        name = promptValue("Name:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<label><input type="checkbox" name="${name}" id="${name}" ${isRequired(required) ? "required" : ""}> Checkbox</label>`;
                        return element;
                    case "textarea":
                        element = document.createElement("div");
                        element.classList.add("col", "p-0");
                        name = promptValue("Name:");
                        placeholder = promptValue("Placeholder:");
                        required = promptValue("Is Required: Yes, No");
                        element.innerHTML = `<textarea name="${name}" id="${name}" ${isRequired(required) ? "required" : ""} placeholder="${placeholder}"></textarea>`;
                        return element;
                    default:
                        return null;
                }
            };

            const createRow = type => {
                switch (type) {
                    case "row": {
                        const wrapper = document.createElement("div");
                        wrapper.classList.add("m-0", "row", "py-3", "border");
                        if (formCanvas.querySelectorAll(".row").length > 0) wrapper.classList.add("border-top-0");
                        return wrapper;
                    }
                    case "table": {
                        const table = document.createElement("table");
                        table.classList.add("table", "table-bordered", "table-sm");
                        table.innerHTML = "<thead><tr><th>col1</th></tr></thead>";
                        table.innerHTML += "<tbody><tr><td>cell1</td></tr></tbody>";
                        return table;
                    }
                    default:
                        return null;
                }
            };

            const showDesignTab = () => {
                tabDesign.classList.add("active");
                tabCode.classList.remove("active");
                formCanvas.classList.remove("d-none");
                designCode.classList.add("d-none");
                formCanvas.innerHTML = designCode.value.trim();
            };

            const showCodeTab = () => {
                tabCode.classList.add("active");
                tabDesign.classList.remove("active");
                formCanvas.classList.add("d-none");
                designCode.classList.remove("d-none");
                syncCodeFromCanvas();
            };

            syncCodeFromCanvas();

            tabDesign.addEventListener("click", showDesignTab);
            tabCode.addEventListener("click", showCodeTab);

            toolbox.querySelectorAll("li").forEach(item => {
                item.addEventListener("dragstart", event => {
                    event.dataTransfer.setData("type", item.getAttribute("data-type"));
                });
            });

            formCanvas.addEventListener("dragover", event => event.preventDefault());

            formCanvas.addEventListener("drop", event => {
                event.preventDefault();

                const type = event.dataTransfer.getData("type");
                const headerRow = event.target.closest("table thead tr");
                const bodyRow = event.target.closest("table tbody tr");
                const row = event.target.closest(".row");

                if (headerRow && formCanvas.contains(headerRow)) {
                    const element = createTableHeaderElement(type);
                    if (element) {
                        headerRow.appendChild(element);
                        syncCodeFromCanvas();
                    }
                    return;
                }

                if (bodyRow && formCanvas.contains(bodyRow)) {
                    const element = createTableBodyElement(type);
                    if (element) {
                        bodyRow.appendChild(element);
                        syncCodeFromCanvas();
                    }
                    return;
                }

                if (row && formCanvas.contains(row)) {
                    const element = createElement(type);
                    if (element) {
                        row.appendChild(element);
                        row.classList.remove("py-3");
                        syncCodeFromCanvas();
                    }
                    return;
                }

                const element = createRow(type);
                if (element) {
                    formCanvas.appendChild(element);
                    syncCodeFromCanvas();
                }
            });

            document.querySelectorAll(options.submitSelector || "[data-form-designer-submit]").forEach(button => {
                button.addEventListener("click", () => {
                    formCanvas.innerHTML = "";
                });
            });
        });
    };

    const initFormDesignPreview = (options = {}) => {
        initRequestForm({
            formSelector: options.formSelector || "#request-form",
            events: options.events || {},
            addColumnWidth: options.addColumnWidth
        });
    };

    const initSelectableList = () => {};
    const initAjaxModal = () => {};

    const api = {
        hideCheckboxes,
        initAjaxModal,
        initApprovalConditionFields,
        initApprovalForm,
        initAutoPrint,
        initAutoSubmitFileInput,
        initClickableRows,
        initConfirmSubmit,
        initFormDesigner,
        initFormDesignPreview,
        initImagePreview,
        initPasswordToggle,
        initProfilePage,
        initReadOnlyRequestForm,
        initRepeatableTables,
        initRequestForm,
        initRequiredMarkers,
        initSelectableList,
        initSignatureCropper,
        initSummernoteEditor,
        initTelegramVerification,
        initUserCreateForm,
        initUserEmailFromName,
        populateEditableValues,
        populateReadOnlyValues,
        updateRowNumbers
    };

    window.ApprovalDashboard = api;
    window.initAjaxModal = initAjaxModal;
    window.initApprovalConditionFields = initApprovalConditionFields;
    window.initApprovalForm = initApprovalForm;
    window.initAutoPrint = initAutoPrint;
    window.initAutoSubmitFileInput = initAutoSubmitFileInput;
    window.initClickableRows = initClickableRows;
    window.initConfirmSubmit = initConfirmSubmit;
    window.initFormDesigner = initFormDesigner;
    window.initFormDesignPreview = initFormDesignPreview;
    window.initImagePreview = initImagePreview;
    window.initPasswordToggle = initPasswordToggle;
    window.initProfilePage = initProfilePage;
    window.initReadOnlyRequestForm = initReadOnlyRequestForm;
    window.initRepeatableTables = initRepeatableTables;
    window.initRequestForm = initRequestForm;
    window.initSelectableList = initSelectableList;
    window.initSignatureCropper = initSignatureCropper;
    window.initSummernoteEditor = initSummernoteEditor;
    window.initTelegramVerification = initTelegramVerification;
    window.initUserCreateForm = initUserCreateForm;
    window.initUserEmailFromName = initUserEmailFromName;
    window.onInput = window.onInput || noop;
    window.onChange = window.onChange || noop;

    if (window.AOS && typeof window.AOS.init === "function") {
        window.AOS.init();
    }

    console.log(document.referrer);
})();
