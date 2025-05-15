<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML to PPT 변환</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        .glass-dark {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        body {
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4ecfb 100%);
            min-height: 100vh;
        }
        .custom-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .drop-zone {
            border: 2px dashed #cbd5e0;
            transition: all 0.3s ease;
        }
        .drop-zone:hover {
            border-color: #3B82F6;
            background-color: rgba(59, 130, 246, 0.05);
        }
        .drop-zone.dragover {
            border-color: #3B82F6;
            background-color: rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- 헤더 섹션 -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-4">
                HTML to PPT 변환
            </h1>
            <p class="text-gray-600 text-lg">HTML 파일을 PowerPoint 프레젠테이션으로 변환하세요</p>
        </div>

        <!-- 변환 섹션 -->
        <div class="glass rounded-2xl p-8 shadow-xl custom-shadow">
            <!-- 파일 업로드 영역 -->
            <div class="mb-8">
                <div id="dropZone" class="drop-zone rounded-xl p-8 text-center cursor-pointer">
                    <div class="space-y-4">
                        <div class="text-4xl text-gray-400">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="text-gray-600">
                            <p class="font-medium">HTML 파일을 여기에 드래그하거나</p>
                            <p class="text-sm mt-1">클릭하여 파일 선택</p>
                        </div>
                        <input type="file" id="fileInput" class="hidden" accept=".html,.htm">
                    </div>
                </div>
            </div>

            <!-- 변환 옵션 -->
            <div class="glass-dark p-6 rounded-xl border border-white/50 mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">변환 옵션</h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="includeImages" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <label for="includeImages" class="text-gray-700">이미지 포함</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="includeStyles" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <label for="includeStyles" class="text-gray-700">스타일 포함</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="createSlides" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <label for="createSlides" class="text-gray-700">섹션별 슬라이드 생성</label>
                    </div>
                </div>
            </div>

            <!-- 변환 버튼 -->
            <div class="flex justify-end">
                <button 
                    id="convertBtn"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 px-8 py-4 rounded-xl font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-white shadow-md flex items-center gap-3 text-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled
                >
                    <i class="fas fa-sync-alt"></i>
                    변환하기
                </button>
            </div>
        </div>

        <!-- 변환 결과 -->
        <div id="resultSection" class="mt-8 hidden">
            <div class="glass rounded-2xl p-8 shadow-xl custom-shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">변환 결과</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white rounded-lg">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-powerpoint text-red-500 text-2xl"></i>
                            <div>
                                <p class="font-medium text-gray-700">presentation.pptx</p>
                                <p class="text-sm text-gray-500">2.5 MB</p>
                            </div>
                        </div>
                        <button class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const convertBtn = document.getElementById('convertBtn');
            const resultSection = document.getElementById('resultSection');

            // 드래그 앤 드롭 이벤트
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('dragover');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
                const files = e.dataTransfer.files;
                handleFiles(files);
            });

            // 파일 선택 이벤트
            dropZone.addEventListener('click', () => {
                fileInput.click();
            });

            fileInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });

            // 파일 처리 함수
            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type === 'text/html' || file.name.endsWith('.html') || file.name.endsWith('.htm')) {
                        convertBtn.disabled = false;
                        // 파일 이름 표시
                        const fileNameElement = dropZone.querySelector('p.font-medium');
                        fileNameElement.textContent = file.name;
                        // 파일 크기 표시
                        const fileSizeElement = dropZone.querySelector('p.text-sm');
                        fileSizeElement.textContent = formatFileSize(file.size);
                    } else {
                        alert('HTML 파일만 업로드 가능합니다.');
                    }
                }
            }

            // 변환 버튼 클릭 이벤트
            convertBtn.addEventListener('click', async () => {
                if (!fileInput.files.length) {
                    alert('파일을 선택해주세요.');
                    return;
                }

                convertBtn.disabled = true;
                convertBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 변환 중...';

                const formData = new FormData();
                formData.append('htmlFile', fileInput.files[0]);
                formData.append('options', JSON.stringify({
                    includeImages: document.getElementById('includeImages').checked,
                    includeStyles: document.getElementById('includeStyles').checked,
                    createSlides: document.getElementById('createSlides').checked
                }));

                try {
                    const response = await fetch('convert_html_to_ppt.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        resultSection.classList.remove('hidden');
                        const resultFile = resultSection.querySelector('.flex.items-center.justify-between');
                        resultFile.querySelector('p.font-medium').textContent = data.file.name;
                        resultFile.querySelector('p.text-sm').textContent = formatFileSize(data.file.size);
                        
                        // 다운로드 버튼에 이벤트 리스너 추가
                        const downloadBtn = resultFile.querySelector('button');
                        downloadBtn.onclick = () => {
                            const downloadUrl = `convert_html_to_ppt.php?download=${encodeURIComponent(data.file.name)}`;
                            window.location.href = downloadUrl;
                        };
                    } else {
                        alert(data.message || '변환 중 오류가 발생했습니다.');
                    }
                } catch (error) {
                    alert('서버 오류가 발생했습니다.');
                    console.error('Error:', error);
                } finally {
                    convertBtn.disabled = false;
                    convertBtn.innerHTML = '<i class="fas fa-sync-alt"></i> 변환하기';
                }
            });

            // 파일 크기 포맷팅 함수
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
</body>
</html> 