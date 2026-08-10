Leaderboard & FCM Push Notifications API
REST API сервис на Laravel с поддержкой системы лидеров (Leaderboard) и отправкой асинхронных Push-уведомлений через Firebase Cloud Messaging (FCM) и Redis Queue.

🛠 Технологический стек
Framework: Laravel 11.x

Database: PostgreSQL / MySQL

Queue / Cache: Redis

Push Notifications: Firebase Cloud Messaging (FCM V1 API) via kreait/laravel-firebase

Frontend Test Environment: Native JavaScript (ES6 Modules) + Web Push Service Worker

🚀 Требования к среде
PHP >= 8.2

Composer

Redis Server

Node.js & NPM (опционально, при наличии фронтенд-сборщика)

⚙️ Установка и настройка
1. Клонирование репозитория и установка зависимостей
   Bash
   git clone https://github.com/your-username/leaderboard.git
   cd leaderboard

composer install
2. Настройка файла окружения (.env)
   Скопируйте пример файла конфигурации:

Bash
cp .env.example .env
php artisan key:generate
Настройте подключение к базе данных и Redis в .env:

3. Настройка Firebase Service Account
   Перейдите в Firebase Console -> Project Settings -> Service accounts.

Нажмите Generate new private key (Сгенерировать новый приватный ключ).

Сохраните скачанный .json файл по пути:

storage/app/firebase/firebase_credentials.json

Важно: Убедитесь, что в Google Cloud Console для вашего проекта включен Firebase Cloud Messaging API (V1).

4. Применение миграций и сидов
   Запустите миграции для создания структуры БД (включая поле fcm_token в таблице users):

Bash
php artisan migrate --seed
⚡ Использование очередей (Queue Workers)
Так как отправка push-уведомлений выполняется асинхронно, обработку задач выполняет отдельный воркер.

Запуск воркера обработчика очередей:

Bash
php artisan queue:work redis
Для просмотра и перезапуска упавших задач:

Bash
# Список упавших задач
php artisan queue:failed

# Повторный запуск всех упавших задач
php artisan queue:retry all
🧪 Тестирование Push-уведомлений в браузере
Проект содержит изолированную тестовую страницу для проверки получения реального fcm_token и приема Web Push без необходимости запуска мобильного приложения.

1. Настройка конфигурации веб-клиента
   Откройте public/test-push.html и public/firebase-messaging-sw.js и вставьте данные вашей веб-конфигурации из Firebase Console (Project Settings -> General -> Your apps -> Web):

JavaScript
const firebaseConfig = {
apiKey: "YOUR_API_KEY",
authDomain: "YOUR_PROJECT.firebaseapp.com",
projectId: "YOUR_PROJECT_ID",
storageBucket: "YOUR_PROJECT.appspot.com",
messagingSenderId: "YOUR_SENDER_ID",
appId: "YOUR_APP_ID"
};
Добавьте ваш VAPID Key (из Project Settings -> Cloud Messaging -> Web Push certificates) в public/test-push.html.

2. Инструкция по тестированию
   Запустите локальный сервер:

Bash
php artisan serve
Откройте страницу в браузере: http://localhost:8000/test-push.html

Нажмите кнопку «Разрешить уведомления и получить токен».

Скопируйте сгенерированный fcm_token.

Откройте php artisan tinker и привяжите токен к пользователю:

PHP
$user = App\Models\User::find(1);
$user->fcm_token = 'ВАШ_ПОЛУЧЕННЫЙ_ТОКЕН';
$user->save();

App\Jobs\SendPushNotification::dispatch(
$user->fcm_token,
'Тестовое уведомление',
'Привет! Асинхронная отправка через Redis прошла успешно.'
);
Убедитесь, что запущен php artisan queue:work redis, и сверните вкладку браузера для получения системного Push-уведомления.

🏗 Архитектура отправки уведомлений
Plaintext
[ Client API Request ] ──► [ Laravel Controller ]
│
▼ (Instant response 200 OK)
[ Dispatch Job to Redis Queue ]
│
▼ (Async Background Worker)
[ Queue Worker (Artisan) ]
│
▼ (HTTP V1 API Request)
[ Firebase Cloud Messaging ]
│
▼
[ Target Device / Browser ]
