# 1. 베이스 이미지 선택: 이미 누군가 PHP 8.1과 FPM을 깔아둔 가벼운 리눅스를 가져옵니다.
FROM php:8.1-fpm

# 2. 추가 부품 설치: PHP 기본형에는 DB 접속 기능이 없으므로, MySQL용 부품(mysqli, pdo)을 설치합니다.
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 3. 코드 복사: 내 컴퓨터(.)에 있는 파일들을 상자 안의 웹 경로(/usr/share/nginx/html/)로 다 집어넣습니다.
COPY . /usr/share/nginx/html/

# 4. 작업 경로 설정: 앞으로 이 상자 안에서 명령어를 칠 때는 이 폴더를 기준으로 합니다.
WORKDIR /usr/share/nginx/html/

# 5. 주인 바꾸기: 웹 서버 엔진이 파일을 읽을 수 있도록 파일 소유자를 www-data로 바꿉니다.
RUN chown -R www-data:www-data /usr/share/nginx/html/