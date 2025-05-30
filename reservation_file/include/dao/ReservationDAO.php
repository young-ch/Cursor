<?php
require_once __DIR__ . '/../common/db_connect.php';

class ReservationDAO {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create($data) {
        try {
            // 이메일 주소 조합
            $tax_manager_email = $_POST["email_id"] ?? ""; '@' . $_POST["email_domain"];

            $sql = "INSERT INTO reservations (
                company_name, representative, phone, address, business_number,
                tax_manager_name, tax_manager_phone, tax_manager_email,
                purpose, total_people, start_date, end_date,
                total_amount, contract_amount, balance_amount,
                doc_tax_invoice, doc_card, doc_cash_receipt, doc_none,
                usage_accommodation, usage_hall, usage_facility, usage_etc,
                status, created_at
            ) VALUES (
                :company_name, :representative, :phone, :address, :business_number,
                :tax_manager_name, :tax_manager_phone, :tax_manager_email,
                :purpose, :total_people, :start_date, :end_date,
                :total_amount, :contract_amount, :balance_amount,
                :doc_tax_invoice, :doc_card, :doc_cash_receipt, :doc_none,
                :usage_accommodation, :usage_hall, :usage_facility, :usage_etc,
                :status, CURRENT_TIMESTAMP
            )";

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'company_name' =>  $_POST["company_name"],
                'representative' =>  $_POST["representative"],
                'phone' =>  $_POST["phone"],
                'address' =>  $_POST["address"],
                'business_number' =>  $_POST["business_number"],
                'tax_manager_name' =>  $_POST["tax_manager_name"],
                'tax_manager_phone' => $_POST["tax_manager_phone"],
                'tax_manager_email' => $tax_manager_email,
                'purpose' =>  $_POST["purpose"] ?? '',
                'total_people' => $_POST["total_people"] ?? 0,
                'start_date' => $_POST["start_date"],
                'end_date' => $_POST["end_date"],
                'total_amount' => str_replace(',', '',  $_POST["total_amount"]),
              //  'contract_amount' => str_replace(',', '', $_POST["contract_amount"]),
             //   'balance_amount' => str_replace(',', '', $_POST["balance_amount"]),
                'doc_tax_invoice' => isset($_POST["doc_tax_invoice"]) ? 1 : 0,
                'doc_card' => isset($_POST["doc_card"]) ? 1 : 0,
                'doc_cash_receipt' => isset($_POST["doc_cash_receipt"]) ? 1 : 0,
                'doc_none' => isset($_POST["doc_none"]) ? 1 : 0,
                'usage_accommodation' => isset($_POST["usage_accommodation"]) ? 1 : 0,
                'usage_hall' => isset($_POST["usage_hall"]) ? 1 : 0,
                'usage_facility' => isset($_POST["usage_facility"]) ? 1 : 0,
                'usage_etc' => isset($_POST["usage_etc"]) ? 1 : 0,
                'status' => $_POST["status"] ?? 'pending'
            ]);

            if ($result) {
                return $this->db->lastInsertId();
            }
            return false;

        } catch (PDOException $e) {
            error_log("Error creating reservation: " . $e->getMessage());
            throw new Exception("예약 저장 중 오류가 발생했습니다.");
        }
    }

    public function getAllReservations() {
        try {
            $sql = "SELECT * FROM reservations ORDER BY created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all reservations: " . $e->getMessage());
            throw new Exception("예약 목록 조회 중 오류가 발생했습니다.");
        }
    }

    public function getDetail($id) {
        try {
            $sql = "SELECT * FROM reservations WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching reservation detail: " . $e->getMessage());
            throw new Exception("예약 상세 정보 조회 중 오류가 발생했습니다.");
        }
    }

    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE reservations 
                    SET status = :status, 
                        updated_at = CURRENT_TIMESTAMP 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'status' => $status
            ]);
        } catch (PDOException $e) {
            error_log("Error updating reservation status: " . $e->getMessage());
            throw new Exception("예약 상태 업데이트 중 오류가 발생했습니다.");
        }
    }

    public function getList($page = 1, $limit = 10, $search = '') {
        try {
            $offset = ($page - 1) * $limit;
            $params = [];
            $whereClause = '';

            if (!empty($search)) {
                $whereClause = "WHERE company_name LIKE :search 
                               OR representative LIKE :search 
                               OR phone LIKE :search";
                $params['search'] = "%{$search}%";
            }

            $sql = "SELECT * FROM reservations 
                    {$whereClause}
                    ORDER BY created_at DESC 
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching reservation list: " . $e->getMessage());
            throw new Exception("예약 목록 조회 중 오류가 발생했습니다.");
        }
    }

    public function getTotalCount($search = '') {
        try {
            $params = [];
            $whereClause = '';

            if (!empty($search)) {
                $whereClause = "WHERE company_name LIKE :search 
                               OR representative LIKE :search 
                               OR phone LIKE :search";
                $params['search'] = "%{$search}%";
            }

            $sql = "SELECT COUNT(*) FROM reservations {$whereClause}";
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total count: " . $e->getMessage());
            throw new Exception("예약 총 개수 조회 중 오류가 발생했습니다.");
        }
    }
}
?> 