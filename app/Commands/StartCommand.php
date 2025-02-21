<?php
namespace App\Commands;
use App\Classes\Helpers\NotificationHelper;
use App\Models\TelegramUser;
use App\Repositories\TelegramUsersRepository;
use App\Repositories\WhiteListUserRepository;
use Faker\Provider\Payment;
use Illuminate\Support\Traits\EnumeratesValues;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Payments\Invoice;
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
        //TODO: убрать если надо будет открыть бота всем.
        if($this->getUpdate()->message->from->id !== env('ADMIN_TELEGRAM_USER_ID')) {
            Telegram::bot()->sendMessage([
                'chat_id' => $this->getUpdate()->message->from->id,
                'text' => "Unfortunately, this action is not available to you. The bot is still under development.",
                'parse_mode' => 'HTML'
            ]);
        }

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

        //Если все ок - отправляем главное меню
        //$this->sendMainMenu($telegramUser);

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

        /*$keyboard = Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => '⭐️ Top up my stars balance',
                        'callback_data' => 'Balance_showInvoices',
                    ],
                ],
                [
                    [
                        'text' => '⚡️ Quick purchase of multiple gifts',
                        'callback_data' => 'QuickPurchase_showInfo',
                    ],
                ],
                [
                    [
                        'text' => '🕒 Automatic purchase of new gifts',
                        'callback_data' => 'Appointment_showAvailableDates',
                    ],
                ],
            ],
            'resize_keyboard' => true,
        ]);*/

        $this->replyWithMessage([
            'text' => 'Нажмите на кнопку, чтобы запустить Web App:',
            'reply_markup' => $keyboard
        ]);

        //Если юзер не авторизовался - отправляем дефолтное сообщение
        /*if (!$telegramUser->is_auth) {
            $this->sendWelcomeMessageIfUserNotAuthorized();
            return;
        }*/
    }

    /**
     * Получаем разметку главного меню.
     *
     * @return EnumeratesValues|Keyboard
     */
    public function getMainMenuMarkup(): EnumeratesValues|Keyboard
    {
        return Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => '⭐️ Top up my stars balance',
                        'callback_data' => 'Balance_showInvoices',
                    ],
                ],
                [
                    [
                        'text' => '⚡️ Quick purchase of multiple gifts',
                        'callback_data' => 'QuickPurchase_showInfo',
                    ],
                ],
                [
                    [
                        'text' => '🕒 Automatic purchase of new gifts',
                        'callback_data' => 'Appointment_showAvailableDates',
                    ],
                ],
            ],
            'resize_keyboard' => true,
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
            NotificationHelper::SendNotificationToChannel('Не получилось создать запись', $userData->toArray());
            return;
        }

        //Отправляем уведомление о добавлении нового юзера
        NotificationHelper::SendNotificationToChannel('Добавили нового пользователя', $userData->toArray());
    }


    /**
     * @param int $userId
     * @param int $messageId
     * @param BotsManager $botsManager
     *
     * @return void
     *
     * @throws TelegramSDKException
     */
    public function checkIsUserInWhiteList(int $userId, int $messageId, BotsManager $botsManager): void
    {
        //Пробуем найти есть ли юзер в белом списке
        /*$whiteListUser = $this->whiteListUserRepository->findUserById($userId);

        //Если юзера нет, зададим сообщение и разметку
        if (!$whiteListUser) {
            $msg = 'К сожалению вас не добавили в белый список. Пожалуйста свяжитесь с администратором';
            $reply_markup = Keyboard::make([
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Написать администратору',
                            'url' => 'https://t.me/indertruster',
                        ],
                    ]
                ],
                'resize_keyboard' => true,
            ]);
        } else {
            //Если юзер есть в белом списке - изменим его статус авторизации
            $telegramUser = $this->telegramUsersRepository->findOneUserByUserId($userId);
            $telegramUser->is_auth = 1;
            $telegramUser->save();
            //Отправим ему главное меню
            $msg = $this->getMaiMenuMsg();
            $reply_markup = $this->getMainMenuMarkup();
        }*/
        $msg = 'К сожалению вас не добавили в белый список. Пожалуйста свяжитесь с администратором';
        $reply_markup = Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Написать администратору',
                        'url' => 'https://t.me/indertruster',
                    ],
                ]
            ],
            'resize_keyboard' => true,
        ]);

        //Отправка ответа с изменение сообщения
        $bot = $botsManager->bot();
        $bot->editMessageText([
            'chat_id'                  => $userId,
            'message_id'               => $messageId,
            'text'                     => $msg,
            'reply_markup'             => $reply_markup
        ]);
    }

    /**
     * @param TelegramUser $telegramUser
     * @return string
     */
    public function getMaiMenuMsg(TelegramUser $telegramUser): string
    {
        $msg = "<b>{$telegramUser->username}</b>, Welcome to our Telegram Gifts Helper Bot.\n";

        $msg .= "\n<b>Current balance</b>: {$telegramUser->balance} ⭐";
        $msg .= "\n<b>Automatic purchase status</b>: "; $msg .= $telegramUser->is_buy_active ? "✅" : "⛔️";
        $msg .= "\n<b>Affiliate program stats</b>: 0 ⭐";
        return $msg;
    }

    /**
     * Отправляем главное меню юзеру.
     *
     * @param TelegramUser $telegramUser
     * @return void
     */
    public function sendMainMenu(TelegramUser $telegramUser): void
    {
        $reply_markup = $this->getMainMenuMarkup();
        $this->replyWithMessage([
            'parse_mode' => 'HTML',
            'text' => $this->getMaiMenuMsg($telegramUser),
            'reply_markup' =>$reply_markup
        ]);
    }
}
