/**
 * WYSIWYG Editor Setup for Document Templates
 * Using Quill.js editor with custom placeholder and sanitization
 */

import Quill from "quill";
import "quill/dist/quill.snow.css";

window.setupEditor = function (name, initialValue = "") {
    return {
        editor: null,
        content: initialValue,

        init() {
            // Give DOM time to load
            setTimeout(() => {
                this.initQuill();
                this.setupListeners();
            }, 100);
        },

        initQuill() {
            // Define custom placeholder options
            const placeholderOptions = [
                { label: "Name", value: "{{name}}" },
                { label: "NIK", value: "{{nik}}" },
                { label: "KK", value: "{{kk}}" },
                { label: "Address", value: "{{address}}" },
                { label: "Date of Birth", value: "{{date_of_birth}}" },
                { label: "Gender", value: "{{gender}}" },
                { label: "Religion", value: "{{religion}}" },
                { label: "Date (Today)", value: "{{date}}" },
            ];

            // Create toolbar with placeholders
            const toolbarOptions = [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline", "strike"],
                [{ list: "ordered" }, { list: "bullet" }],
                [{ indent: "-1" }, { indent: "+1" }],
                [{ align: [] }],
                ["clean"],
                ["placeholder"],
            ];

            // Create placeholder toolbar
            const placeholderDropdown = document.createElement("select");
            placeholderDropdown.className = "ql-placeholder form-select";
            placeholderDropdown.innerHTML =
                "<option selected disabled>Insert Variable</option>";

            placeholderOptions.forEach((option) => {
                const optElement = document.createElement("option");
                optElement.value = option.value;
                optElement.textContent = option.label;
                placeholderDropdown.appendChild(optElement);
            });

            // Initialize Quill
            this.editor = new Quill(`#${name}_container`, {
                modules: {
                    toolbar: {
                        container: toolbarOptions,
                        handlers: {
                            placeholder: function () {}, // Will be set after initialization
                        },
                    },
                },
                theme: "snow",
                placeholder: "Enter template content...",
            });

            // Set initial content
            if (this.content) {
                this.editor.root.innerHTML = this.content;
            }

            // Add placeholder dropdown to toolbar
            const toolbar = this.editor.getModule("toolbar");
            toolbar.addHandler("placeholder", () => {
                const range = this.editor.getSelection();
                if (range) {
                    const value = placeholderDropdown.value;
                    this.editor.insertText(range.index, value);
                }
            });

            // Append placeholder dropdown to toolbar
            const placeholderToolbar =
                toolbar.container.querySelector(".ql-placeholder");
            if (placeholderToolbar) {
                placeholderToolbar.parentNode.replaceChild(
                    placeholderDropdown,
                    placeholderToolbar
                );
            }

            // Sanitize content (remove scripts)
            const sanitizeHTML = (html) => {
                const div = document.createElement("div");
                div.innerHTML = html;

                // Remove script tags
                const scripts = div.querySelectorAll("script");
                scripts.forEach((script) => script.remove());

                return div.innerHTML;
            };

            // Apply sanitization when getting HTML
            const originalGetHTML = this.editor.root.innerHTML;
            Object.defineProperty(this.editor.root, "innerHTML", {
                get: function () {
                    return sanitizeHTML(originalGetHTML);
                },
                set: function (html) {
                    this.innerHTML = sanitizeHTML(html);
                },
            });
        },

        setupListeners() {
            // Update the hidden textarea on change
            this.editor.on("text-change", () => {
                const content = this.editor.root.innerHTML;
                document.getElementById(`${name}_textarea`).value = content;
            });
        },
    };
};
