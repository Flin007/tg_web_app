<template>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full justify-center p-4 text-center items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                <div>
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                        <svg fill="#dc2626" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M402.7 425.3l-316-316C52.6 148.6 32 199.9 32 256c0 123.7 100.3 224 224 224c56.1 0 107.4-20.6 146.7-54.7zm22.6-22.6C459.4 363.4 480 312.1 480 256C480 132.3 379.7 32 256 32c-56.1 0-107.4 20.6-146.7 54.7l316 316zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                            Доступ на сайт запрещён
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                <!-- Неверные данные из Telegram -->
                                <span v-if="telegramUserStore.isValidData === false">
                                    Получили неверные данные из Telegram, пожалуйста перезапустите приложение.
                                </span>
                                <!-- Не смогли определить пользователя -->
                                <span v-else-if="!telegramUserStore.user?.id">
                                    Не смогли определить пользователя Telegram, пожалуйста перезапустите приложение или свяжитесь с администратором, если ошибка повторится.
                                </span>
                                <!-- Нет разрешения на сообщения -->
                                <span v-else-if="telegramUserStore.user.status !== 'member'">
                                    Вы не дали разрешения нашему боту писать вам сообщения, пожалуйста перезапустите приложение и дайте разрешение, это важно для получения обратной связи.
                                </span>
                                <!-- Пользователь заблокирован -->
                                <span v-else-if="telegramUserStore.user.is_blocked">
                                    К сожалению, ваш аккаунт был заблокирован в нашем сервисе, пожалуйста свяжитесь с администратором, если считаете, что произошла ошибка.
                                </span>
                                <!-- В других случаях -->
                                <span v-else>
                                    Произошла непредвиденная ошибка, пожалуйста попробуйте перезагрузить приложение или свяжитесь с администратором если ошибка повторяется.
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <!-- Неверные данные из Telegram -->
                    <a v-if="telegramUserStore.isValidData === false" href="https://t.me/t3zusauto_bot"
                       class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        Перейти в бота
                    </a>
                    <!-- Не смогли определить пользователя -->
                    <a v-else-if="!telegramUserStore.user?.id" href="https://t.me/notcollector"
                       class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        Написать администратору
                    </a>
                    <!-- Нет разрешения на сообщения -->
                    <a v-else-if="telegramUserStore.user.status !== 'member'" href="https://t.me/t3zusauto_bot"
                       class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        Перейти в бота
                    </a>
                    <!-- Пользователь заблокирован -->
                    <a v-else-if="telegramUserStore.user.is_blocked" href="https://t.me/notcollector"
                       class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        Написать администратору
                    </a>
                    <!-- В других случаях -->
                    <a v-else href="https://t.me/notcollector"
                       class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        Написать администратору
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useTelegramUserStore} from "../../stores/telegramUser.js";

const telegramUserStore = useTelegramUserStore();
</script>
