<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>자동차 운행일지</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: perspective(1000px) translateY(20px) translateZ(-10px);
            }
            to {
                opacity: 1;
                transform: perspective(1000px) translateY(0) translateZ(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .animate-fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        .glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(192, 132, 252, 0.2),
                       inset 0 0 32px 0 rgba(255, 255, 255, 0.1);
        }
        .glass-input {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 24px 0 rgba(192, 132, 252, 0.15),
                       inset 0 0 16px 0 rgba(255, 255, 255, 0.1),
                       0 0 0 1px rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            transform: perspective(1000px) translateZ(0);
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 12px 32px 0 rgba(192, 132, 252, 0.2),
                       inset 0 0 24px 0 rgba(255, 255, 255, 0.15),
                       0 0 0 2px rgba(255, 255, 255, 0.3);
            transform: perspective(1000px) translateZ(10px);
        }
        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .glass-button {
            background: rgba(168, 85, 247, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 24px 0 rgba(192, 132, 252, 0.2),
                       inset 0 0 16px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            transform: perspective(1000px) translateZ(0);
        }
        .glass-button:hover {
            background: rgba(168, 85, 247, 0.9);
            box-shadow: 0 12px 32px 0 rgba(192, 132, 252, 0.3),
                       inset 0 0 24px 0 rgba(255, 255, 255, 0.2);
            transform: perspective(1000px) translateZ(10px);
        }
        body {
            background: 
                radial-gradient(circle at 20% 20%, rgba(216, 180, 254, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(168, 85, 247, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(192, 132, 252, 0.2) 0%, transparent 70%),
                linear-gradient(135deg, #c084fc 0%, #a855f7 50%, #9333ea 100%);
            min-height: 100vh;
            color: white;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 0% 0%, rgba(255, 255, 255, 0.1) 0%, transparent 30%),
                radial-gradient(circle at 100% 100%, rgba(216, 180, 254, 0.15) 0%, transparent 30%),
                radial-gradient(circle at 50% 50%, rgba(168, 85, 247, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.7;
            cursor: pointer;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .entry-hover:hover {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 24px 0 rgba(192, 132, 252, 0.2),
                       inset 0 0 16px 0 rgba(255, 255, 255, 0.1);
            transform: perspective(1000px) translateZ(5px);
        }
        .glass-container {
            position: relative;
            z-index: 1;
        }
        .glass-container::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: rgba(216, 180, 254, 0.05);
            border-radius: inherit;
            z-index: -1;
            filter: blur(20px);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Noto Sans KR', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="min-h-screen py-12 px-4 animate-fade-in">
    <nav class="fixed top-0 left-0 right-0 z-50 animate-fade-in">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="glass rounded-xl p-4 flex justify-between items-center">
                <span class="text-white font-medium text-xl">운행일지</span>
                <div class="flex gap-4">
                    <a href="kpop.html" class="glass-button rounded-lg px-6 py-2 text-white font-medium hover:scale-105 transition-transform">
                        K-POP
                    </a>
                    <a href="saemaul.html" class="glass-button rounded-lg px-6 py-2 text-white font-medium hover:scale-105 transition-transform flex items-center">
                        <span class="mr-2">🏢</span>
                        새마을운동중앙회
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="max-w-6xl mx-auto space-y-8 mt-16">
        <div class="glass glass-container rounded-2xl p-8 animate-fade-in-up">
            <h1 class="text-4xl font-bold text-white mb-8 text-center animate-fade-in-up delay-200">자동차 운행일지</h1>
            
            <form id="drivingLogForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 animate-fade-in-up delay-300">
                        <label for="date" class="block text-sm font-medium text-white">운행 날짜</label>
                        <input type="date" id="date" name="date" required
                            class="glass-input w-full px-4 py-3 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    
                    <div class="space-y-2 animate-fade-in-up delay-300">
                        <label for="startKm" class="block text-sm font-medium text-white">시작 거리 (km)</label>
                        <input type="number" id="startKm" name="startKm" step="0.1" required
                            class="glass-input w-full px-4 py-3 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    
                    <div class="space-y-2 animate-fade-in-up delay-400">
                        <label for="endKm" class="block text-sm font-medium text-white">종료 거리 (km)</label>
                        <input type="number" id="endKm" name="endKm" step="0.1" required
                            class="glass-input w-full px-4 py-3 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <p id="kmError" class="text-red-300 text-sm hidden">종료 거리는 시작 거리보다 커야 합니다.</p>
                    </div>
                    
                    <div class="space-y-2 animate-fade-in-up delay-400">
                        <label for="destination" class="block text-sm font-medium text-white">목적지</label>
                        <input type="text" id="destination" name="destination" required
                            class="glass-input w-full px-4 py-3 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    
                    <div class="space-y-2 animate-fade-in-up delay-500">
                        <label for="purpose" class="block text-sm font-medium text-white">운행 목적</label>
                        <input type="text" id="purpose" name="purpose" required
                            class="glass-input w-full px-4 py-3 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    
                    <div class="space-y-2 animate-fade-in-up delay-500">
                        <label for="notes" class="block text-sm font-medium text-white">비고</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="glass-input w-full px-4 py-3 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-center mt-8 animate-fade-in-up delay-500">
                    <button type="submit" 
                        class="glass-button px-8 py-4 text-white font-medium rounded-xl transition-all">
                        기록 추가
                    </button>
                </div>
            </form>
        </div>

        <div class="glass glass-container rounded-2xl p-8 animate-fade-in-up delay-200">
            <h2 class="text-3xl font-bold text-white mb-6">운행 기록</h2>
            <div id="logEntries" class="space-y-4">
                <!-- 운행 기록이 여기에 추가됩니다 -->
            </div>
        </div>
    </div>

    <script>
        document.getElementById('drivingLogForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const date = document.getElementById('date').value;
            const startKm = parseFloat(document.getElementById('startKm').value);
            const endKm = parseFloat(document.getElementById('endKm').value);
            const destination = document.getElementById('destination').value;
            const purpose = document.getElementById('purpose').value;
            const notes = document.getElementById('notes').value;
            
            // 시작 거리와 종료 거리 비교
            const kmError = document.getElementById('kmError');
            if (endKm <= startKm) {
                kmError.classList.remove('hidden');
                return;
            }
            kmError.classList.add('hidden');
            
            const distance = endKm - startKm;
            
            const logEntries = document.getElementById('logEntries');
            const entryId = `entry-${Date.now()}`;
            
            const newEntry = document.createElement('div');
            newEntry.className = 'glass rounded-xl overflow-hidden animate-fade-in-up';
            newEntry.style.animationDelay = '0s';  // 새로 추가되는 항목은 즉시 애니메이션 시작
            newEntry.innerHTML = `
                <button class="w-full px-6 py-4 flex items-center justify-between entry-hover transition-all duration-300"
                        onclick="toggleAccordion('${entryId}')">
                    <div class="flex items-center space-x-4">
                        <span class="font-medium text-white">${date}</span>
                        <span class="text-white/80">${destination}</span>
                    </div>
                    <svg id="${entryId}-icon" class="w-5 h-5 transform transition-transform text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="${entryId}-content" class="hidden px-6 py-4 border-t border-white/10">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-white/60">운행 거리</p>
                            <p class="font-medium text-white">${distance.toFixed(1)} km</p>
                        </div>
                        <div>
                            <p class="text-sm text-white/60">시작 거리</p>
                            <p class="font-medium text-white">${startKm} km</p>
                        </div>
                        <div>
                            <p class="text-sm text-white/60">종료 거리</p>
                            <p class="font-medium text-white">${endKm} km</p>
                        </div>
                        <div>
                            <p class="text-sm text-white/60">운행 목적</p>
                            <p class="font-medium text-white">${purpose}</p>
                        </div>
                        ${notes ? `
                        <div class="col-span-2">
                            <p class="text-sm text-white/60">비고</p>
                            <p class="font-medium text-white">${notes}</p>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            logEntries.insertBefore(newEntry, logEntries.firstChild);
            this.reset();
        });

        function toggleAccordion(id) {
            const content = document.getElementById(`${id}-content`);
            const icon = document.getElementById(`${id}-icon`);
            
            content.classList.toggle('hidden');
            if (!content.classList.contains('hidden')) {
                icon.style.transform = 'rotate(180deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</body>
</html>