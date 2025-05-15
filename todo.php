<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
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
        .calendar-day {
            transition: all 0.2s;
            cursor: pointer;
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
        }
        .calendar-day:hover:not(.empty-day):not(.selected-day) {
            background-color: rgba(219, 234, 254, 0.7);
        }
        .selected-day {
            background: rgba(59, 130, 246, 0.9);
            color: white;
            font-weight: bold;
        }
        .today-highlight {
            border: 2px solid #3B82F6;
            font-weight: bold;
        }
        .empty-day {
            background: transparent;
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
<body>
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- 메인 컨테이너 -->
        <div class="glass rounded-2xl p-6 shadow-xl custom-shadow">
            <!-- 헤더 -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                    To Do List
                </h1>
                <p class="text-gray-600 text-sm mt-1">오늘의 할 일을 관리하세요</p>
                <div class="mt-4">
                    <a href="index.php" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                        <i class="fas fa-home mr-2"></i>
                        홈으로 돌아가기
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- 왼쪽 사이드: 달력 표시 -->
                <div class="glass-dark p-5 rounded-xl border border-white/50 shadow-sm lg:col-span-1">
                    <!-- 캘린더 섹션 -->
                    <div class="glass-dark p-6 rounded-2xl border border-white/50 shadow-sm mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <button id="prevMonth" class="text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-lg hover:bg-blue-50">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button id="calendarTitle" class="text-lg font-semibold text-gray-700 hover:text-blue-600 transition-colors">
                                <span id="currentDate">2024년 3월 19일</span>
                            </button>
                            <button id="nextMonth" class="text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-lg hover:bg-blue-50">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center mb-2">
                            <div class="text-red-500 font-medium">일</div>
                            <div class="font-medium">월</div>
                            <div class="font-medium">화</div>
                            <div class="font-medium">수</div>
                            <div class="font-medium">목</div>
                            <div class="font-medium">금</div>
                            <div class="text-blue-500 font-medium">토</div>
                        </div>
                        <div id="calendarGrid" class="grid grid-cols-7 gap-1">
                            <!-- Calendar days will be dynamically inserted here -->
                        </div>
                    </div>

                    <!-- 미니 캘린더 팝업 -->
                    <div id="miniCalendarPopup" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 hidden">
                        <div class="glass-dark p-4 rounded-xl border border-white/50 shadow-xl w-80 relative">
                            <button id="closeMiniCalendar" class="absolute -top-2 -right-2 text-gray-500 hover:text-gray-700 p-1 bg-white rounded-full shadow-sm">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="flex items-center justify-between mb-4">
                                <button id="prevYear" class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-50">
                                    <i class="fas fa-angle-double-left"></i>
                                </button>
                                <h3 id="miniCalendarYear" class="text-base font-semibold text-gray-700"></h3>
                                <button id="nextYear" class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-50">
                                    <i class="fas fa-angle-double-right"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">1월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">2월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">3월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">4월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">5월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">6월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">7월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">8월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">9월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">10월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">11월</button>
                                <button class="month-btn p-2 text-sm rounded hover:bg-blue-50 text-gray-700">12월</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 오른쪽: 할 일 목록 -->
                <div class="lg:col-span-3">
                    <!-- 입력 폼 -->
                    <div class="bg-white/50 p-5 rounded-xl shadow-sm mb-5">
                        <form id="todoForm" class="mb-0">
                            <div class="flex gap-3">
                                <input 
                                    type="text" 
                                    id="todoInput" 
                                    class="flex-1 glass-dark border border-white/50 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-inner text-sm"
                                    placeholder="할 일을 입력하세요..."
                                >
                                <button 
                                    type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 px-4 py-2 rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-white shadow-md flex items-center gap-2 text-sm"
                                >
                                    <i class="fas fa-plus"></i>
                                    추가
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 필터 버튼 -->
                    <div class="bg-white/30 p-1.5 rounded-lg mb-5 inline-flex gap-1">
                        <button 
                            class="filter-btn active px-4 py-1.5 rounded-md text-xs font-medium transition-all text-white bg-blue-500 shadow-sm"
                            data-filter="all"
                        >
                            전체
                        </button>
                        <button 
                            class="filter-btn px-4 py-1.5 rounded-md text-xs font-medium transition-all text-gray-600 hover:bg-white/80"
                            data-filter="active"
                        >
                            진행중
                        </button>
                        <button 
                            class="filter-btn px-4 py-1.5 rounded-md text-xs font-medium transition-all text-gray-600 hover:bg-white/80"
                            data-filter="completed"
                        >
                            완료
                        </button>
                    </div>

                    <!-- 할 일 목록 -->
                    <div class="bg-white/50 p-5 rounded-xl">
                        <div id="todoList" class="space-y-2">
                            <!-- 할 일 항목들이 여기에 동적으로 추가됩니다 -->
                        </div>

                        <!-- 완료된 항목 삭제 버튼 -->
                        <div class="mt-5 flex justify-end">
                            <button 
                                id="clearCompleted"
                                class="bg-red-500 hover:bg-red-600 px-4 py-1.5 rounded-md text-xs font-medium transition-all text-white shadow-sm flex items-center gap-1"
                            >
                                <i class="fas fa-trash-alt"></i> 완료된 항목 삭제
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const todoForm = document.getElementById('todoForm');
            const todoInput = document.getElementById('todoInput');
            const todoList = document.getElementById('todoList');
            const clearCompleted = document.getElementById('clearCompleted');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Calendar elements
            const currentDateElement = document.getElementById('currentDate');
            const calendarTitle = document.getElementById('calendarTitle');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');
            const calendarGrid = document.getElementById('calendarGrid');
            const miniCalendarPopup = document.getElementById('miniCalendarPopup');
            const miniCalendarYear = document.getElementById('miniCalendarYear');
            const prevYearBtn = document.getElementById('prevYear');
            const nextYearBtn = document.getElementById('nextYear');
            const closeMiniCalendarBtn = document.getElementById('closeMiniCalendar');
            const monthButtons = document.querySelectorAll('.month-btn');

            // Global variables
            let currentDate = new Date();
            let selectedDate = new Date();
            let currentYear = new Date().getFullYear();
            
            // Map to store todos by date
            let todosByDate = {};
            // Load todos from localStorage
            try {
                todosByDate = JSON.parse(localStorage.getItem('todosByDate')) || {};
            } catch (e) {
                todosByDate = {};
            }
            
            let currentFilter = 'all';
            
            // Get todos for selected date
            function getTodosForSelectedDate() {
                const dateKey = formatDateKey(selectedDate);
                return todosByDate[dateKey] || [];
            }
            
            // Save todos for selected date
            function saveTodosForSelectedDate(todos) {
                const dateKey = formatDateKey(selectedDate);
                todosByDate[dateKey] = todos;
                localStorage.setItem('todosByDate', JSON.stringify(todosByDate));
            }
            
            // Format date for use as object key
            function formatDateKey(date) {
                return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
            }

            // Calendar functions
            function formatDate(date) {
                return `${date.getFullYear()}년 ${date.getMonth() + 1}월 ${date.getDate()}일`;
            }

            function formatMonthYear(date) {
                return `${date.getFullYear()}년 ${date.getMonth() + 1}월`;
            }

            function updateCurrentDate() {
                currentDateElement.textContent = formatDate(selectedDate);
                calendarTitle.textContent = formatMonthYear(currentDate);
                renderCalendar();
                renderTodos();
            }

            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const totalDays = lastDay.getDate();
                const startingDay = firstDay.getDay();

                calendarGrid.innerHTML = '';

                for (let i = 0; i < startingDay; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'h-10 flex items-center justify-center text-gray-300';
                    calendarGrid.appendChild(emptyCell);
                }

                const today = new Date();
                for (let day = 1; day <= totalDays; day++) {
                    const dateCell = document.createElement('div');
                    const isToday = year === today.getFullYear() && 
                                   month === today.getMonth() && 
                                   day === today.getDate();
                    const isSelected = year === selectedDate.getFullYear() && 
                                     month === selectedDate.getMonth() && 
                                     day === selectedDate.getDate();
                    
                    dateCell.className = `h-10 flex items-center justify-center rounded-lg cursor-pointer transition-all
                        ${isToday ? 'bg-blue-100 text-blue-600 font-bold' : 'hover:bg-white/50'}
                        ${isSelected ? 'ring-2 ring-blue-500 font-bold' : ''}`;
                    dateCell.textContent = day;
                    
                    dateCell.addEventListener('click', () => {
                        selectedDate = new Date(year, month, day);
                        updateCurrentDate();
                    });
                    
                    calendarGrid.appendChild(dateCell);
                }
            }

            // 미니 캘린더 함수
            function updateMiniCalendarYear() {
                miniCalendarYear.textContent = `${currentYear}년`;
            }

            function showMiniCalendar() {
                currentYear = currentDate.getFullYear();
                miniCalendarPopup.classList.remove('hidden');
                updateMiniCalendarYear();
            }

            function hideMiniCalendar() {
                miniCalendarPopup.classList.add('hidden');
            }

            // 이벤트 리스너
            calendarTitle.addEventListener('click', showMiniCalendar);
            closeMiniCalendarBtn.addEventListener('click', hideMiniCalendar);
            miniCalendarPopup.addEventListener('click', (e) => {
                if (e.target === miniCalendarPopup) {
                    hideMiniCalendar();
                }
            });

            prevYearBtn.addEventListener('click', () => {
                currentYear--;
                updateMiniCalendarYear();
            });

            nextYearBtn.addEventListener('click', () => {
                currentYear++;
                updateMiniCalendarYear();
            });

            monthButtons.forEach((btn, index) => {
                btn.addEventListener('click', () => {
                    currentDate = new Date(currentYear, index, 1);
                    selectedDate = new Date(currentDate);
                    updateCurrentDate();
                    renderCalendar();
                    hideMiniCalendar();
                });
            });

            prevMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                selectedDate = new Date(currentDate);
                updateCurrentDate();
                renderCalendar();
            });

            nextMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                selectedDate = new Date(currentDate);
                updateCurrentDate();
                renderCalendar();
            });

            // 초기화
            updateCurrentDate();
            renderCalendar();
            updateMiniCalendarYear();

            // ESC 키로 미니 캘린더 닫기
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    hideMiniCalendar();
                }
            });

            // 할 일 목록 렌더링
            function renderTodos() {
                const todos = getTodosForSelectedDate();
                
                const filteredTodos = todos.filter(todo => {
                    if (currentFilter === 'active') return !todo.completed;
                    if (currentFilter === 'completed') return todo.completed;
                    return true;
                });

                todoList.innerHTML = filteredTodos.length > 0 ? 
                    filteredTodos.map((todo, index) => `
                        <div class="todo-item glass-dark rounded-lg p-3 flex items-center justify-between transition-all duration-200 group border border-white/50 hover:border-blue-200 ${
                            todo.completed 
                            ? 'bg-green-50/50 hover:bg-green-50/70' 
                            : 'hover:bg-blue-50/50'
                        }">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="relative">
                                    <input 
                                        type="checkbox" 
                                        ${todo.completed ? 'checked' : ''} 
                                        data-index="${index}"
                                        class="w-4 h-4 rounded-full border-2 border-blue-500 text-blue-500 focus:ring-blue-500 focus:ring-offset-transparent bg-white/50 transition-all"
                                    >
                                    ${todo.completed ? '<div class="absolute inset-0 bg-green-100 rounded-full opacity-20"></div>' : ''}
                                </div>
                                <span class="flex-1 min-w-0 ${
                                    todo.completed 
                                    ? 'line-through text-gray-400' 
                                    : 'text-gray-700'
                                } truncate text-sm">
                                    ${todo.text}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 ml-2">
                                <span class="text-xs px-2 py-0.5 rounded-full ${
                                    todo.completed 
                                    ? 'bg-green-100 text-green-700' 
                                    : 'bg-blue-100 text-blue-700'
                                }">
                                    ${todo.completed ? '완료' : '진행중'}
                                </span>
                                <button 
                                    data-index="${index}"
                                    class="delete-btn opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 transition-all duration-200 p-1 hover:bg-red-50 rounded-full"
                                >
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    `).join('') : 
                    `<div class="text-center text-gray-500 py-8">
                        <i class="fas fa-clipboard-list text-3xl mb-2 opacity-30"></i>
                        <p class="text-sm">${formatDate(selectedDate)}에 등록된 할 일이 없습니다.</p>
                    </div>`;
            }

            // 새로운 할 일 추가
            todoForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const text = todoInput.value.trim();
                if (text) {
                    // Get current todos for selected date
                    const todos = getTodosForSelectedDate();
                    // Add new todo
                    todos.push({ text, completed: false });
                    // Save todos for selected date
                    saveTodosForSelectedDate(todos);
                    // Clear input
                    todoInput.value = '';
                    // Update UI
                    renderTodos();
                    renderCalendar(); // Re-render calendar to show indicators
                }
            });

            // 할 일 상태 토글 및 삭제
            todoList.addEventListener('click', (e) => {
                const todos = getTodosForSelectedDate();
                
                if (e.target.type === 'checkbox') {
                    const index = e.target.dataset.index;
                    todos[index].completed = e.target.checked;
                    saveTodosForSelectedDate(todos);
                    renderTodos();
                }

                if (e.target.closest('.delete-btn')) {
                    const index = e.target.closest('.delete-btn').dataset.index;
                    todos.splice(index, 1);
                    saveTodosForSelectedDate(todos);
                    renderTodos();
                    renderCalendar(); // Re-render calendar to update indicators
                }
            });

            // 완료된 항목 모두 삭제
            clearCompleted.addEventListener('click', () => {
                const todos = getTodosForSelectedDate();
                const activeTodos = todos.filter(todo => !todo.completed);
                saveTodosForSelectedDate(activeTodos);
                renderTodos();
                renderCalendar(); // Re-render calendar to update indicators
            });

            // 필터 기능
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-blue-500', 'text-white');
                        btn.classList.add('text-gray-600');
                    });
                    button.classList.add('active', 'bg-blue-500', 'text-white');
                    button.classList.remove('text-gray-600');
                    currentFilter = button.dataset.filter;
                    renderTodos();
                });
            });
        });
    </script>
</body>
</html>