<?php
namespace App\Commands;
use App\Helpers\TelegramBotNotificationHelper;
use App\Models\TelegramUser;
use App\Repositories\TelegramUsersRepository;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\User;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start / Restart command';
    protected TelegramUser $telegramUser;
    protected TelegramUsersRepository $telegramUsersRepository;

    public function __construct() {
        //Через app, дабы не прокидывать классы в конструктор из Webhook контроллера
        $this->telegramUser = app(TelegramUser::class);
        $this->telegramUsersRepository = app(TelegramUsersRepository::class);
    }

    /**
     * Метод, который дергается при вызове команды
     *
     * @return void
     */
    public function handle(): void
    {
        //Получаем всю информацию о пользователе
        $userData = $this->getUpdate()->message->from;
        //Получаем его уникальный ID
        $userId = $userData->id;
        //Пробуем найти юзера в БД
        /** @var TelegramUser $telegramUser */
        $telegramUser = $this->telegramUsersRepository->findOneUserByUserId($userId);

        //Если юзера не нашли - добавляем
        if (!$telegramUser) {
            $this->addNewTelegramUser($userData);
            return;
        }

        $keyboard = Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Запустить Web App',
                        'web_app' => ['url' => 'https://t3zusauto.ru/']
                    ],
                ],
            ],
            'resize_keyboard' => true,
        ]);

        $this->replyWithMessage([
            'text' => 'Нажмите на кнопку, чтобы запустить Web App:',
            'reply_markup' => $keyboard
        ]);
    }

    /**
     * Добавление пользователя в базу данных. И уведомление в тг группу
     *
     * @param User $userData
     *
     * @return void
     */
    public function addNewTelegramUser(User $userData): void
    {
        $res =  $this->telegramUser->updateOrCreate([
            'user_id' => $userData->id,
        ],[
            'username' => $userData->username,
            'first_name' => $userData->first_name ?? 'Не указано',
            'last_name' => $userData->last_name ?? 'Не указано',
            'is_premium' => $userData->is_premium ?? 0,
            'is_bot' => $userData->is_bot ?? 0,
        ]);

        //Не получилось ничего создать?
        if (!isset($res->id)){
            TelegramBotNotificationHelper::sendLog('Не получилось создать запись', $userData->toArray());
            return;
        }

        //Отправляем уведомление о добавлении нового юзера
        TelegramBotNotificationHelper::sendLog('Добавили нового пользователя', $userData->toArray());
    }
}
