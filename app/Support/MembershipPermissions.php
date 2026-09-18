<?php

namespace App\Support;

final class MembershipPermissions
{
    /** @return list<string> */
    public static function keys(): array
    {
        return [
            'view_progress',
            'edit_wedding',
            'create_requests',
            'approve_proposals',
            'share_invitation',
            'view_finance',
            'scan_passes',
        ];
    }

    /** @return array<string, bool> */
    public static function empty(): array
    {
        return array_fill_keys(self::keys(), false);
    }
}
