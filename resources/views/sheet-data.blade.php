<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery from Google Sheets</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
            color: #2d3748;
            line-height: 1.6;
        }

        .header-container {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }

        .page-title {
            font-weight: 600;
            color: #1a202c;
            font-size: 2rem;
            margin-bottom: 0.25rem;
        }

        .current-date {
            color: #718096;
            font-size: 1rem;
            font-weight: 300;
        }

        .gallery-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            padding: 2rem;
            margin-bottom: 3rem;
        }

        .featured-image-container {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .featured-image-container:hover {
            transform: scale(1.02);
        }

        .featured-image {
            width: 100%;
            height: 550px;
            object-fit: cover;
            border-radius: 12px;
        }

        .featured-image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.75);
            color: #fff;
            padding: 1rem 1.5rem;
            font-weight: 500;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .image-list {
            height: 550px;
            overflow-y: auto;
            padding-right: 1rem;
        }

        .image-list::-webkit-scrollbar {
            width: 8px;
        }

        .image-list::-webkit-scrollbar-track {
            background: #edf2f7;
            border-radius: 8px;
        }

        .image-list::-webkit-scrollbar-thumb {
            background: #a0aec0;
            border-radius: 8px;
        }

        .image-list::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }

        .thumbnail-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            background: #fff;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .thumbnail-item:hover {
            transform: translateX(8px);
            background: #f7fafc;
            border-left: 4px solid #4299e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .thumbnail-item.active {
            border-left: 4px solid #4299e1;
            background: #ebf8ff;
        }

        .thumbnail {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 1rem;
            border: 1px solid #e2e8f0;
        }

        .thumbnail-info {
            flex: 1;
        }

        .thumbnail-number {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.25rem;
        }

        .thumbnail-date {
            font-size: 0.875rem;
            color: #718096;
        }

        .no-data-message {
            text-align: center;
            padding: 3rem 0;
            color: #718096;
        }

        .btn-outline-primary {
            border-color: #4299e1;
            color: #4299e1;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: #4299e1;
            color: #fff;
        }

        @media (max-width: 768px) {
            .featured-image {
                height: 400px;
            }
            .image-list {
                height: auto;
                max-height: 450px;
            }
            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">รูปภาพจากกล้อง</h1>
                    <p class="current-date" id="currentDate"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="gallery-container">
            <h2 class="mb-4">
                <i class="fas fa-images me-2"></i>
                รูปภาพจากกล้อง
            </h2>

            @if(count($columnB) > 0)
                <div class="row">
                    <div class="col-md-5 mb-4">
                        <h4 class="mb-3">รูปภาพทั้งหมด</h4>
                        <div class="image-list">
                            @foreach ($columnB as $index => $data)
                                @if (!is_null($data))
                                    <div class="thumbnail-item {{ $index === count($columnB) - 1 ? 'active' : '' }}"
                                         onclick="showFeatured('{{ $data }}', {{ $index + 1 }})">
                                        <img src="{{ $data }}" class="thumbnail" alt="รูปย่อที่ {{ $index + 1 }}">
                                        <div class="thumbnail-info">
                                            <div class="thumbnail-number">รูปที่ {{ $index + 1 }}</div>
                                            <div class="thumbnail-date">
                                                เพิ่มเมื่อ: 
                                                @if (isset($columnA[$index]) && !is_null($columnA[$index]))
                                                    <span id="date-{{ $index }}">{{ $columnA[$index] }}</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h4 class="mb-3">รูปภาพล่าสุด</h4>
                        <div class="featured-image-container">
                            <img id="featuredImage" src="{{ end($columnB) }}" class="featured-image" alt="รูปภาพล่าสุด">
                            <div class="featured-image-caption">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span id="featuredCaption">รูปที่ {{ count($columnB) }}</span>
                                    <a href="{{ end($columnB) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-external-link-alt me-1"></i> ดูขนาดเต็ม
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="no-data-message">
                    <i class="fas fa-images fa-3x mb-3"></i>
                    <h4>ไม่พบรูปภาพ</h4>
                    <p>ไม่มีข้อมูลรูปภาพในคอลัมน์ B</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        let currentCount = {{ count($columnB) }};
        let latestImage = "{{ end($columnB) }}";

        document.addEventListener("DOMContentLoaded", function() {
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const today = new Date();
            document.getElementById("currentDate").textContent = "วันที่: " + today.toLocaleDateString('th-TH', options);

            // ไม่มีการแปลงวันที่ เพียงแค่แสดงข้อมูลดิบจาก $columnA
            checkForNewData();
        });

        function showFeatured(imageUrl, number) {
            const featuredImage = document.getElementById('featuredImage');
            featuredImage.src = imageUrl;
            document.getElementById('featuredCaption').textContent = `รูปที่ ${number}`;
            document.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
            event.currentTarget.classList.add('active');
            const link = document.querySelector('.featured-image-caption a');
            if (link) link.href = imageUrl;
        }

        function checkForNewData() {
            fetch("{{ route('check.latest.data') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.count > currentCount || data.latest !== latestImage) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error checking data:', error))
                .finally(() => {
                    setTimeout(checkForNewData, 10000);
                });
        }
    </script>
</body>
</html>