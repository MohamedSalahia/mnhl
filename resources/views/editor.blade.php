<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">

    <title>Certificate Editor</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

    <style>

        body{
            margin:0;
            padding:20px;
            background:#f3f4f6;
            font-family:Arial;
            direction: rtl;
        }

        .toolbar{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            margin-bottom:20px;
            align-items:center;
        }

        button,
        input,
        select{
            padding:10px;
        }

        #canvas-wrapper{
            width:1200px;
            margin:auto;
            background:#fff;
            box-shadow:0 0 10px rgba(0,0,0,.1);
        }

        canvas{
            border:1px solid #ddd;
        }

    </style>
</head>
<body>

<div class="toolbar">

    <!-- رفع الخلفية -->
    <input type="file" id="templateInput">

    <!-- رفع صورة الطالب -->
    <input type="file" id="studentImageInput">

    <!-- إضافة نص -->
    <button onclick="addText()">
        إضافة نص
    </button>

    <!-- لون الخط -->
    <input type="color" id="fontColorPicker">

    <!-- حجم الخط -->
    <input
        type="number"
        id="fontSize"
        placeholder="حجم الخط"
        value="40"
    >

    <!-- نوع الخط -->
    <select id="fontFamily">

        <option value="Arial">
            Arial
        </option>

        <option value="Cairo">
            Cairo
        </option>

        <option value="Tahoma">
            Tahoma
        </option>

    </select>

    <!-- بولد -->
    <button onclick="toggleBold()">
        Bold
    </button>

    <!-- حذف -->
    <button onclick="deleteSelected()">
        حذف
    </button>

    <!-- حفظ -->
    <button onclick="saveImage()">
        حفظ الشهادة
    </button>

</div>

<div id="canvas-wrapper">

    <canvas id="canvas" width="1200" height="800"></canvas>

</div>

<script>

    /*
    |--------------------------------------------------------------------------
    | Canvas
    |--------------------------------------------------------------------------
    */

    const canvas = new fabric.Canvas('canvas');

    /*
    |--------------------------------------------------------------------------
    | Upload Background
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('templateInput')
        .addEventListener('change', function(e){

            const file = e.target.files[0];

            if(!file) return;

            const reader = new FileReader();

            reader.onload = function(f){

                fabric.Image.fromURL(f.target.result, function(img){

                    canvas.setBackgroundImage(

                        img,

                        canvas.renderAll.bind(canvas),

                        {
                            scaleX: canvas.width / img.width,
                            scaleY: canvas.height / img.height
                        }
                    );
                });
            };

            reader.readAsDataURL(file);
        });

    /*
    |--------------------------------------------------------------------------
    | Upload Student Image
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('studentImageInput')
        .addEventListener('change', function(e){

            const file = e.target.files[0];

            if(!file) return;

            const reader = new FileReader();

            reader.onload = function(f){

                fabric.Image.fromURL(f.target.result, function(img){

                    img.set({

                        left: 100,
                        top: 100,

                        scaleX: 0.4,
                        scaleY: 0.4,

                        cornerColor: 'blue',
                        cornerStyle: 'circle',
                    });

                    canvas.add(img);

                    canvas.setActiveObject(img);
                });
            };

            reader.readAsDataURL(file);
        });

    /*
    |--------------------------------------------------------------------------
    | Add Text
    |--------------------------------------------------------------------------
    */

    function addText()
    {
        const text = new fabric.IText('اكتب هنا', {

            left: 300,
            top: 300,

            fontSize: 40,

            fill: '#000',

            fontFamily: 'Arial',

            editable: true,
        });

        canvas.add(text);

        canvas.setActiveObject(text);
    }

    /*
    |--------------------------------------------------------------------------
    | Change Font Color
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('fontColorPicker')
        .addEventListener('change', function(e){

            const activeObject = canvas.getActiveObject();

            if(
                activeObject &&
                activeObject.type === 'i-text'
            )
            {
                activeObject.set({

                    fill: e.target.value
                });

                canvas.renderAll();
            }
        });

    /*
    |--------------------------------------------------------------------------
    | Change Font Size
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('fontSize')
        .addEventListener('input', function(e){

            const activeObject = canvas.getActiveObject();

            if(
                activeObject &&
                activeObject.type === 'i-text'
            )
            {
                activeObject.set({

                    fontSize: parseInt(e.target.value)
                });

                canvas.renderAll();
            }
        });

    /*
    |--------------------------------------------------------------------------
    | Change Font Family
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('fontFamily')
        .addEventListener('change', function(e){

            const activeObject = canvas.getActiveObject();

            if(
                activeObject &&
                activeObject.type === 'i-text'
            )
            {
                activeObject.set({

                    fontFamily: e.target.value
                });

                canvas.renderAll();
            }
        });

    /*
    |--------------------------------------------------------------------------
    | Toggle Bold
    |--------------------------------------------------------------------------
    */

    function toggleBold()
    {
        const activeObject = canvas.getActiveObject();

        if(
            activeObject &&
            activeObject.type === 'i-text'
        )
        {
            activeObject.set({

                fontWeight:
                    activeObject.fontWeight === 'bold'
                        ? 'normal'
                        : 'bold'
            });

            canvas.renderAll();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Selected
    |--------------------------------------------------------------------------
    */

    function deleteSelected()
    {
        const activeObject = canvas.getActiveObject();

        if(activeObject)
        {
            canvas.remove(activeObject);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete By Keyboard
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function(e){

        if(e.key === 'Delete')
        {
            deleteSelected();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Save Final Image
    |--------------------------------------------------------------------------
    */

    function saveImage()
    {
        const image = canvas.toDataURL({

            format: 'png',

            quality: 1
        });

        fetch('/save-certificate', {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'X-CSRF-TOKEN':
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content
            },

            body: JSON.stringify({

                image: image
            })
        })
            .then(res => res.json())
            .then(res => {

                alert('تم حفظ الشهادة');

                window.open(res.url);
            });
    }

</script>

</body>
</html>
