<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>온라인 명함</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #1a1a1a;
            color: white;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: rgba(26, 26, 26, 0.95);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .navbar-brand {
            font-size: 1.25rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-brand img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }
        .navbar-nav {
            display: none;
        }
        .mobile-menu-btn {
            display: block;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }
        .mobile-menu {
            display: none;
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            background-color: rgba(26, 26, 26, 0.95);
            padding: 1rem;
            backdrop-filter: blur(10px);
            z-index: 999;
        }
        .mobile-menu.show {
            display: block;
        }
        .mobile-menu a {
            color: #888;
            text-decoration: none;
            padding: 0.75rem 1rem;
            display: block;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .mobile-menu a:last-child {
            border-bottom: none;
        }
        .mobile-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .container {
            max-width: 1200px;
            margin: 80px auto 0;
            padding: 1rem;
        }
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            text-align: center;
        }
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            margin: 2rem 0;
            text-align: center;
        }
        .profile-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid #333;
        }
        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-info h1 {
            font-size: 2rem;
            margin: 0;
        }
        .profile-info h2 {
            color: #007bff;
            margin: 0.5rem 0;
            font-size: 1.25rem;
        }
        .profile-info h3 {
            font-size: 1.1rem;
            margin: 0.5rem 0;
            color: #888;
        }
        .company-info {
            color: #666;
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .social-links a {
            width: 36px;
            height: 36px;
            background-color: #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .social-links a:hover {
            background-color: #007bff;
        }
        .videos-section {
            margin-top: 2rem;
        }
        .videos-section h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .video-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .video-card {
            background-color: #333;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .video-card:hover {
            transform: translateY(-5px);
        }
        .video-card iframe {
            width: 100%;
            height: 200px;
            border: none;
        }
        .video-info {
            padding: 1rem;
        }
        .video-info h3 {
            margin: 0;
            font-size: 1rem;
        }
        .video-info p {
            margin: 5px 0;
            color: #888;
            font-size: 14px;
        }
        .section-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .books-section {
            margin-top: 3rem;
        }
        .books-section .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 1rem 0;
        }
        .books-section .section-header i.toggle-icon {
            transition: transform 0.3s ease;
        }
        .books-section .section-header.collapsed i.toggle-icon {
            transform: rotate(-180deg);
        }
        .books-grid {
            display: none;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .books-grid.show {
            display: flex;
            flex-direction: column;
        }
        .book-card {
            background: #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 1rem;
            gap: 1.5rem;
        }
        .book-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .book-cover {
            position: relative;
            width: 100px;
            height: 150px;
            flex-shrink: 0;
            overflow: hidden;
            background: linear-gradient(45deg, #2c3e50, #3498db);
            border-radius: 6px;
        }
        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .book-info {
            flex: 1;
            min-width: 0;
            padding: 0;
        }
        .book-title {
            font-size: 1.1rem;
            color: #fff;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        .book-author {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 0.5rem;
        }
        .book-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        .book-links {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .book-links a {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            text-decoration: none;
            color: #fff;
            background: #007bff;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .book-links a:hover {
            background: #0056b3;
        }
        .career-education-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-top: 3rem;
            margin-bottom: 3rem;
        }
        .career-section, .education-section {
            padding: 0;
            margin: 0;
        }
        .timeline-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 2rem;
            position: relative;
            padding-left: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #007bff;
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -4px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #007bff;
        }
        .timeline-date {
            color: #888;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .timeline-content {
            flex: 1;
            min-width: 0;
        }
        .timeline-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        .timeline-org {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: #888;
        }
        .timeline-org img {
            width: 24px;
            height: 24px;
            border-radius: 4px;
        }
        .timeline-desc {
            font-size: 0.9rem;
            line-height: 1.5;
            color: #666;
        }
        .contacts-section {
            margin-top: 3rem;
            padding: 2rem 0;
            background: #2a2a2a;
        }
        .contacts-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .contact-form {
            background: #333;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #fff;
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #444;
            border-radius: 6px;
            background: #2a2a2a;
            color: #fff;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #007bff;
        }
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        .submit-btn {
            width: 100%;
            background: #007bff;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .submit-btn:hover {
            background: #0056b3;
        }
        .submit-btn:disabled {
            background: #666;
            cursor: not-allowed;
        }
        .map-container {
            background: #333;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .map-frame {
            width: 100%;
            height: 250px;
            border: none;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .address-info {
            color: #fff;
        }
        .address-info h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #007bff;
        }
        .address-info p {
            margin-bottom: 0.8rem;
            color: #888;
            line-height: 1.6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .address-info i {
            min-width: 20px;
            color: #007bff;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .alert-success {
            background: #28a745;
            color: #fff;
        }
        .alert-error {
            background: #dc3545;
            color: #fff;
        }
        .navigation-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .nav-button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
            border: none;
            border-radius: 10px;
            color: #333;
            font-size: 1.1em;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 5px 5px 15px rgba(0,0,0,0.1),
                        -5px -5px 15px rgba(255,255,255,0.8);
        }
        .nav-button:hover {
            transform: translateY(-3px);
            box-shadow: 7px 7px 20px rgba(0,0,0,0.15),
                        -7px -7px 20px rgba(255,255,255,0.9);
            color: #007bff;
        }
        .nav-button i {
            font-size: 1.2em;
        }
        /* 태블릿 이상 화면에서의 스타일 */
        @media (min-width: 768px) {
            .navbar {
                padding: 1rem 2rem;
            }
            .navbar-brand {
                font-size: 1.5rem;
            }
            .navbar-brand img {
                width: 40px;
                height: 40px;
            }
            .mobile-menu-btn {
                display: none;
            }
            .navbar-nav {
                display: flex;
                gap: 2rem;
                align-items: center;
            }
            .nav-item {
                position: relative;
            }
            .nav-link {
                color: #888;
                text-decoration: none;
                font-size: 1rem;
                transition: color 0.3s ease;
                position: relative;
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 0.5rem 1rem;
            }
            .nav-link:hover {
                color: white;
            }
            .dropdown-content {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                background-color: rgba(26, 26, 26, 0.95);
                min-width: 180px;
                box-shadow: 0 8px 16px rgba(0,0,0,0.2);
                border-radius: 8px;
                backdrop-filter: blur(10px);
            }
            .dropdown:hover .dropdown-content {
                display: block;
            }
            .profile-section {
                flex-direction: row;
                text-align: left;
                gap: 50px;
            }
            .profile-image {
                width: 300px;
                height: 300px;
            }
            .header {
                flex-direction: row;
                justify-content: space-between;
                text-align: left;
            }
            .profile-info h1 {
                font-size: 48px;
            }
            .profile-info h2 {
                font-size: 1.5rem;
            }
            .profile-info h3 {
                font-size: 24px;
            }
            .social-links {
                justify-content: flex-start;
            }
            .video-grid {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
            }
            .videos-section h2 {
                text-align: left;
                font-size: 24px;
            }
            .book-cover {
                width: 120px;
                height: 180px;
            }
            .book-title {
                font-size: 1.2rem;
            }
            .book-description {
                font-size: 1rem;
            }
            .career-education-row {
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
            }
            .section-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }
            .timeline-item {
                flex-direction: row;
                gap: 1.5rem;
            }
            .timeline-date {
                min-width: 120px;
            }
            .book-links a {
                flex: 0 1 auto;
            }
            .contacts-container {
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                padding: 0 2rem;
            }
            .contact-form, .map-container {
                padding: 2rem;
            }
            .map-frame {
                height: 300px;
            }
            .submit-btn {
                width: auto;
                padding: 1rem 2rem;
            }
            .books-grid {
                display: none;
                gap: 1.5rem;
            }
            .books-grid.show {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
            }
            .book-card {
                flex-direction: column;
                padding: 0;
                gap: 0;
            }
            .book-cover {
                width: 100%;
                height: auto;
                padding-top: 140%;
                position: relative;
                border-radius: 12px 12px 0 0;
            }
            .book-cover img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                transition: transform 0.3s ease;
            }
            .book-card:hover .book-cover img {
                transform: scale(1.05);
            }
            .book-info {
                padding: 1rem;
            }
            .book-title {
                font-size: 1rem;
                height: 2.8em;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                line-clamp: 2;
                -webkit-box-orient: vertical;
            }
            .book-description {
                font-size: 0.85rem;
                height: 3.6em;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
            }
            .book-links a {
                flex: 1;
                justify-content: center;
                min-width: 120px;
            }
        }
        /* 데스크톱 화면에서의 스타일 */
        @media (min-width: 1024px) {
            .books-grid.show {
                grid-template-columns: repeat(6, 1fr);
            }
            .book-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            }
        }
        .features-section {
            margin-top: 3rem;
            padding: 2rem 0;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .feature-card {
            background-color: #333;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            background-color: #444;
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background-color: #007bff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .feature-icon i {
            font-size: 1.5rem;
        }
        
        .feature-card h3 {
            margin: 0.5rem 0;
            font-size: 1.2rem;
        }
        
        .feature-card p {
            color: #888;
            font-size: 0.9rem;
            margin: 0;
        }

        /* 예약 버튼 스타일 */
        .reservation-button {
            margin-top: 2rem;
            text-align: center;
        }

        .reservation-button a {
            display: inline-block;
            padding: 15px 40px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .reservation-button a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .reservation-button a i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <?php
    function getYoutubeEmbedUrl($url) {
        $video_id = '';
        if (preg_match('/[?&]v=([^&#]+)/', $url, $matches)) {
            $video_id = $matches[1];
        } elseif (preg_match('/youtu.be\/([^\/\?]+)/', $url, $matches)) {
            $video_id = $matches[1];
        }
        return 'https://www.youtube.com/embed/' . $video_id;
    }

    function getYoutubeThumbnail($url) {
        $video_id = '';
        if (preg_match('/[?&]v=([^&#]+)/', $url, $matches)) {
            $video_id = $matches[1];
        } elseif (preg_match('/youtu.be\/([^\/\?]+)/', $url, $matches)) {
            $video_id = $matches[1];
        }
        return 'https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
    }

    $profile = [
        'name' => '최영철',
        'name_multi' => '최영철 • 崔永哲 • チェ・ヨンチョル',
        'title' => 'Prompt Engineer',
        'company' => 'younlab(주)',
        'department' => '인공지능팀',
        'image' => 'images\시안.png',
        'social' => [
            'kakao' => '#',
            'linkedin' => '#',
            'facebook' => '#',
            'instagram' => '#',
            'twitter' => '#'
        ]
    ];

    $videos = [
        [
            'url' => 'https://www.youtube.com/watch?v=6QV1rShe13A',
            'title' => '미국이 자원 전쟁에서 중국에 항상 지는 이유',
            'date' => '2024.2'
        ],
        [
            'url' => 'https://www.youtube.com/watch?v=6tPx2OPT8q0',
            'title' => '태슬라댄스',
            'date' => '2023.10'
        ],
        [
            'url' => 'https://www.youtube.com/watch?v=esNASmRZ_qg',
            'title' => 'IMF두번째 경고',
            'date' => '2023.7'
        ]
    ];

    $books = [
        [
            'title' => '프롬프트 엔지니어링의 정석',
            'author' => '최영철',
            'description' => 'AI 시대의 필수 역량, 프롬프트 엔지니어링의 모든 것을 담은 실전 가이드',
            'cover' => 'https://contents.kyobobook.co.kr/sih/fit-in/458x0/pdt/9791169211567.jpg',
            'links' => [
                ['text' => '교보문고', 'url' => '#', 'icon' => 'fa-book'],
                ['text' => 'YES24', 'url' => '#', 'icon' => 'fa-shopping-cart']
            ]
        ],
        [
            'title' => 'ChatGPT 프롬프트 엔지니어링',
            'author' => '최영철',
            'description' => 'ChatGPT를 200% 활용하는 프롬프트 작성법과 실전 예제',
            'cover' => 'https://contents.kyobobook.co.kr/sih/fit-in/458x0/pdt/9791191905656.jpg',
            'links' => [
                ['text' => '교보문고', 'url' => '#', 'icon' => 'fa-book'],
                ['text' => 'YES24', 'url' => '#', 'icon' => 'fa-shopping-cart']
            ]
        ],
        [
            'title' => 'AI 시대의 프롬프트 디자인',
            'author' => '최영철',
            'description' => '효과적인 프롬프트 설계와 최적화를 위한 디자인 패턴',
            'cover' => 'https://contents.kyobobook.co.kr/sih/fit-in/458x0/pdt/9791158393779.jpg',
            'links' => [
                ['text' => '교보문고', 'url' => '#', 'icon' => 'fa-book'],
                ['text' => 'YES24', 'url' => '#', 'icon' => 'fa-shopping-cart']
            ]
        ],
        [
            'title' => '실전 프롬프트 엔지니어링',
            'author' => '최영철',
            'description' => '현업에서 바로 적용하는 프롬프트 엔지니어링 실무 테크닉',
            'cover' => 'https://contents.kyobobook.co.kr/sih/fit-in/458x0/pdt/9791191600674.jpg',
            'links' => [
                ['text' => '교보문고', 'url' => '#', 'icon' => 'fa-book'],
                ['text' => 'YES24', 'url' => '#', 'icon' => 'fa-shopping-cart']
            ]
        ],
        [
            'title' => 'AI 프롬프트 최적화 전략',
            'author' => '최영철',
            'description' => 'LLM 성능을 극대화하는 프롬프트 최적화 기법',
            'cover' => 'https://contents.kyobobook.co.kr/sih/fit-in/458x0/pdt/9791161755663.jpg',
            'links' => [
                ['text' => '교보문고', 'url' => '#', 'icon' => 'fa-book'],
                ['text' => 'YES24', 'url' => '#', 'icon' => 'fa-shopping-cart']
            ]
        ],
        [
            'title' => '프롬프트 엔지니어링 워크북',
            'author' => '최영철',
            'description' => '단계별 실습으로 배우는 프롬프트 엔지니어링',
            'cover' => 'https://contents.kyobobook.co.kr/sih/fit-in/458x0/pdt/9791191600674.jpg',
            'links' => [
                ['text' => '교보문고', 'url' => '#', 'icon' => 'fa-book'],
                ['text' => 'YES24', 'url' => '#', 'icon' => 'fa-shopping-cart']
            ]
        ]
    ];

    $careers = [
        [
            'period' => '1592 - 현재',
            'title' => '포도대장',
            'organization' => '조선 한성부 포도청',
            'org_logo' => 'https://via.placeholder.com/30x30',
            'description' => '한양 도성 내 치안 유지 및 순찰 총괄 책임자'
        ],
        [
            'period' => '1590 - 1592',
            'title' => '훈련원 교관',
            'organization' => '조선 훈련원',
            'org_logo' => 'https://via.placeholder.com/30x30',
            'description' => '무예 교육 및 군사 훈련 담당'
        ]
    ];

    $education = [
        [
            'period' => '1585 - 1589',
            'title' => '성균관 유생',
            'organization' => '성균관',
            'org_logo' => 'https://via.placeholder.com/30x30',
            'description' => '문무 양반의 길 수학'
        ],
        [
            'period' => '1580 - 1585',
            'title' => '향교 교육',
            'organization' => '한성부 향교',
            'org_logo' => 'https://via.placeholder.com/30x30',
            'description' => '사서삼경 및 기초 무예 수학'
        ]
    ];
    ?>

    <nav class="navbar">
        <a href="#" class="navbar-brand">
            <img src="<?php echo $profile['image']; ?>" alt="Profile">
            <?php echo $profile['name']; ?>
        </a>
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="navbar-nav">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link">
                    <i class="fas fa-folder"></i>
                    포트폴리오
                </a>
                <div class="dropdown-content">
                    <a href="#"><i class="fas fa-code"></i> 웹 개발</a>
                    <a href="#"><i class="fas fa-mobile-alt"></i> 앱 개발</a>
                    <a href="#"><i class="fas fa-paint-brush"></i> 디자인</a>
                </div>
            </div>
            <div class="nav-item">
                <a href="todo.php" class="nav-link">
                    <i class="fas fa-tasks"></i>
                    To Do List
                </a>
            </div>
            <div class="nav-item">
                <a href="#contact" class="nav-link">
                    <i class="fas fa-envelope"></i>
                    연락하기
                </a>
            </div>
        </div>
    </nav>

    <div class="mobile-menu" id="mobileMenu">
        <a href="#profile">프로필</a>
        <a href="#videos">영상</a>
        <a href="todo.php">To Do List</a>
        <a href="survey.php">설문지</a>
    </div>

    <div class="container">
        <div class="navigation-buttons">
            <a href="kpop.html" class="nav-button">
                <i class="fas fa-music"></i>
                K-POP
            </a>
            <a href="saemaul.html" class="nav-button">
                <i class="fas fa-seedling"></i>
                새마을운동
            </a>
            <a href="index_back.php" class="nav-button">
                <i class="fas fa-car"></i>
                운전기록일지
            </a>
            <a href="apple_landing.php" class="nav-button">
                <i class="fas fa-apple-alt"></i>
                프리미엄 사과
            </a>
            <a href="todo.php" class="nav-button">
                <i class="fas fa-tasks"></i>
                To Do List
            </a>
            <a href="survey.php" class="nav-button">
                <i class="fas fa-tasks"></i>
                설문지
            </a>
        </div>
        <main>
            <section id="intro" class="profile-section">
                <div class="profile-image">
                    <img src="<?php echo $profile['image']; ?>" alt="Profile Image">
                </div>
                <div class="profile-info">
                    <h2><?php echo $profile['title']; ?></h2>
                    <h1><?php echo $profile['name']; ?></h1>
                    <h3><?php echo $profile['name_multi']; ?></h3>
                    <div class="company-info">
                        <?php echo $profile['company']; ?> • <?php echo $profile['department']; ?>
                    </div>
                    <div class="social-links">
                        <a href="<?php echo $profile['social']['kakao']; ?>" target="_blank">K</a>
                        <a href="<?php echo $profile['social']['linkedin']; ?>" target="_blank">in</a>
                        <a href="<?php echo $profile['social']['facebook']; ?>" target="_blank">f</a>
                        <a href="<?php echo $profile['social']['instagram']; ?>" target="_blank">I</a>
                        <a href="<?php echo $profile['social']['twitter']; ?>" target="_blank">X</a>
                    </div>
                </div>
            </section>

            <section id="videos" class="videos-section">
                <h2><i class="fas fa-video"></i> Videos</h2>
                <div class="video-grid">
                    <?php
                    foreach ($videos as $video) {
                        $embed_url = getYoutubeEmbedUrl($video['url']);
                        echo '<div class="video-card">';
                        echo '<iframe src="' . $embed_url . '" title="' . $video['title'] . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                        echo '<div class="video-info">';
                        echo '<h3>' . $video['title'] . '</h3>';
                        echo '<p>' . $video['date'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>

            <section id="books" class="books-section">
                <h2 class="section-title">
                    <i class="fas fa-book"></i>
                    Books
                </h2>
                <div class="books-section section-header">
                    <span>Books</span>
                    <i class="fas fa-angle-down toggle-icon"></i>
                </div>
                <div class="books-grid">
                    <?php foreach ($books as $book): ?>
                        <div class="book-card">
                            <div class="book-cover">
                                <img src="<?php echo $book['cover']; ?>" alt="<?php echo $book['title']; ?>">
                            </div>
                            <div class="book-info">
                                <h3 class="book-title"><?php echo $book['title']; ?></h3>
                                <div class="book-author"><?php echo $book['author']; ?></div>
                                <p class="book-description"><?php echo $book['description']; ?></p>
                                <div class="book-links">
                                    <?php foreach ($book['links'] as $link): ?>
                                        <a href="<?php echo $link['url']; ?>" target="_blank">
                                            <i class="fas <?php echo $link['icon']; ?>"></i>
                                            <?php echo $link['text']; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="career-education-row">
                <section id="careers" class="career-section">
                    <h2 class="section-title">
                        <i class="fas fa-briefcase"></i>
                        Careers
                    </h2>
                    <?php foreach ($careers as $career): ?>
                        <div class="timeline-item">
                            <div class="timeline-date"><?php echo $career['period']; ?></div>
                            <div class="timeline-content">
                                <h3 class="timeline-title"><?php echo $career['title']; ?></h3>
                                <div class="timeline-org">
                                    <img src="<?php echo $career['org_logo']; ?>" alt="<?php echo $career['organization']; ?>">
                                    <span><?php echo $career['organization']; ?></span>
                                </div>
                                <p class="timeline-desc"><?php echo $career['description']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>

                <section id="education" class="education-section">
                    <h2 class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Education
                    </h2>
                    <?php foreach ($education as $edu): ?>
                        <div class="timeline-item">
                            <div class="timeline-date"><?php echo $edu['period']; ?></div>
                            <div class="timeline-content">
                                <h3 class="timeline-title"><?php echo $edu['title']; ?></h3>
                                <div class="timeline-org">
                                    <img src="<?php echo $edu['org_logo']; ?>" alt="<?php echo $edu['organization']; ?>">
                                    <span><?php echo $edu['organization']; ?></span>
                                </div>
                                <p class="timeline-desc"><?php echo $edu['description']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>
            </div>

            <section id="contacts" class="contacts-section">
                <div class="contacts-container">
                    <div class="contact-form">
                        <h2 class="section-title">
                            <i class="fas fa-envelope"></i>
                            Contact Me
                        </h2>
                        <div class="alert alert-success" id="success-message"></div>
                        <div class="alert alert-error" id="error-message"></div>
                        <form id="contact-form" action="send_mail.php" method="POST">
                            <div class="form-group">
                                <label for="name">이름</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">이메일</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="subject">제목</label>
                                <input type="text" id="subject" name="subject" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="message">메시지</label>
                                <textarea id="message" name="message" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="submit-btn" id="submit-btn">
                                <i class="fas fa-paper-plane"></i>
                                메시지 보내기
                            </button>
                        </form>
                    </div>

                    <div class="map-container">
                        <h2 class="section-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Location
                        </h2>
                        <iframe 
                            class="map-frame"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3169.893723543024!2d127.15560937547914!3d37.392345534098006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x357ca4394960fa3b%3A0x532b7c27155330f5!2z7IOI66eI7J2E7Jq064-Z7KSR7JWZ7ZqM!5e0!3m2!1sko!2skr!4v1745394011110!5m2!1sko!2skr"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <div class="address-info">
                            <h3>Office Location</h3>
                            <p>
                                <i class="fas fa-map-marker-alt"></i>
                                서울특별시 강남구 테헤란로 341
                            </p>
                            <p>
                                <i class="fas fa-phone"></i>
                                02-1234-5678
                            </p>
                            <p>
                                <i class="fas fa-envelope"></i>
                                contact@younlab.com
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="features-section">
                <h2 class="section-title">기능</h2>
                <div class="features-grid">
                    <a href="todo.php" class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3>To Do List</h3>
                        <p>할 일을 관리하고 체크하세요</p>
                    </a>
                    <a href="survey.php" class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3>설문지</h3>
                        <p>고객 만족도 설문에 참여하세요</p>
                    </a>
                    <a href="converter.php" class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-powerpoint"></i>
                        </div>
                        <h3>변환</h3>
                        <p>HTML을 PPT로 변환하세요</p>
                    </a>
                </div>
            </div>

            <!-- 관리자 링크 -->
            <div class="text-center mt-8 mb-4">
                <a href="admin.php" class="text-gray-400 hover:text-gray-600 text-sm transition-colors">
                    <i class="fas fa-lock"></i> 관리자
                </a>
            </div>

            <!-- 예약 버튼 추가 -->
            <div class="reservation-button">
                <a href="reservation_file/reservation.php">
                    <i class="fas fa-calendar-check"></i>
                    온라인 예약하기
                </a>
            </div>
            <div class="container">
                <a href="#" class="simple-button">시설 사용 신청</a>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submit-btn');
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        
        // 버튼 비활성화
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 전송 중...';
        
        // 이전 메시지 숨기기
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';

        // 폼 데이터 수집
        const formData = new FormData(form);

        // Ajax 요청
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                successMessage.textContent = data.message;
                successMessage.style.display = 'block';
                form.reset();
            } else {
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
            }
        })
        .catch(error => {
            errorMessage.textContent = '오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
            errorMessage.style.display = 'block';
        })
        .finally(() => {
            // 버튼 상태 복구
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> 메시지 보내기';
        });
    });

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('show');
    }

    // Books 섹션 토글 기능
    document.addEventListener('DOMContentLoaded', function() {
        const booksHeader = document.querySelector('.books-section .section-header');
        const booksGrid = document.querySelector('.books-grid');
        
        // 초기 상태 설정 (기본적으로 펼쳐진 상태)
        booksGrid.classList.add('show');
        
        booksHeader.addEventListener('click', function() {
            booksGrid.classList.toggle('show');
            this.classList.toggle('collapsed');
        });
    });
    </script>
</body>
</html>
