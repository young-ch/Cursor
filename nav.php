<?php
// 현재 페이지 URL을 가져옴
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="bg-apple-red">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between md:justify-start md:space-x-8">
            <div class="text-white text-2xl font-bold">
                <a href="apple_landing.php">맛있는 사과</a>
            </div>
            <!-- 햄버거 메뉴 버튼 -->
            <button class="md:hidden text-white focus:outline-none" onclick="toggleMenu()">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <!-- 데스크톱 메뉴 -->
            <div class="hidden md:flex space-x-8">
                <?php if ($current_page === 'apple_landing.php'): ?>
                    <a href="#products" class="text-white hover:text-apple-light transition">제품</a>
                    <a href="#features" class="text-white hover:text-apple-light transition">특징</a>
                    <a href="#contact" class="text-white hover:text-apple-light transition">문의</a>
                <?php else: ?>
                    <a href="apple_landing.php#products" class="text-white hover:text-apple-light transition">제품</a>
                    <a href="apple_landing.php#features" class="text-white hover:text-apple-light transition">특징</a>
                    <a href="apple_landing.php#contact" class="text-white hover:text-apple-light transition">문의</a>
                <?php endif; ?>
            </div>
            <div class="hidden md:flex ml-auto space-x-8">
                <a href="products.php" class="text-white hover:text-apple-light transition">제품 소개</a>
                <a href="index.php" class="text-white hover:text-apple-light transition">홈으로</a>
            </div>
        </div>
        <!-- 모바일 메뉴 -->
        <div id="mobileMenu" class="mobile-menu bg-apple-dark md:hidden mt-4 rounded-lg p-4">
            <div class="flex flex-col space-y-3">
                <?php if ($current_page === 'apple_landing.php'): ?>
                    <a href="#products" class="text-white hover:text-apple-light transition">제품</a>
                    <a href="#features" class="text-white hover:text-apple-light transition">특징</a>
                    <a href="#contact" class="text-white hover:text-apple-light transition">문의</a>
                <?php else: ?>
                    <a href="apple_landing.php#products" class="text-white hover:text-apple-light transition">제품</a>
                    <a href="apple_landing.php#features" class="text-white hover:text-apple-light transition">특징</a>
                    <a href="apple_landing.php#contact" class="text-white hover:text-apple-light transition">문의</a>
                <?php endif; ?>
                <hr class="border-apple-light opacity-20">
                <a href="products.php" class="text-white hover:text-apple-light transition">제품 소개</a>
                <a href="index.php" class="text-white hover:text-apple-light transition">홈으로</a>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('show');
    }
</script> 