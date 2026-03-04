<?php  // PHP 코드의 시작을 알리는 약속입니다.

// 1. 에러 출력 설정 (개발 단계에서 어디가 틀렸는지 보기 위해 켭니다)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. 접속 정보 변수 설정 (RDS의 주소와 문 열쇠들)
$host = "rds-practice.clsggeq0wcpf.ap-northeast-2.rds.amazonaws.com"; // RDS 엔드포인트
$user = "admin"; // DB 마스터 이름
$pw = getenv('DB_PASSWORD'); // 이제 코드에 직접 적지 않고 밖에서 주입받습니다.
$db = "practice_db"; // 접속할 데이터베이스 이름
$table = "users"; // 데이터를 가져올 테이블 이름

// 3. MySQL 연결 객체 생성 (mysqli라는 도구를 사용해 접속을 시도합니다)
$conn = new mysqli($host, $user, $pw, $db);

// 4. 연결 실패 시 에러 메시지 출력 후 종료
if ($conn->connect_error) {
    die("<h2>🚨 DB 연결 실패: " . $conn->connect_error . "</h2>");
}

echo "<h2>🎉 웹 서버(EC2)가 RDS DB의 정보를 불러왔습니다.</h2>";

// 5. SQL 쿼리 준비 (명령어: "users 테이블의 모든 내용을 가져와라")
$sql = "SELECT * FROM " . $table;
$result = $conn->query($sql); // 명령어를 실행하고 결과를 $result에 담습니다.

// 6. 결과가 1개라도 있다면 표(Table)를 그리기 시작합니다.
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; text-align: center;'><tr>";
    
    // 표의 머리글(Column Name)을 자동으로 추출해서 출력합니다.
    $fields = $result->fetch_fields();
    foreach ($fields as $field) {
        echo "<th style='background-color: #f2f2f2;'>" . htmlspecialchars($field->name) . "</th>";
    }
    echo "</tr>";
    
    // 가져온 데이터를 한 줄씩(Row) 반복하며 칸(td)에 채워 넣습니다.
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $val) {
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

} else {
    echo "<p>테이블은 연결되었으나 데이터가 비어있습니다.</p>";
}

// 7. 모든 작업이 끝나면 DB 연결을 닫습니다. (자원 절약)
$conn->close();
?>