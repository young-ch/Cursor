<?php
header('Content-Type: application/json');

// 업로드된 파일 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 파일 업로드 확인
        if (!isset($_FILES['htmlFile']) || $_FILES['htmlFile']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('파일 업로드에 실패했습니다.');
        }

        $file = $_FILES['htmlFile'];
        $options = json_decode($_POST['options'], true);

        // 파일 형식 검증
        if (!preg_match('/\.html?$/', $file['name'])) {
            throw new Exception('HTML 파일만 업로드 가능합니다.');
        }

        // 임시 파일 생성
        $tempDir = 'temp/';
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // 고유한 파일명 생성
        $uniqueId = uniqid();
        $tempFile = $tempDir . $uniqueId . '.html';
        $outputFile = $tempDir . $uniqueId . '.pptx';

        // 파일 업로드
        if (!move_uploaded_file($file['tmp_name'], $tempFile)) {
            throw new Exception('파일 저장에 실패했습니다.');
        }

        // HTML 파일 읽기
        $htmlContent = file_get_contents($tempFile);
        if ($htmlContent === false) {
            throw new Exception('파일을 읽을 수 없습니다.');
        }

        // HTML 내용을 PPT 형식으로 변환
        $pptContent = convertHtmlToPpt($htmlContent, $options, $outputFile);

        // 응답
        echo json_encode([
            'success' => true,
            'message' => '변환이 완료되었습니다.',
            'file' => [
                'name' => basename($outputFile),
                'path' => $outputFile,
                'size' => filesize($outputFile)
            ]
        ]);

        // 임시 HTML 파일 삭제
        unlink($tempFile);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// HTML을 PPT로 변환하는 함수
function convertHtmlToPpt($htmlContent, $options, $outputFile) {
    // HTML 태그 제거 및 텍스트 추출
    $text = strip_tags($htmlContent);
    
    // 줄바꿈 처리
    $text = str_replace(["\r\n", "\r", "\n"], "\n", $text);
    
    // PPT 형식의 XML 생성
    $pptXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:presentation xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
                xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main"
                xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <p:sldMaster>
        <p:txStyles>
            <p:titleStyle>
                <p:defPPr>
                    <p:defRPr sz="4400"/>
                </p:defPPr>
            </p:titleStyle>
        </p:txStyles>
    </p:sldMaster>
    <p:sld>
        <p:spTree>
            <p:sp>
                <p:nvSpPr>
                    <p:cNvPr id="1" name="Title"/>
                    <p:cNvSpPr>
                        <a:spLocks noGrp="1"/>
                    </p:cNvSpPr>
                    <p:nvPr>
                        <p:ph type="title"/>
                    </p:nvPr>
                </p:nvSpPr>
                <p:spPr>
                    <a:xfrm>
                        <a:off x="365125" y="365125"/>
                        <a:ext cx="9144000" cy="3651250"/>
                    </a:xfrm>
                </p:spPr>
                <p:txBody>
                    <a:bodyPr/>
                    <a:lstStyle/>
                    <a:p>
                        <a:pPr>
                            <a:defRPr sz="3200"/>
                        </a:pPr>
                        <a:r>
                            <a:rPr lang="ko-KR"/>
                            <a:t>' . htmlspecialchars($text) . '</a:t>
                        </a:r>
                    </a:p>
                </p:txBody>
            </p:sp>
        </p:spTree>
    </p:sld>
</p:presentation>';

    // PPT 파일 구조 생성
    $zip = new ZipArchive();
    $zipFile = tempnam(sys_get_temp_dir(), 'ppt_');
    
    if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
        try {
            // [Content_Types].xml
            $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/ppt/presentation.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.presentation.main+xml"/>
</Types>');

            // _rels/.rels
            $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="ppt/presentation.xml"/>
</Relationships>');

            // ppt/presentation.xml
            $zip->addFromString('ppt/presentation.xml', $pptXml);

            // ppt/_rels/presentation.xml.rels
            $zip->addFromString('ppt/_rels/presentation.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideMaster" Target="slideMasters/slideMaster1.xml"/>
</Relationships>');

            $zip->close();
            
            // ZIP 파일을 PPTX로 복사
            if (!copy($zipFile, $outputFile)) {
                throw new Exception('PPT 파일 복사에 실패했습니다.');
            }
            
            return true;
        } catch (Exception $e) {
            $zip->close();
            unlink($zipFile);
            throw $e;
        }
    }
    
    throw new Exception('PPT 파일 생성에 실패했습니다.');
}

// 파일 다운로드 처리
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['download'])) {
    $file = basename($_GET['download']); // 파일명만 추출
    $filePath = 'temp/' . $file;

    if (file_exists($filePath) && is_file($filePath)) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo '파일을 찾을 수 없습니다.';
    }
}
?> 