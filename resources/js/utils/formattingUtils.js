/**
 * Форматирует цену в российский формат без копеек.
 * @param {number} price - Цена для форматирования
 * @returns {string} Отформатированная цена
 */
export const formatPrice = (price) => {
    // Преобразуем цену в целое число, убирая копейки, если они нулевые
    const formatted = Math.floor(price);

    // Форматируем число с разделителями тысяч
    return formatted.toLocaleString('ru-RU');
};
