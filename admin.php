<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 로그인</title>
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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- 로고 섹션 -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                관리자 로그인
            </h1>
            <p class="text-gray-600 mt-2">관리자 계정으로 로그인하세요</p>
        </div>

        <!-- 로그인 폼 -->
        <div class="glass rounded-2xl p-8 shadow-xl custom-shadow">
            <form id="loginForm" class="space-y-6">
                <!-- 아이디 입력 -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                        아이디
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="glass-dark block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                            placeholder="아이디를 입력하세요"
                            required
                        >
                    </div>
                </div>

                <!-- 비밀번호 입력 -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        비밀번호
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="glass-dark block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                            placeholder="비밀번호를 입력하세요"
                            required
                        >
                    </div>
                </div>

                <!-- 로그인 버튼 -->
                <div>
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 px-4 py-2 rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-white shadow-md flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-sign-in-alt"></i>
                        로그인
                    </button>
                </div>
            </form>
        </div>

        <!-- 홈으로 돌아가기 링크 -->
        <div class="text-center mt-4">
            <a href="index.php" class="text-gray-500 hover:text-gray-700 text-sm transition-colors">
                <i class="fas fa-arrow-left"></i> 홈으로 돌아가기
            </a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            // 여기에 실제 로그인 로직을 구현할 수 있습니다
            if (username === 'admin' && password === 'admin123') {
                alert('로그인 성공!');
                // 로그인 성공 후 리다이렉트
                window.location.href = 'admin_dashboard.php';
            } else {
                alert('아이디 또는 비밀번호가 올바르지 않습니다.');
            }
        });
    </script>
</body>
</html> 