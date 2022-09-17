<?php

if (!function_exists('get_subdomain_to_send_mail')) {
    function get_subdomain_to_send_mail($string)
    {
        return str_replace('https', $string, config('app.url'));
    }
}

if (!function_exists('get_info_by_role')) {
    function get_info_by_role($data, string $roleName)
    {
        foreach ($data as $member) {
            foreach ($member->member_role as $role) {
                if ($role->title == $roleName){

                    return $member->team_member?->email;
                }
            }
        }
        return null;
    }
}
