<?php

namespace App\Enum;

/**
 * Decision to use an enum instead of a database table:
 *
 * 1. **Performance Considerations**:
 *    Using an enum allows for faster lookups and comparisons since the values are stored in the codebase
 *    and are represented as integers in the database. This avoids the need for an additional join operation
 *    with a `stocks` table, which could slow down queries involving stock prices.
 *
 * 2. **Simplicity and Maintainability**:
 *    The set of tracked stock symbols is relatively small and fixed. Storing them as an enum in the codebase
 *    simplifies the data model and reduces the need for maintaining a separate `stocks` table in the database.
 *
 * 3. **Type Safety**:
 *    Enums provide type safety, ensuring that only valid stock symbols can be used throughout the application.
 *    This helps prevent errors that might arise from using invalid or misspelled stock symbols.
 *
 * 4. **Centralized Management**:
 *    All stock symbols are centralized within the enum class, making it easier to manage and update the list of
 *    supported stocks. Any changes to the stock symbols are explicitly defined in one place, simplifying updates.
 *
 * 5. **Denormalization**:
 *    This approach can be seen as a form of denormalization because it embeds the stock symbol data directly in
 *    the application code rather than storing it in a separate table in the database. While this slightly
 *    violates the principles of database normalization, it is justified in this context due to the fixed and
 *    limited nature of the data, and the performance benefits it provides.
 *
 * Note: If the list of stock symbols becomes dynamic or grows significantly, we may need to reconsider this
 * approach and migrate to a database table to allow for easier updates and scalability.
 */
enum Stock: int
{
    case APPLE = 1;
    case GOOGLE = 2;
    case MICROSOFT = 3;
    case ADOBE = 4;
    case AMD = 5;
    case NVIDIA = 6;
    case AMAZON = 7;
    case WALMART = 8;
    case NETFLIX = 9;
    case AIRBNB = 10;

    public function symbol(): string
    {
        return match ($this) {
            self::APPLE => 'AAPL',
            self::GOOGLE => 'GOOGL',
            self::MICROSOFT => 'MSFT',
            self::ADOBE => 'ADBE',
            self::AMD => 'AMD',
            self::NVIDIA => 'NVDA',
            self::AMAZON => 'AMZN',
            self::WALMART => 'WMT',
            self::NETFLIX => 'NFLX',
            self::AIRBNB => 'ABNB',
        };
    }

    public static function symbols(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            $result[$case->symbol()] = $case;
        }

        return $result;
    }
}
