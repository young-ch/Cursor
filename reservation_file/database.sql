-- 예약 테이블 생성
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL COMMENT '기관(업체)명',
    representative VARCHAR(50) NOT NULL COMMENT '대표자',
    phone VARCHAR(20) NOT NULL COMMENT '전화번호',
    address VARCHAR(200) NOT NULL COMMENT '주소',
    business_number VARCHAR(20) NOT NULL COMMENT '사업자등록번호',
    attachment_path VARCHAR(255) COMMENT '첨부파일 경로',
    
    -- 증빙종류 (여러 개 선택 가능)
    doc_tax_invoice BOOLEAN DEFAULT FALSE COMMENT '세금계산서',
    doc_card BOOLEAN DEFAULT FALSE COMMENT '카드',
    doc_cash_receipt BOOLEAN DEFAULT FALSE COMMENT '현금영수증',
    doc_none BOOLEAN DEFAULT FALSE COMMENT '해당없음',
    
    -- 사용범위 (여러 개 선택 가능)
    usage_accommodation BOOLEAN DEFAULT FALSE COMMENT '숙소',
    usage_hall BOOLEAN DEFAULT FALSE COMMENT '강당/강의실',
    usage_facility BOOLEAN DEFAULT FALSE COMMENT '부대시설',
    usage_etc BOOLEAN DEFAULT FALSE COMMENT '기타',
    
    -- 전자세금계산서 담당자 정보
    tax_manager_name VARCHAR(50) NOT NULL COMMENT '담당자 성명',
    tax_manager_phone VARCHAR(20) NOT NULL COMMENT '담당자 휴대폰',
    tax_manager_email VARCHAR(100) NOT NULL COMMENT '담당자 이메일',
    
    purpose VARCHAR(200) NOT NULL COMMENT '사용목적',
    total_people INT NOT NULL COMMENT '사용인원',
    start_date DATE NOT NULL COMMENT '시작일',
    end_date DATE NOT NULL COMMENT '종료일',
    
    -- 사용계획 금액
    total_amount DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT '총사용대금',
    contract_amount DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT '계약금',
    balance_amount DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT '잔금',
    
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') NOT NULL DEFAULT 'pending' COMMENT '예약상태',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='예약 정보'; 