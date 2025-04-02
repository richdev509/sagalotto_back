<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        .text-overlay {
            position: absolute;
            padding: 5px;
            font-weight: bold;
            user-select: none;
            font-family: Arial, sans-serif;
            font-size: 55px;
            transform: translate(-50%, -50%);
            /* Center the text on position */
            line-height: 1.2;
            /* Adjusted text height */
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }

        .image-container {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background: #f9f9f9;
            position: relative;
            width: 100%;
        }

        #editable-image {
            display: block;
            width: 100%;
            height: auto;
        }

        .controls {
            margin-top: 20px;
        }

        .delete-btn {
            margin-left: 5px;
            cursor: pointer;
            font-weight: bold;
            pointer-events: auto;
        }

        .tirage-overlay {
            font-size: 40px !important;
            font-weight: bold !important;
            color: #f6f2f2 !important;
            border: 1px solid #f6f2f2 !important;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="image-container" id="capture-area">
            <!-- The image -->
            <img src="{{ asset('assets/images/post.jpg') }}" id="editable-image">

            <!-- Text overlay container -->
            <div id="text-overlay-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
            </div>
        </div>

        <!-- Controls -->
        <div class="controls">
            <div class="row g-3">

                <div class="col-md-4 d-flex align-items-end">
                    <button id="download-btn" class="btn btn-success">Download Image</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const image = document.getElementById('editable-image');

            const downloadBtn = document.getElementById('download-btn');
            const overlayContainer = document.getElementById('text-overlay-container');
            const captureArea = document.getElementById('capture-area');

            let isAddingText = false;

            // Load any URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const first = urlParams.get('first');
            const second = urlParams.get('second');
            const third = urlParams.get('third');
            const tirage = urlParams.get('tirage');




            // Download image with text
            downloadBtn.addEventListener('click', function () {
                downloadBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                downloadBtn.disabled = true;

                html2canvas(captureArea, {
                    logging: false,
                    useCORS: true,
                    allowTaint: true,
                    scale: 2
                }).then(canvas => {
                    const imageData = canvas.toDataURL('image/png');
                    const link = document.createElement('a');

                    const now = new Date();
                    const formattedDate = `${now.getFullYear()}-${(now.getMonth() + 1).toString().padStart(2, '0')}-${now.getDate().toString().padStart(2, '0')}`;

                    const tirage = document.querySelector('.tirage-overlay')?.textContent.replace('×', '').trim() || 'image';
                    link.download = `${tirage}-${formattedDate}.png`;
                    link.href = imageData;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    downloadBtn.innerHTML = 'Download Image';
                    downloadBtn.disabled = false;
                }).catch(err => {
                    console.error('Error generating image:', err);
                    alert('Error generating image. Please try again.');
                    downloadBtn.innerHTML = 'Download Image';
                    downloadBtn.disabled = false;
                });
            });

            // Add text element with percentage-based positioning
            function addTextElement(xPercent, yPercent, text) {
                const textElement = document.createElement('div');
                textElement.className = 'text-overlay';
                textElement.textContent = text;
                textElement.style.left = xPercent + '%';
                textElement.style.top = yPercent + '%';

                // Store original percentages as data attributes
                textElement.dataset.xPercent = xPercent;
                textElement.dataset.yPercent = yPercent;

                // Add delete button
               

                overlayContainer.appendChild(textElement);
                return textElement;
            }

            // Handle window resize - reposition all text elements
            window.addEventListener('resize', function () {
                const textElements = overlayContainer.querySelectorAll('.text-overlay');
                textElements.forEach(el => {
                    el.style.left = el.dataset.xPercent + '%';
                    el.style.top = el.dataset.yPercent + '%';
                });
            });


            // Add static text when image loads
            image.onload = function () {
                // Define static positions as percentages (adjust as needed)
                // Tirage at the top, then first, second, third in a row below
                const positions = {
                    tirage: { x: 52, y: 19},   // 50% from left, 15% from top (top center)
                    first: { x: 29, y: 32 },     // 25% from left, 40% from top
                    second: { x: 55, y: 32 },    // 50% from left, 40% from top
                    third: { x: 79, y: 32 }      // 75% from left, 40% from top
                };

                // Add tirage first (top center)
                if (tirage) {
                    const tirageElement = addTextElement(positions.tirage.x, positions.tirage.y, tirage);
                    tirageElement.classList.add('tirage-overlay');
                }

                // Then add first, second, third in a row below
                if (first) addTextElement(positions.first.x, positions.first.y, first);
                if (second) addTextElement(positions.second.x, positions.second.y, second);
                if (third) addTextElement(positions.third.x, positions.third.y, third);
            };

            // Handle case where image is already loaded
            if (image.complete) {
                image.onload();
            }
        });
    </script>
</body>

</html>