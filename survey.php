<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고객 만족도 설문조사</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#1F2937',
                    }
                }
            }
        }
    </script>
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
        .rating-btn {
            transition: all 0.2s;
        }
        .rating-btn:hover {
            transform: scale(1.05);
        }
        .rating-btn.active {
            background-color: #3B82F6;
            color: white;
            transform: scale(1.05);
        }
        .progress-bar {
            transition: width 0.3s ease-in-out;
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- 헤더 섹션 -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-4">
                고객 만족도 설문조사
            </h1>
            <p class="text-gray-600 text-lg">저희 식당의 서비스 향상을 위한 소중한 의견을 돌려주세요.</p>
            <div class="mt-4">
                <a href="index.php" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-home mr-2"></i>
                    홈으로 돌아가기
                </a>
            </div>
        </div>

        <!-- 진행 상태 표시 -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-600">진행률</span>
                <span class="text-sm font-medium text-gray-600" id="progressText">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full progress-bar" id="progressBar" style="width: 0%"></div>
            </div>
        </div>

        <!-- 설문 폼 -->
        <div class="glass rounded-2xl p-8 shadow-xl custom-shadow">
            <form id="surveyForm" class="space-y-8">
                <!-- 설문 질문들이 여기에 동적으로 추가됩니다 -->
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const surveyForm = document.getElementById('surveyForm');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            let currentQuestion = 0;
            let surveyData = {};
            let surveyQuestions = [];

            // 설문 데이터 로드
            try {
                const response = await fetch('survey.json');
                const data = await response.json();
                surveyQuestions = data.restaurant_survey.questions;
                renderQuestion();
            } catch (error) {
                console.error('Error loading survey:', error);
                alert('설문 데이터를 불러오는데 실패했습니다.');
            }

            // 질문 렌더링
            function renderQuestion() {
                if (currentQuestion >= surveyQuestions.length) {
                    showFinalStep();
                    return;
                }

                const question = surveyQuestions[currentQuestion];
                const progress = ((currentQuestion + 1) / surveyQuestions.length) * 100;
                progressBar.style.width = `${progress}%`;
                progressText.textContent = `${Math.round(progress)}%`;

                const questionHtml = `
                    <div class="glass-dark p-8 rounded-xl border border-white/50">
                        <h2 class="text-xl font-semibold text-gray-700 mb-6">${question.question}</h2>
                        <div class="grid grid-cols-5 gap-4">
                            ${generateRatingButtons(question)}
                        </div>
                    </div>
                `;

                surveyForm.innerHTML = questionHtml;
                addRatingButtonListeners();
            }

            // 별점 버튼 생성
            function generateRatingButtons(question) {
                const options = question.options[0];
                const labels = options.label;
                const emojis = ['😞', '😕', '😐', '🙂', '😄'];
                
                return Object.entries(labels).map(([rating, label], index) => `
                    <button type="button" class="rating-btn flex flex-col items-center justify-center py-4 px-2 rounded-xl border border-gray-200 hover:border-blue-500 text-gray-600 transition-all duration-300" data-rating="${rating}">
                        <span class="text-2xl mb-2">${emojis[index]}</span>
                        <span class="text-sm font-medium">${label}</span>
                    </button>
                `).join('');
            }

            // 별점 버튼 이벤트 리스너
            function addRatingButtonListeners() {
                const ratingButtons = document.querySelectorAll('.rating-btn');
                ratingButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        ratingButtons.forEach(btn => btn.classList.remove('active'));
                        button.classList.add('active');
                        const rating = button.dataset.rating;
                        const question = surveyQuestions[currentQuestion];
                        surveyData[question.id] = rating;
                        
                        // 잠시 후 다음 질문으로 이동
                        setTimeout(() => {
                            currentQuestion++;
                            renderQuestion();
                        }, 500);
                    });
                });
            }

            // 마지막 단계 (추가 의견)
            function showFinalStep() {
                const finalStepHtml = `
                    <div class="glass-dark p-8 rounded-xl border border-white/50">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">추가 의견이 있으시다면 알려주세요</h2>
                        <textarea 
                            id="additionalComments"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 resize-none"
                            rows="4"
                            placeholder="의견을 자유롭게 작성해주세요..."
                        ></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 px-8 py-4 rounded-xl font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-white shadow-md flex items-center gap-3 text-lg">
                            <i class="fas fa-paper-plane"></i>
                            제출하기
                        </button>
                    </div>
                `;
                surveyForm.innerHTML = finalStepHtml;
            }

            // 폼 제출 이벤트
            surveyForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const additionalComments = document.getElementById('additionalComments');
                surveyData.comments = additionalComments.value;
                surveyData.timestamp = new Date().toISOString();

                try {
                    const response = await fetch('save_survey.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(surveyData)
                    });

                    if (response.ok) {
                        alert('설문이 제출되었습니다. 감사합니다!');
                        // index 페이지로 이동
                        window.location.href = 'index.php';
                    } else {
                        throw new Error('서버 오류가 발생했습니다.');
                    }
                } catch (error) {
                    alert('설문 제출 중 오류가 발생했습니다. 다시 시도해주세요.');
                    console.error('Error:', error);
                }
            });
        });
    </script>
</body>
</html> 