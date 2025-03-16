<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบแสดงข้อมูลนักศึกษา</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* CSS เดิมทั้งหมดตามที่ให้มา */
        :root {
            --primary: #1e3a8a;
            --secondary: #3b82f6;
            --accent: #dbeafe;
            --light: #f8fafc;
            --dark: #1e293b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --font-heading: 'Prompt', sans-serif;
            --font-body: 'Sarabun', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background-color: #f1f5f9;
            color: var(--dark);
            line-height: 1.6;
            font-size: 16px;
            font-weight: 300;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .card-title {
            font-family: var(--font-heading);
            letter-spacing: -0.015em;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: var(--primary);
            background-image: linear-gradient(135deg, var(--primary), #2563eb);
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .university-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-circle {
            width: 60px;
            height: 60px;
            background-color: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary);
        }

        .header-title {
            color: white;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .header-title p {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px;
            background-color: var(--accent);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: 500;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .form-group {
            flex: 1;
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .input-control {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            font-family: var(--font-body);
        }

        .input-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .clear-search {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            display: none;
            background: transparent;
            border: none;
            font-size: 14px;
        }

        .clear-search:hover {
            color: var(--danger);
        }

        .search-status {
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 15px;
            color: #64748b;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            transition: all 0.2s;
            font-family: var(--font-heading);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #1e40af;
        }

        .error {
            background-color: #fee2e2;
            color: var(--danger);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid var(--danger);
        }

        .table-responsive {
            overflow-x: auto;
            transition: all 0.3s ease;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            line-height: 1.8;
        }

        .data-table th,
        .data-table td {
            padding: 12px 18px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
            position: sticky;
            top: 0;
            font-family: var(--font-heading);
            font-size: 15px;
        }

        .data-table tr:hover {
            background-color: #f8fafc;
        }

        .data-table tr:nth-child(even) {
            background-color: rgba(241, 245, 249, 0.7);
        }

        .data-table td {
            font-weight: 300;
        }

        .highlight {
            background-color: #fef9c3;
            padding: 2px 4px;
            border-radius: 3px;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 400;
            font-family: var(--font-heading);
        }

        .badge-id {
            background-color: var(--accent);
            color: var(--primary);
        }

        .pagination {
            display: flex;
            justify-content: flex-end;
            list-style: none;
            margin-top: 20px;
            gap: 5px;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            width: 40px;
            border-radius: 8px;
            background-color: white;
            color: var(--dark);
            text-decoration: none;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            font-family: var(--font-heading);
        }

        .pagination a.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination a:hover:not(.active) {
            background-color: #f1f5f9;
        }

        .pagination .ellipsis {
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            cursor: default;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #64748b;
            font-size: 16px;
        }

        .no-data i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #94a3b8;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            color: #64748b;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fade {
            animation: fadeIn 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .university-logo {
                justify-content: center;
            }

            .search-form {
                flex-direction: column;
            }

            .data-table th,
            .data-table td {
                padding: 10px;
            }

            body {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <div class="university-logo">
                    <div class="logo-circle">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="header-title">
                        <h1>ระบบแสดงข้อมูลนักศึกษา</h1>
                        <p>คณะวิทยาศาสตร์และเทคโนโลยีการเกษตร</p>
                    </div>
                </div>
                <div class="date">
                    <span style="color: white;">
                        <i class="far fa-calendar-alt"></i>
                        วันที่ <span id="current-date"></span>
                    </span>
                </div>
            </div>
        </header>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-graduation-cap"></i> ข้อมูลนักศึกษา
                </div>
                <div>
                    <span class="badge" style="background-color: var(--light); color: var(--dark);">
                        <i class="fas fa-users"></i> จำนวนทั้งหมด: <span id="student-count">0</span>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="search-form">
                    <div class="form-group">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" class="input-control" placeholder="ค้นหาด้วยชื่อหรือรหัสนักศึกษา (พิมพ์เพื่อค้นหาทันที)">
                        <button class="clear-search" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div id="searchStatus" class="search-status"></div>

                @if(isset($error))
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i> {{ $error }}
                </div>
                @endif

                @if(!empty($data))
                <!-- เพิ่มฟังก์ชันแปลงวันที่ใน Blade -->
                @php
                function formatThaiDate($rawDate) {
                if (empty($rawDate)) {
                return "ไม่มีข้อมูลวันที่";
                }

                // รองรับรูปแบบ "6, มีนาคม 2568 16:05 บ่าย"
                if (preg_match('/(\d{1,2})\s*,\s*([มกราคม|กุมภาพันธ์|มีนาคม|เมษายน|พฤษภาคม|มิถุนายน|กรกฎาคม|สิงหาคม|กันยายน|ตุลาคม|พฤศจิกายน|ธันวาคม]+)\s*(\d{4})\s+(\d{2}:\d{2})\s*(เช้า|บ่าย)/i', $rawDate, $matches)) {
                $day = (int)$matches[1]; // วัน
                $month = $matches[2]; // เดือน
                $year = (int)$matches[3]; // ปี
                $time = $matches[4]; // เวลา
                $period = $matches[5]; // เช้า/บ่าย

                $shortYear = substr($year, -2); // เอา 2 หลักท้าย

                // สร้างผลลัพธ์
                return "วันที่ {$day} {$month} ปี {$shortYear} เวลา {$time} ช่วง {$period}";
                }

                return $rawDate; // ถ้าไม่ตรงรูปแบบ
                }
                @endphp

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">วันที่</th>
                                <th width="15%">รหัสนักศึกษา</th>
                                <th width="30%">ชื่อ-นามสกุล</th>
                                <th width="10%">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach($data as $index => $row)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ formatThaiDate($row['column0']) }}</td> <!-- ใช้ฟังก์ชันแปลงวันที่ -->
                                <td><span class="badge badge-id">{{ $row['column2'] }}</span></td>
                                <td>{{ $row['column3'] }} {{ $row['column4'] }}</td>
                                <td class="text-center">
                                    <span class="badge" style="background-color: #dcfce7; color: #166534;">
                                        <i class="fas fa-check-circle"></i> ปกติ
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <ul class="pagination" id="pagination">
                    <!-- Pagination จะถูกสร้างโดย JavaScript -->
                </ul>
                @else
                <div class="no-data">
                    <i class="far fa-folder-open"></i>
                    <p>ไม่มีข้อมูลสำหรับแสดงผล</p>
                    <p>กรุณาตรวจสอบการเชื่อมต่อกับ Google Sheet หรือลองค้นหาใหม่อีกครั้ง</p>
                </div>
                @endif
            </div>
        </div>

        <footer class="footer">
            <p>© 2025 ระบบจัดการข้อมูลนักศึกษา มหาวิทยาลัย. สงวนลิขสิทธิ์.</p>
        </footer>
    </div>

    <script>
        // ตัวแปรหลัก
        let initialData = [];
        let filteredData = [];
        let currentPage = 1;
        const itemsPerPage = 20;
        const maxPagesToShow = 5;

        // Cache DOM elements
        const searchInput = document.getElementById("searchInput");
        const clearSearchBtn = document.getElementById("clearSearch");
        const searchStatus = document.getElementById("searchStatus");
        const tableBody = document.getElementById("tableBody");
        const pagination = document.getElementById("pagination");
        const studentCount = document.getElementById("student-count");
        const currentDateEl = document.getElementById("current-date");

        // โหลดข้อมูลเริ่มต้นจากตาราง
        function loadInitialData() {
            const rows = document.querySelectorAll("#tableBody tr");
            initialData = Array.from(rows).map((row, index) => {
                const cells = row.cells; // ใช้ .cells แทน querySelectorAll
                return {
                    index: index + 1,
                    rawDate: cells[1].textContent.trim(),
                    studentId: cells[2].firstElementChild.textContent.trim(),
                    name: cells[3].textContent.trim(),
                    status: cells[4].firstElementChild.textContent.trim().replace(/^\s*✔\s*/, "")
                };
            });
            filteredData = [...initialData];
            studentCount.textContent = initialData.length;
        }

        // แสดงวันที่ปัจจุบัน
        (function setCurrentDate() {
            const now = new Date();
            const thaiMonths = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
            ];
            const thaiYear = now.getFullYear() + 543;
            currentDateEl.textContent = `${now.getDate()} ${thaiMonths[now.getMonth()]} ${thaiYear}`;
        })();

        // สร้างแถวตารางด้วย DocumentFragment
        function populateTable(data, page = 1) {
            const fragment = document.createDocumentFragment();
            const start = (page - 1) * itemsPerPage;
            const end = Math.min(start + itemsPerPage, data.length);
            const paginatedData = data.slice(start, end);

            tableBody.innerHTML = ''; // ล้างครั้งเดียว

            if (paginatedData.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td colspan="5" class="no-data text-center">
                    <i class="fas fa-search"></i>
                    <p>ไม่พบข้อมูลที่ตรงกับการค้นหา</p>
                </td>
            `;
                fragment.appendChild(tr);
            } else {
                paginatedData.forEach(student => {
                    const tr = document.createElement('tr');
                    tr.className = 'animate-fade';
                    tr.innerHTML = `
                    <td class="text-center">${student.index}</td>
                    <td>${student.rawDate}</td>
                    <td><span class="badge badge-id">${student.studentId}</span></td>
                    <td>${student.name}</td>
                    <td class="text-center">
                        <span class="badge" style="background-color: #dcfce7; color: #166534;">
                            <i class="fas fa-check-circle"></i> ${student.status}
                        </span>
                    </td>
                `;
                    fragment.appendChild(tr);
                });
            }

            tableBody.appendChild(fragment);
            updatePagination(data.length, page);
        }

        // อัพเดต Pagination
        function updatePagination(totalItems, currentPage) {
            pagination.innerHTML = '';
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            if (totalItems === 0) return;

            const fragment = document.createDocumentFragment();
            const maxPages = Math.min(maxPagesToShow, totalPages);
            let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
            let endPage = startPage + maxPages - 1;

            if (endPage > totalPages) {
                endPage = totalPages;
                startPage = Math.max(1, endPage - maxPages + 1);
            }

            // ปุ่ม "หน้าแรก"
            fragment.appendChild(createPaginationItem('<i class="fas fa-angle-double-left"></i>', () => currentPage > 1 && renderPage(1)));
            // ปุ่ม "ก่อนหน้า"
            fragment.appendChild(createPaginationItem('<i class="fas fa-angle-left"></i>', () => currentPage > 1 && renderPage(currentPage - 1)));

            // หน้าแรกถ้าอยู่นอกช่วง
            if (startPage > 1) {
                fragment.appendChild(createPaginationItem('1', () => renderPage(1), currentPage === 1));
                if (startPage > 2) fragment.appendChild(createEllipsis());
            }

            // หมายเลขหน้า
            for (let i = startPage; i <= endPage; i++) {
                fragment.appendChild(createPaginationItem(i, () => renderPage(i), i === currentPage));
            }

            // หน้าสุดท้ายถ้าอยู่นอกช่วง
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) fragment.appendChild(createEllipsis());
                fragment.appendChild(createPaginationItem(totalPages, () => renderPage(totalPages), currentPage === totalPages));
            }

            // ปุ่ม "ถัดไป" และ "หน้าสุดท้าย"
            fragment.appendChild(createPaginationItem('<i class="fas fa-angle-right"></i>', () => currentPage < totalPages && renderPage(currentPage + 1)));
            fragment.appendChild(createPaginationItem('<i class="fas fa-angle-double-right"></i>', () => currentPage < totalPages && renderPage(totalPages)));

            pagination.appendChild(fragment);
        }

        // Helper functions สำหรับ Pagination
        function createPaginationItem(content, onClick, isActive = false) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#';
            a.innerHTML = content;
            if (isActive) a.classList.add('active');
            if (onClick) a.addEventListener('click', onClick);
            li.appendChild(a);
            return li;
        }

        function createEllipsis() {
            const li = document.createElement('li');
            li.className = 'ellipsis';
            li.textContent = '...';
            return li;
        }

        // Render หน้า
        function renderPage(page, data = filteredData) {
            currentPage = page;
            populateTable(data, currentPage);
        }

        // Debounced search
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        const performSearch = debounce(function() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            clearSearchBtn.style.display = searchTerm ? 'block' : 'none';

            if (searchTerm) {
                filteredData = initialData.filter(student =>
                    student.studentId.toLowerCase().includes(searchTerm) ||
                    student.name.toLowerCase().includes(searchTerm) ||
                    student.rawDate.toLowerCase().includes(searchTerm)
                );
                searchStatus.textContent = `พบ ${filteredData.length} รายการ จากการค้นหา "${searchInput.value}"`;
            } else {
                filteredData = [...initialData];
                searchStatus.textContent = '';
            }

            studentCount.textContent = filteredData.length;
            renderPage(1, filteredData);
        }, 300); // รอ 300ms หลังหยุดพิมพ์

        // โหลดข้อมูลเริ่มต้น
        window.addEventListener('load', function() {
            loadInitialData();
            if (initialData.length > 0) {
                renderPage(1);
            } else {
                tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="no-data text-center">
                        <i class="far fa-folder-open"></i>
                        <p>ไม่มีข้อมูลสำหรับแสดงผล</p>
                    </td>
                </tr>
            `;
            }
        });

        // Event listeners
        searchInput.addEventListener('input', performSearch);
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.focus();
            performSearch();
        });
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    </script>
</body>

</html>