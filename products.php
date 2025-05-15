<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>제품 소개 | 프리미엄 사과</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'apple-red': '#DC2626',
                        'apple-dark': '#991B1B',
                        'apple-light': '#FEE2E2',
                    },
                    fontFamily: {
                        sans: ['Noto Sans KR', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Noto Sans KR', sans-serif;
        }
        .mobile-menu {
            display: none;
        }
        .mobile-menu.show {
            display: block;
        }
    </style>
</head>
<body class="bg-white">
    <?php
    $products = [
        [
            'name' => '프리미엄 홍로',
            'description' => '달콤한 향과 아삭한 식감의 완벽한 조화',
            'price' => '35,000',
            'weight' => '3kg',
            'image' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6',
            'details' => [
                '당도: 14-15 Brix',
                '크기: 중대과(300g 내외)',
                '보관: 0-5도 냉장보관',
                '특징: 단단하고 과즙이 풍부'
            ]
        ],
        [
            'name' => '유기농 양광',
            'description' => '무농약 재배로 더욱 건강한 맛',
            'price' => '42,000',
            'weight' => '3kg',
            'image' => 'https://images.unsplash.com/photo-1579613832125-5d34a13ffe2a',
            'details' => [
                '당도: 13-14 Brix',
                '크기: 중과(250g 내외)',
                '보관: 0-5도 냉장보관',
                '특징: 부드럽고 달콤한 맛'
            ]
        ],
        [
            'name' => '명품 부사',
            'description' => '진한 단맛과 풍부한 과즙',
            'price' => '38,000',
            'weight' => '3kg',
            'image' => 'https://images.unsplash.com/photo-1570913149827-d2ac84ab3f9a',
            'details' => [
                '당도: 15-16 Brix',
                '크기: 대과(320g 내외)',
                '보관: 0-5도 냉장보관',
                '특징: 단단하고 아삭한 식감'
            ]
        ]
    ];
    ?>

    <!-- 네비게이션 -->
    <?php include 'nav.php'; ?>

    <!-- 제품 상세 섹션 -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl font-bold text-center text-gray-800 mb-16">프리미엄 사과 상세 소개</h1>
            
            <?php foreach ($products as $index => $product): ?>
            <div class="mb-20">
                <div class="flex flex-col md:flex-row <?php echo $index % 2 === 0 ? '' : 'md:flex-row-reverse'; ?> items-center gap-12">
                    <div class="w-full md:w-1/2">
                        <div class="rounded-2xl overflow-hidden shadow-xl">
                            <img src="<?php echo $product['image']; ?>" 
                                 alt="<?php echo $product['name']; ?>" 
                                 class="w-full h-[400px] object-cover">
                        </div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-800 mb-4"><?php echo $product['name']; ?></h2>
                        <p class="text-xl text-gray-600 mb-6"><?php echo $product['description']; ?></p>
                        <div class="bg-apple-light rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-apple-red mb-4">상품 정보</h3>
                            <ul class="space-y-2">
                                <?php foreach ($product['details'] as $detail): ?>
                                <li class="flex items-center">
                                    <span class="material-icons text-apple-red mr-2">check_circle</span>
                                    <?php echo $detail; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-apple-red">￦<?php echo $product['price']; ?></span>
                                <span class="text-gray-500 ml-2"><?php echo $product['weight']; ?></span>
                            </div>
                            <button class="bg-apple-red text-white px-8 py-3 rounded-full hover:bg-apple-dark transition">
                                구매하기
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 푸터 -->
    <footer class="bg-gray-900 text-white py-12">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <span class="material-icons mr-2">agriculture</span>
                        맛있는 사과
                    </h3>
                    <p class="text-gray-400">
                        자연과 함께 키워낸<br>
                        최고급 프리미엄 사과를 제공합니다.
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <span class="material-icons mr-2">support_agent</span>
                        연락처
                    </h3>
                    <p class="text-gray-400">
                        전화: 1544-0000<br>
                        이메일: contact@premium-apple.com<br>
                        주소: 경북 청송군 청송읍 과수원길 123
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <span class="material-icons mr-2">share</span>
                        팔로우
                    </h3>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <span class="material-icons">facebook</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <span class="material-icons">photo_camera</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <span class="material-icons">smart_display</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <span class="material-icons">flutter_dash</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                &copy; 2024 Premium Apple. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html> 