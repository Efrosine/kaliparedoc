@props(['name', 'value' => '', 'label' => 'Template Content'])

<div x-data="setupEditor('{{ $name }}')" x-init="init()" class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
    </label>

    <textarea id="{{ $name }}_textarea" name="{{ $name }}" class="hidden">{!! $value !!}</textarea>
    <div id="{{ $name }}_container" class="border border-gray-300 rounded-md min-h-[300px]"></div>

    <p class="mt-2 text-sm text-gray-500">
        Available variables: @{{name}}, @{{nik}}, @{{kk}}, @{{address}}, @{{date_of_birth}}, @{{gender}}, @{{religion}},
        @{{date}}
    </p>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


<script src="https://cdn.tiny.cloud/1/skma9s9ymvhw5w22drg68zrj4d13hy4ttzbrl95zzxdvriax/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>
<script>
    function setupEditor(fieldName) {
        return {
            init() {
                // Get the initial value from the textarea instead
                const initialValue = document.getElementById(`${fieldName}_textarea`).value;

                tinymce.init({
                    selector: `#${fieldName}_container`,
                    height: 500,
                    menubar: false,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'hr', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | ' +
                        'bold italic forecolor  hr| alignleft aligncenter ' +
                        'alignright alignjustify | bullist numlist outdent indent| ' +
                        'removeformat | help | code | placeholders',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                    init_instance_callback: function (editor) {
                        editor.on('Change', function (e) {
                            document.getElementById(`${fieldName}_textarea`).value = editor.getContent();
                        });

                        // Set initial content
                        editor.setContent(initialValue);
                    },
                    setup: function (editor) {
                        // Clean potentially dangerous scripts
                        editor.on('BeforeSetContent', function (e) {
                            e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                        });

                        // Add placeholder button for template variables
                        editor.ui.registry.addMenuButton('placeholders', {
                            text: 'Insert Variable',
                            fetch: function (callback) {
                                const items = [
                                    {
                                        type: 'menuitem',
                                        text: 'Name',
                                        onAction: function () {
                                            editor.insertContent('@{{name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'NIK',
                                        onAction: function () {
                                            editor.insertContent('@{{nik}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'KK',
                                        onAction: function () {
                                            editor.insertContent('@{{kk}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Document Number',
                                        onAction: function () {
                                            editor.insertContent('@{{document_number}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Current Date',
                                        onAction: function () {
                                            editor.insertContent('@{{date}}');
                                        }
                                    }
                                ];
                                callback(items);
                            }
                        });
                    }
                });
            }
        };
    }
</script>