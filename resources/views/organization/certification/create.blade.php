@php use App\Enums\CurriculumTypeEnum; @endphp
@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('curricula.curricula')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.curricula.index') }}" wire:navigate>@lang('curricula.curricula')</a></li>
                                <li class="breadcrumb-item active">@lang('site.create')</li>
                            </ol>

                        </div><!-- end of breadcrumb -->
                    </div>

                </div><!-- end of row -->

            </div><!-- end of content header -->

        </div><!-- end of content header -->

        <div class="content-body">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">

                            <form method="post" action="{{ route('organization.curricula.store') }}" class="ajax-form" enctype="multipart/form-data">
                                @csrf
                                @method('post')

                                {{--name--}}
                                <div class="form-group">
                                    <label>@lang('curricula.name') <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" autofocus required>
                                </div>

                                {{--curriculum_type--}}
                                <div class="form-group">
                                    <label>@lang('curricula.curriculum_type') <span class="text-danger">*</span></label>
                                    <select name="curriculum_type" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('curricula.curriculum_type')</option>
                                        @foreach (CurriculumTypeEnum::getConstants() as $curriculumType)
                                            <option value="{{ $curriculumType }}">{{ __('curricula.' . strtolower($curriculumType)) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--book_name--}}
                                <div class="form-group">
                                    <label>@lang('curricula.book_name') <span class="text-danger">*</span></label>
                                    <input type="text" name="book_name" class="form-control" value="{{ old('book_name') }}" required>
                                </div>

                                {{--book_file--}}
                                <div class="form-group">
                                    <label>@lang('curricula.book_file') <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="file" name="book_file" class="form-control upload-image" id="book-file" accept=".pdf" style="display: none;">
                                        <label for="book-file" class="form-control custom-file-upload file-label">@lang('site.choose_file')</label>
                                        <button class="btn btn-outline-secondary browse-file-btn" type="button">@lang('site.browse')</button>
                                    </div>
                                </div>

                                {{--book_number_of_pages--}}
                                <div class="form-group">
                                    <label>@lang('curricula.book_number_of_pages')</label>
                                    <input type="number" name="book_number_of_pages" class="form-control" value="{{ old('book_number_of_pages') }}" min="1">
                                </div>

                                {{-- Certificate Editor --}}
                                <div class="form-group">
                                    <label>@lang('curricula.certificate_template')</label>

                                    {{-- Toolbar --}}
                                    <div class="d-flex flex-wrap gap-1 mb-1 align-items-center" id="cert-toolbar">

                                        <div class="input-group" style="width:auto">
                                            <input type="file" id="templateInput" class="form-control" style="display:none">
                                            <label for="templateInput" class="btn btn-outline-secondary mb-0">
                                                <i data-feather="image"></i> @lang('curricula.upload_background')
                                            </label>
                                        </div>

                                        <div class="input-group" style="width:auto">
                                            <input type="file" id="studentImageInput" class="form-control" style="display:none">
                                            <label for="studentImageInput" class="btn btn-outline-secondary mb-0">
                                                <i data-feather="user"></i> @lang('curricula.upload_student_image')
                                            </label>
                                        </div>

                                        <button type="button" class="btn btn-outline-primary" onclick="addText()">
                                            <i data-feather="type"></i> @lang('curricula.add_text')
                                        </button>

                                        <input type="color" id="fontColorPicker" class="form-control" style="width:50px;padding:2px" title="@lang('curricula.font_color')">

                                        <input type="number" id="fontSize" class="form-control" style="width:90px" placeholder="@lang('curricula.font_size')" value="40">

                                        <select id="fontFamily" class="form-control" style="width:120px">
                                            <option value="Arial">Arial</option>
                                            <option value="Cairo">Cairo</option>
                                            <option value="Tahoma">Tahoma</option>
                                        </select>

                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleBold()"><b>B</b></button>

                                        <button type="button" class="btn btn-outline-danger" onclick="deleteSelected()">
                                            <i data-feather="trash-2"></i> @lang('site.delete')
                                        </button>

                                    </div>

                                    {{-- Canvas --}}
                                    <div style="width:100%;overflow-x:auto;background:#fff;border:1px solid #ddd;border-radius:4px">
                                        <canvas id="cert-canvas" width="1200" height="800"></canvas>
                                    </div>

                                    {{-- Hidden input to store canvas data --}}
                                    <input type="hidden" name="certificate_template" id="certificate_template">

                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</button>
                                </div>

                            </form><!-- end of form -->

                        </div><!-- end card body-->

                    </div><!-- end of card -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script>

        const canvas = new fabric.Canvas('cert-canvas');

        // Upload Background
        document.getElementById('templateInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (f) {
                fabric.Image.fromURL(f.target.result, function (img) {
                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                        scaleX: canvas.width / img.width,
                        scaleY: canvas.height / img.height
                    });
                });
            };
            reader.readAsDataURL(file);
        });

        // Upload Student Image
        document.getElementById('studentImageInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (f) {
                fabric.Image.fromURL(f.target.result, function (img) {
                    img.set({ left: 100, top: 100, scaleX: 0.4, scaleY: 0.4, cornerColor: 'blue', cornerStyle: 'circle' });
                    canvas.add(img);
                    canvas.setActiveObject(img);
                });
            };
            reader.readAsDataURL(file);
        });

        // Add Text
        function addText() {
            const text = new fabric.IText('اكتب هنا', {
                left: 300, top: 300, fontSize: 40, fill: '#000', fontFamily: 'Arial', editable: true
            });
            canvas.add(text);
            canvas.setActiveObject(text);
        }

        // Font Color
        document.getElementById('fontColorPicker').addEventListener('change', function (e) {
            const obj = canvas.getActiveObject();
            if (obj && obj.type === 'i-text') { obj.set({ fill: e.target.value }); canvas.renderAll(); }
        });

        // Font Size
        document.getElementById('fontSize').addEventListener('input', function (e) {
            const obj = canvas.getActiveObject();
            if (obj && obj.type === 'i-text') { obj.set({ fontSize: parseInt(e.target.value) }); canvas.renderAll(); }
        });

        // Font Family
        document.getElementById('fontFamily').addEventListener('change', function (e) {
            const obj = canvas.getActiveObject();
            if (obj && obj.type === 'i-text') { obj.set({ fontFamily: e.target.value }); canvas.renderAll(); }
        });

        // Toggle Bold
        function toggleBold() {
            const obj = canvas.getActiveObject();
            if (obj && obj.type === 'i-text') {
                obj.set({ fontWeight: obj.fontWeight === 'bold' ? 'normal' : 'bold' });
                canvas.renderAll();
            }
        }

        // Delete Selected
        function deleteSelected() {
            const obj = canvas.getActiveObject();
            if (obj) canvas.remove(obj);
        }

        // Delete by keyboard
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Delete') deleteSelected();
        });

        // Before submit — save canvas JSON into hidden input
        document.querySelector('.ajax-form').addEventListener('submit', function () {
            document.getElementById('certificate_template').value = JSON.stringify(canvas.toJSON());
        });

    </script>
@endpush
