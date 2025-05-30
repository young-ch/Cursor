<?php
require_once __DIR__ . '/../dao/ReservationDAO.php';
require_once __DIR__ . '/../common/functions.php';

class ReservationController {
    private $dao;
    private $rules = [
        'company_name' => ['required' => true, 'max' => 100],
        'representative' => ['required' => true, 'max' => 50],
        'phone' => ['required' => true, 'pattern' => '/^[0-9-]{10,20}$/'],
        'address' => ['required' => true, 'max' => 200],
        'business_number' => ['required' => true, 'pattern' => '/^[0-9-]{10,12}$/'],
        'tax_manager_name' => ['required' => true, 'max' => 50],
        'tax_manager_phone' => ['required' => true, 'pattern' => '/^[0-9-]{10,20}$/'],
        'tax_manager_email' => ['required' => true, 'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        'purpose' => ['required' => true],
        'total_people' => ['required' => true, 'pattern' => '/^[0-9]+$/'],
        'start_date' => ['required' => true, 'pattern' => '/^\d{4}-\d{2}-\d{2}$/'],
        'end_date' => ['required' => true, 'pattern' => '/^\d{4}-\d{2}-\d{2}$/'],
        'total_amount' => ['required' => true, 'pattern' => '/^[0-9]+$/'],
        'contract_amount' => ['required' => true, 'pattern' => '/^[0-9]+$/']
    ];

    public function __construct() {
        $this->dao = new ReservationDAO();
    }

    // 예약 생성 처리
    public function create() {
        try {
            // CSRF 토큰 검증
            if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
                throw new Exception('보안 토큰이 유효하지 않습니다.');
            }

            // 입력값 검증
            $errors = validateInput($_POST, $this->rules);
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }

            // 파일 업로드 처리
            if (isset($_FILES['business_license']) && $_FILES['business_license']['error'] === UPLOAD_ERR_OK) {
                $fileName = uploadFile($_FILES['business_license']);
                if (!$fileName) {
                    throw new Exception('파일 업로드에 실패했습니다.');
                }
                $_POST['business_license'] = $fileName;
            }

            // 예약 데이터 생성
            $result = $this->dao->create($_POST);
            
            return ['success' => true, 'message' => '예약이 성공적으로 등록되었습니다.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // 예약 목록 조회
    public function getList($page = 1, $search = '') {
        try {
            $limit = 10;
            $reservations = $this->dao->getList($page, $limit, $search);
            $totalCount = $this->dao->getTotalCount($search);
            $totalPages = ceil($totalCount / $limit);

            return [
                'success' => true,
                'data' => [
                    'reservations' => $reservations,
                    'pagination' => [
                        'current_page' => $page,
                        'total_pages' => $totalPages,
                        'total_count' => $totalCount
                    ]
                ]
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // 예약 상세 조회
    public function getDetail($id) {
        try {
            $reservation = $this->dao->getDetail($id);
            if (!$reservation) {
                throw new Exception('예약 정보를 찾을 수 없습니다.');
            }

            return ['success' => true, 'data' => $reservation];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // 예약 상태 업데이트
    public function updateStatus($id, $status) {
        try {
            // CSRF 토큰 검증
            if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
                throw new Exception('보안 토큰이 유효하지 않습니다.');
            }

            $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
            if (!in_array($status, $validStatuses)) {
                throw new Exception('유효하지 않은 상태값입니다.');
            }

            $result = $this->dao->updateStatus($id, $status);
            if (!$result) {
                throw new Exception('상태 업데이트에 실패했습니다.');
            }

            return ['success' => true, 'message' => '상태가 성공적으로 업데이트되었습니다.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAllReservations() {
        try {
            return $this->dao->getAllReservations();
        } catch (Exception $e) {
            error_log("Error fetching reservations: " . $e->getMessage());
            return [];
        }
    }

    public function getReservationById($id) {
        try {
            return $this->dao->getDetail($id);
        } catch (Exception $e) {
            error_log("Error fetching reservation: " . $e->getMessage());
            return null;
        }
    }

    public function cancelReservation($id) {
        try {
            return $this->dao->updateStatus($id, 'cancelled');
        } catch (Exception $e) {
            error_log("Error cancelling reservation: " . $e->getMessage());
            return false;
        }
    }
}
?> 