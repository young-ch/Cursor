<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>프리미엄 사과 | 자연의 달콤함</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'apple-red': '#DC2626',
                        'apple-dark': '#991B1B',
                        'apple-light': '#FEE2E2',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white">
    <?php
    $products = [
        [
            'name' => '프리미엄 홍로',
            'description' => '달콤한 향과 아삭한 식감의 완벽한 조화',
            'price' => '35,000',
            'weight' => '3kg',
            'image' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6'
        ],
        [
            'name' => '유기농 양광',
            'description' => '무농약 재배로 더욱 건강한 맛',
            'price' => '42,000',
            'weight' => '3kg',
            'image' => 'https://images.unsplash.com/photo-1579613832125-5d34a13ffe2a'
        ],
        [
            'name' => '명품 부사',
            'description' => '진한 단맛과 풍부한 과즙',
            'price' => '38,000',
            'weight' => '3kg',
            'image' => 'https://images.unsplash.com/photo-1570913149827-d2ac84ab3f9a'
        ]
    ];

    $features = [
        [
            'icon' => '🌱',
            'title' => '친환경 재배',
            'description' => '무농약 친환경 재배로 안전하고 건강한 사과를 제공합니다.'
        ],
        [
            'icon' => '🚚',
            'title' => '당일 배송',
            'description' => '수확 후 24시간 이내 배송으로 가장 신선한 상태를 유지합니다.'
        ],
        [
            'icon' => '💝',
            'title' => '선물 포장',
            'description' => '고급스러운 선물 포장 서비스를 무료로 제공합니다.'
        ]
    ];
    ?>

    <!-- 네비게이션 -->
    <nav class="bg-apple-red">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="text-white text-2xl font-bold">
                    Premium Apple
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#products" class="text-white hover:text-apple-light transition">제품</a>
                    <a href="#features" class="text-white hover:text-apple-light transition">특징</a>
                    <a href="#contact" class="text-white hover:text-apple-light transition">문의</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 히어로 섹션 -->
    <div class="relative bg-apple-red py-16">
        <div class="absolute inset-0 overflow-hidden">
            <div class="bg-gradient-to-r from-apple-dark to-apple-red opacity-50 absolute inset-0"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex items-center justify-between">
                <div class="w-1/2 pr-8">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        자연이 선물한<br>최고의 달콤함
                    </h1>
                    <p class="text-xl text-apple-light mb-8">
                        청정 자연에서 정성껏 키운 프리미엄 사과를 소개합니다.<br>
                        최상의 품질과 맛을 보장하는 프리미엄 과일 브랜드입니다.
                    </p>
                    <a href="#products" 
                       class="inline-block bg-white text-apple-red px-8 py-4 rounded-full font-bold hover:bg-apple-light transition">
                        제품 보기
                    </a>
                </div>
                <div class="w-1/2 flex justify-center">
                    <div class="w-[300px] h-[300px] rounded-full overflow-hidden shadow-2xl border-4 border-white">
                        <img src="images/premium_apple.jpg" 
                             alt="프리미엄 사과" 
                             class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 제품 섹션 -->
    <section id="products" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-16">프리미엄 사과</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition">
                    <img src="<?php echo $product['image']; ?>" 
                         alt="<?php echo $product['name']; ?>" 
                         class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo $product['name']; ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo $product['description']; ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-apple-red font-bold text-xl">￦<?php echo $product['price']; ?></span>
                            <span class="text-gray-500"><?php echo $product['weight']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 특징 섹션 -->
    <section id="features" class="py-20 bg-apple-light">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-16">특별한 가치</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <?php foreach ($features as $feature): ?>
                <div class="text-center">
                    <div class="text-5xl mb-4"><?php echo $feature['icon']; ?></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo $feature['title']; ?></h3>
                    <p class="text-gray-600"><?php echo $feature['description']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 문의 섹션 -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-4xl font-bold text-center text-gray-800 mb-16">문의하기</h2>
                <form class="space-y-6">
                    <div>
                        <label class="block text-gray-700 mb-2" for="name">이름</label>
                        <input type="text" id="name" name="name" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-apple-red focus:ring-2 focus:ring-apple-red focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2" for="email">이메일</label>
                        <input type="email" id="email" name="email" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-apple-red focus:ring-2 focus:ring-apple-red focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2" for="message">메시지</label>
                        <textarea id="message" name="message" rows="4" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-apple-red focus:ring-2 focus:ring-apple-red focus:outline-none"></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-apple-red text-white font-bold py-4 px-8 rounded-lg hover:bg-apple-dark transition">
                        문의하기
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- 푸터 -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div>
                    <h3 class="text-xl font-bold mb-4">Premium Apple</h3>
                    <p class="text-gray-400">최고의 품질과 서비스로<br>고객님께 만족을 드립니다.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">연락처</h3>
                    <p class="text-gray-400">
                        전화: 1544-0000<br>
                        이메일: contact@premium-apple.com
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">운영시간</h3>
                    <p class="text-gray-400">
                        평일: 09:00 - 18:00<br>
                        주말 및 공휴일 휴무
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                &copy; 2024 Premium Apple. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html> 